<?php
class ControllerModuleJustPurchasedNotification extends Controller {
	protected function index($setting) {
		$this->document->addScript('catalog/view/javascript/jquery/jquery.notify.js');
		$this->document->addStyle('https://fonts.googleapis.com/css?family=PT+Sans&subset=latin,cyrillic-ext,latin-ext,cyrillic');
		
		if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/just_purchased_notification.css')) {
			$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/just_purchased_notification.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/just_purchased_notification.css');
		}
	
		$this->load->model('module/just_purchased_notification');
		$this->load->model('tool/image');
		
		$find = array(
			'{country}',
			'{zone}',
			'{city}',
			'{quantity}',
			'{product_with_link}'
		);
		
		$just_purchased_notification_localisation = $this->config->get('just_purchased_notification_localisation');
		$jpnl_current = $just_purchased_notification_localisation[$this->config->get('config_language_id')];
		$use_cache = $this->config->get('just_purchased_notification_cache');
		$shuffle = $this->config->get('just_purchased_notification_shuffle');
		
		if ($this->config->get('just_purchased_notification_hide_older')){
			$hide_older = (int)$this->config->get('just_purchased_notification_hide_older');
		} else {
			$hide_older = false;
		}
		
		$this->data['notifications'] = array();
		
		$notifications = $this->model_module_just_purchased_notification->getNotifications();
		
		if ($notifications) {
		
			if ($shuffle) {
				shuffle($notifications);
			}
			
			foreach($notifications as $notification) {
				$replace = array(
					'country' 			=> $notification['country'],
					'zone'    			=> $notification['zone'],
					'city'    			=> $notification['city'],
					'quantity'    	    => $notification['quantity'],
					'product_with_link' => '<a href="' . $this->url->link('product/product', 'product_id=' . $notification['product_id']) . '">' . $notification['product_name']  . '</a>'
				);
				
				if ($notification['image']) {
					$image = $this->model_tool_image->resize($notification['image'], $this->config->get('just_purchased_notification_image_width'), $this->config->get('just_purchased_notification_image_height'));	
				} else {
					$image = $this->model_tool_image->resize('no_image.jpg', $this->config->get('just_purchased_notification_image_width'), $this->config->get('just_purchased_notification_image_height'));	
				}
				
				if ($use_cache) {
					$now = date('Y-m-d H:i:s');
					$date_added = $notification['date_added'];
					$seconds_ago = strtotime($now) - strtotime($date_added) - $notification['php_mysl_time_diff'];
					
				} else {
					$seconds_ago = $notification['time_ago'];
				}
				
				$show_time_ago = true;
				
				if ($hide_older) {
					$older_seconds = $hide_older * 60 * 60;
					
					if ($seconds_ago > $older_seconds) {
						$show_time_ago = false;
					}					
				}
				
				
				$this->data['notifications'][] = array(
					'image'         => $image,
					'message'       => str_replace($find, $replace, $jpnl_current['message']),
					'time_ago'      => $this->timeAgoSecondsToText($seconds_ago),
					'product_href'  => $this->url->link('product/product', 'product_id=' . $notification['product_id']),
					'show_time_ago' => $show_time_ago
				);
			}
			
			$this->data['background_color'] = $this->config->get('just_purchased_notification_background_color');
			$this->data['border_color'] = $this->config->get('just_purchased_notification_border_color');
			$this->data['text_color'] = $this->config->get('just_purchased_notification_text_color');
			$this->data['link_color'] = $this->config->get('just_purchased_notification_link_color');
			
			$this->data['speed'] = (int)$this->config->get('just_purchased_notification_speed') * 1000;
			$this->data['expire'] = (int)$this->config->get('just_purchased_notification_expire') * 1000;
			$this->data['click'] = $this->config->get('just_purchased_notification_click');
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/just_purchased_notification.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/just_purchased_notification.tpl';
			} else {
				$this->template = 'default/template/module/just_purchased_notification.tpl';
			}

			$this->render();
		}
	}
	
	private function timeAgoSecondsToText($seconds) {
		$just_purchased_notification_localisation = $this->config->get('just_purchased_notification_localisation');
		$jpnl_current = $just_purchased_notification_localisation[$this->config->get('config_language_id')];
		
		$minutes = floor($seconds / 60);
		
		if ($minutes < 1) {
			$minutes = 1;
		}
		
		$hours   = floor($seconds / 60 / 60);
		$days    = floor($seconds / 60 / 60 / 24);
		
		if ( $minutes <= 59) {
			return str_replace("{time_counter}", $minutes, $jpnl_current['minute']);
		} elseif ($hours <= 23) {
			return str_replace("{time_counter}", $hours, $jpnl_current['hour']);
		} else {
			return str_replace("{time_counter}", $days, $jpnl_current['day']);
		}
	}
}
?>