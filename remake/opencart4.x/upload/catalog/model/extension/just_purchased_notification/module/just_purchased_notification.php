<?php
namespace Opencart\Catalog\Model\Extension\JustPurchasedNotification\Module;

class JustPurchasedNotification extends \Opencart\System\Engine\Model {
    public function getRecentOrderProducts(array $setting): array {
        $order_statuses = $setting['module_just_purchased_notification_order_status'] ?? [];

        if (!$order_statuses) {
            return [];
        }

        $exclude_product_ids = $this->parseProductIds((string)($setting['module_just_purchased_notification_exclude_product_ids'] ?? ''));
        $cache_ttl = (int)($setting['module_just_purchased_notification_cache_ttl'] ?? 0);

        $cache_key_data = [
            (int)$this->config->get('config_store_id'),
            (int)$this->config->get('config_language_id'),
            (int)($setting['module_just_purchased_notification_limit'] ?? 15),
            implode('-', $order_statuses),
            implode('-', $exclude_product_ids)
        ];

        $cache_key = 'just_purchased_notification.' . md5(implode('|', $cache_key_data));
        $rows = [];

        if (!empty($setting['module_just_purchased_notification_cache'])) {
            $cached = $this->cache->get($cache_key);

            if (is_array($cached)) {
                if ($cache_ttl > 0 && isset($cached['cached_at']) && ((time() - $cached['cached_at']) > $cache_ttl)) {
                    $rows = [];
                } else {
                    $rows = $cached['rows'] ?? [];
                }
            }
        }

        if (!$rows) {
            $sql = "SELECT o.order_id, o.shipping_country AS country, o.shipping_zone AS zone, o.shipping_city AS city, o.date_added, op.product_id, op.quantity, p.image, pd.name AS product_name
                    FROM `" . DB_PREFIX . "order` o
                    INNER JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id)
                    INNER JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id)
                    INNER JOIN `" . DB_PREFIX . "product_description` pd ON (op.product_id = pd.product_id)
                    INNER JOIN `" . DB_PREFIX . "product_to_store` p2s ON (op.product_id = p2s.product_id)
                    WHERE o.order_status_id IN (" . implode(',', array_map('intval', $order_statuses)) . ")
                      AND p.status = 1
                      AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
                      AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

            if ($exclude_product_ids) {
                $sql .= " AND op.product_id NOT IN (" . implode(',', $exclude_product_ids) . ")";
            }

            $sql .= " ORDER BY o.order_id DESC, op.order_product_id DESC
                      LIMIT " . (int)($setting['module_just_purchased_notification_limit'] ?? 15);

            $query = $this->db->query($sql);
            $rows = $query->rows;

            if (!empty($setting['module_just_purchased_notification_cache'])) {
                $this->cache->set($cache_key, [
                    'cached_at' => time(),
                    'rows' => $rows
                ]);
            }
        }

        return $rows;
    }

    private function parseProductIds(string $raw): array {
        if (!$raw) {
            return [];
        }

        $ids = [];

        foreach (explode(',', $raw) as $id) {
            $id = (int)trim($id);

            if ($id > 0) {
                $ids[] = $id;
            }
        }

        return array_values(array_unique($ids));
    }
}
