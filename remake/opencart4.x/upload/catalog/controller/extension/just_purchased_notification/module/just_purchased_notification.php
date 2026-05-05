<?php
namespace Opencart\Catalog\Controller\Extension\JustPurchasedNotification\Module;

class JustPurchasedNotification extends \Opencart\System\Engine\Controller {
    public function index(array $setting = []): string {
        $setting = $this->resolveSetting($setting);

        if (empty($setting['module_just_purchased_notification_status'])) {
            return '';
        }

        $this->load->model('extension/just_purchased_notification/module/just_purchased_notification');
        $this->load->model('tool/image');

        $rows = $this->model_extension_just_purchased_notification_module_just_purchased_notification->getRecentOrderProducts($setting);

        if (!$rows) {
            return '';
        }

        if (!empty($setting['module_just_purchased_notification_shuffle'])) {
            shuffle($rows);
        }

        $notifications = [];

        foreach ($rows as $row) {
            $product_url = $this->url->link('product/product', 'product_id=' . (int)$row['product_id']);
            $time_ago_text = $this->formatTimeAgo((string)$row['date_added'], $setting);

            $replace = [
                '{country}' => (string)$row['country'],
                '{zone}' => (string)$row['zone'],
                '{city}' => (string)$row['city'],
                '{quantity}' => (int)$row['quantity'],
                '{product_name}' => (string)$row['product_name'],
                '{product_link}' => $product_url,
                '{product_with_link}' => '<a href="' . $product_url . '">' . (string)$row['product_name'] . '</a>',
                '{time_ago}' => $time_ago_text
            ];

            $message = strtr((string)$setting['module_just_purchased_notification_message_template'], $replace);
            $show_time_ago = !empty($setting['module_just_purchased_notification_show_time_ago']) && $time_ago_text !== '';

            $notifications[] = [
                'image' => $this->buildProductImage((string)$row['image'], $setting),
                'message' => $message,
                'time_ago' => $time_ago_text,
                'show_time_ago' => $show_time_ago,
                'product_href' => $product_url
            ];
        }

        $data['notifications'] = $notifications;
        $data['config'] = [
            'delay' => (int)$setting['module_just_purchased_notification_delay'],
            'display_time' => (int)$setting['module_just_purchased_notification_display_time'],
            'start_delay' => (int)$setting['module_just_purchased_notification_start_delay'],
            'click_anywhere' => !empty($setting['module_just_purchased_notification_click_anywhere']),
            'position' => (string)$setting['module_just_purchased_notification_position'],
            'motion' => (string)$setting['module_just_purchased_notification_motion'],
            'bottom_offset' => (int)$setting['module_just_purchased_notification_bottom_offset'],
            'side_offset' => (int)$setting['module_just_purchased_notification_side_offset'],
            'debug' => !empty($setting['module_just_purchased_notification_debug']),
            'styles' => [
                'background' => (string)$setting['module_just_purchased_notification_background_color'],
                'border' => (string)$setting['module_just_purchased_notification_border_color'],
                'text' => (string)$setting['module_just_purchased_notification_text_color'],
                'link' => (string)$setting['module_just_purchased_notification_link_color']
            ]
        ];

        $this->document->addStyle('extension/just_purchased_notification/catalog/view/stylesheet/just_purchased_notification.css');
        $this->document->addScript('extension/just_purchased_notification/catalog/view/javascript/just_purchased_notification.js');

        return $this->load->view('extension/just_purchased_notification/module/just_purchased_notification', $data);
    }

    public function injectInFooter(string &$route, array &$args, string &$output): void {
        $setting = $this->resolveSetting();

        if (empty($setting['module_just_purchased_notification_status']) || empty($setting['module_just_purchased_notification_auto_inject'])) {
            return;
        }

        $module_output = $this->index($setting);

        if ($module_output) {
            $output .= $module_output;
        }
    }

    private function resolveSetting(array $override = []): array {
        $keys = [
            'module_just_purchased_notification_status',
            'module_just_purchased_notification_auto_inject',
            'module_just_purchased_notification_limit',
            'module_just_purchased_notification_order_status',
            'module_just_purchased_notification_shuffle',
            'module_just_purchased_notification_cache',
            'module_just_purchased_notification_cache_ttl',
            'module_just_purchased_notification_message_template',
            'module_just_purchased_notification_time_minute_template',
            'module_just_purchased_notification_time_hour_template',
            'module_just_purchased_notification_time_day_template',
            'module_just_purchased_notification_show_time_ago',
            'module_just_purchased_notification_hide_time_ago_after',
            'module_just_purchased_notification_image_width',
            'module_just_purchased_notification_image_height',
            'module_just_purchased_notification_background_color',
            'module_just_purchased_notification_border_color',
            'module_just_purchased_notification_text_color',
            'module_just_purchased_notification_link_color',
            'module_just_purchased_notification_delay',
            'module_just_purchased_notification_display_time',
            'module_just_purchased_notification_start_delay',
            'module_just_purchased_notification_click_anywhere',
            'module_just_purchased_notification_position',
            'module_just_purchased_notification_motion',
            'module_just_purchased_notification_bottom_offset',
            'module_just_purchased_notification_side_offset',
            'module_just_purchased_notification_exclude_product_ids',
            'module_just_purchased_notification_debug'
        ];

        $setting = [];

        foreach ($keys as $key) {
            $setting[$key] = $override[$key] ?? $this->config->get($key);
        }

        if (!is_array($setting['module_just_purchased_notification_order_status'])) {
            $setting['module_just_purchased_notification_order_status'] = [];
        }

        return $setting;
    }

    private function buildProductImage(string $image, array $setting): string {
        if (!$image) {
            $image = 'placeholder.png';
        }

        return $this->model_tool_image->resize($image, (int)$setting['module_just_purchased_notification_image_width'], (int)$setting['module_just_purchased_notification_image_height']);
    }

    private function formatTimeAgo(string $date_added, array $setting): string {
        if (empty($setting['module_just_purchased_notification_show_time_ago'])) {
            return '';
        }

        $seconds = time() - strtotime($date_added);

        if ($seconds < 0) {
            $seconds = 0;
        }

        $hide_after_hours = trim((string)$setting['module_just_purchased_notification_hide_time_ago_after']);

        if ($hide_after_hours !== '') {
            $max_seconds = ((int)$hide_after_hours) * 3600;

            if ($max_seconds > 0 && $seconds > $max_seconds) {
                return '';
            }
        }

        $minutes = max(1, (int)floor($seconds / 60));

        if ($minutes <= 59) {
            return str_replace('{time_counter}', (string)$minutes, (string)$setting['module_just_purchased_notification_time_minute_template']);
        }

        $hours = (int)floor($seconds / 3600);

        if ($hours <= 23) {
            return str_replace('{time_counter}', (string)$hours, (string)$setting['module_just_purchased_notification_time_hour_template']);
        }

        $days = max(1, (int)floor($seconds / 86400));

        return str_replace('{time_counter}', (string)$days, (string)$setting['module_just_purchased_notification_time_day_template']);
    }
}
