<?php
class ModelModuleJustPurchasedNotification extends Model { 
	
	public function getNotifications(){
		$use_cache = $this->config->get('just_purchased_notification_cache');
	
		if ($use_cache) {
			$notificatication_data = $this->cache->get('just_purchased_notification.' . (int)$this->config->get('config_store_id') . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('just_purchased_notification_limit') . '.' . implode('.', $this->config->get('just_purchased_notification_order_status')));
		} else {
			$notificatication_data = array();
		}		
		
		if (!$notificatication_data) {
			$sql = "SELECT o.shipping_country AS country, o.shipping_zone AS zone, o.shipping_city AS city, o.date_added, p.product_id, p.image, op.quantity, pd.name AS product_name, TIME_TO_SEC(TIMEDIFF(NOW(), o.date_added)) AS time_ago, TIME_TO_SEC(TIMEDIFF('" . date('Y-m-d H:i:s') . "', NOW())) AS php_mysl_time_diff 
					FROM `" . DB_PREFIX . "order` o
					LEFT JOIN " . DB_PREFIX . "order_product op ON (o.order_id = op.order_id)		
					LEFT JOIN " . DB_PREFIX . "product p ON (op.product_id = p.product_id)
					LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
					LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
					WHERE o.order_status_id IN (" . implode(',', $this->config->get('just_purchased_notification_order_status')) . ")
					AND p.status = 1 AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
					AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
					ORDER BY o.order_id DESC 
					LIMIT 0," . $this->config->get('just_purchased_notification_limit');
		
			$query = $this->db->query($sql);
				
			$notificatication_data = $query->rows;	
			
			if ($use_cache) {
				$this->cache->set('just_purchased_notification.' . (int)$this->config->get('config_store_id') . '.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('just_purchased_notification_limit') . '.' . implode('.', $this->config->get('just_purchased_notification_order_status')), $notificatication_data);
			}	
		}	
		
		return $notificatication_data; 	
	}
}
?>