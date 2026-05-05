<?php
class ControllerModuleJustPurchasedNotification extends Controller {
	private $error = array();
	private $version = '1.0';	

	public function index() {   
		$this->language->load('module/just_purchased_notification');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->document->addScript('view/javascript/jquery/colorpicker/colorpicker.js');
		$this->document->addStyle('view/stylesheet/colorpicker.css');
		$this->document->addStyle('view/stylesheet/just_purchased_notification.css');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {			
			$this->model_setting_setting->editSetting('just_purchased_notification', $this->request->post);		

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title') . ' ' . $this->version;

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_hours'] = $this->language->get('text_hours');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_message'] = $this->language->get('tab_message');
		$this->data['tab_design'] = $this->language->get('tab_design');
		$this->data['tab_extra']   = $this->language->get('tab_extra');
		$this->data['tab_help']   = $this->language->get('tab_help');

		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_shuffle'] = $this->language->get('entry_shuffle');
		$this->data['entry_cache'] = $this->language->get('entry_cache');
		
		$this->data['entry_message'] = $this->language->get('entry_message');
		$this->data['entry_time_ago'] = $this->language->get('entry_time_ago');
		$this->data['entry_time_ago_older'] = $this->language->get('entry_time_ago_older');
		$this->data['entry_time_ago_minute'] = $this->language->get('entry_time_ago_minute');
		$this->data['entry_time_ago_hour'] = $this->language->get('entry_time_ago_hour');
		$this->data['entry_time_ago_day'] = $this->language->get('entry_time_ago_day');
		$this->data['entry_hide_older'] = $this->language->get('entry_hide_older');
		
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['entry_background_color'] = $this->language->get('entry_background_color');
		$this->data['entry_border_color'] = $this->language->get('entry_border_color');
		$this->data['entry_text_color'] = $this->language->get('entry_text_color');
		$this->data['entry_link_color'] = $this->language->get('entry_link_color');
		$this->data['entry_image'] = $this->language->get('entry_image');
		
		$this->data['entry_speed'] = $this->language->get('entry_speed');
		$this->data['entry_expire'] = $this->language->get('entry_expire');
		$this->data['entry_click'] = $this->language->get('entry_click');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_clear_cache'] = $this->language->get('button_clear_cache');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['limit'])) {
			$this->data['error_limit'] = $this->error['limit'];
		} else {
			$this->data['error_limit'] = '';
		}
		
		if (isset($this->error['order_status'])) {
			$this->data['error_order_status'] = $this->error['order_status'];
		} else {
			$this->data['error_order_status'] = '';
		}		
		
		if (isset($this->error['message'])) {
			$this->data['error_message'] = $this->error['message'];
		} else {
			$this->data['error_message'] = array();
		}

		if (isset($this->error['minute'])) {
			$this->data['error_minute'] = $this->error['minute'];
		} else {
			$this->data['error_minute'] = array();
		}

		if (isset($this->error['hour'])) {
			$this->data['error_hour'] = $this->error['hour'];
		} else {
			$this->data['error_hour'] = array();
		}	

		if (isset($this->error['day'])) {
			$this->data['error_day'] = $this->error['day'];
		} else {
			$this->data['error_day'] = array();
		}		
	
		if (isset($this->error['hide_older'])) {
			$this->data['error_hide_older'] = $this->error['hide_older'];
		} else {
			$this->data['error_hide_older'] = '';
		}
		
		if (isset($this->error['image'])) {
			$this->data['error_image'] = $this->error['image'];
		} else {
			$this->data['error_image'] = '';
		}
		
		if (isset($this->error['background_color'])) {
			$this->data['error_background_color'] = $this->error['background_color'];
		} else {
			$this->data['error_background_color'] = '';
		}	

		if (isset($this->error['border_color'])) {
			$this->data['error_border_color'] = $this->error['border_color'];
		} else {
			$this->data['error_border_color'] = '';
		}	

		if (isset($this->error['text_color'])) {
			$this->data['error_text_color'] = $this->error['text_color'];
		} else {
			$this->data['error_text_color'] = '';
		}

		if (isset($this->error['link_color'])) {
			$this->data['error_link_color'] = $this->error['link_color'];
		} else {
			$this->data['error_link_color'] = '';
		}	

		if (isset($this->error['speed'])) {
			$this->data['error_speed'] = $this->error['speed'];
		} else {
			$this->data['error_speed'] = '';
		}

		if (isset($this->error['expire'])) {
			$this->data['error_expire'] = $this->error['expire'];
		} else {
			$this->data['error_expire'] = '';
		}			

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/just_purchased_notification', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('module/just_purchased_notification', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['token'] = $this->session->data['token'];
	
		if (isset($this->request->post['just_purchased_notification_limit'])) {
			$this->data['just_purchased_notification_limit'] = $this->request->post['just_purchased_notification_limit'];
		} elseif ($this->config->get('just_purchased_notification_limit')) {
			$this->data['just_purchased_notification_limit'] = $this->config->get('just_purchased_notification_limit');
		} else {
			$this->data['just_purchased_notification_limit'] = '';
		}
		
		if (isset($this->request->post['just_purchased_notification_order_status'])) {
			$this->data['just_purchased_notification_order_status'] = $this->request->post['just_purchased_notification_order_status'];
		} elseif ($this->config->get('just_purchased_notification_order_status')) {
			$this->data['just_purchased_notification_order_status'] = $this->config->get('just_purchased_notification_order_status');
		} else {
			$this->data['just_purchased_notification_order_status'] = array();
		}

		if (isset($this->request->post['just_purchased_notification_shuffle'])) {
			$this->data['just_purchased_notification_shuffle'] = $this->request->post['just_purchased_notification_shuffle'];
		} elseif ($this->config->get('just_purchased_notification_shuffle')) {
			$this->data['just_purchased_notification_shuffle'] = $this->config->get('just_purchased_notification_shuffle');
		} else {
			$this->data['just_purchased_notification_shuffle'] = '';
		}	
		
		if (isset($this->request->post['just_purchased_notification_cache'])) {
			$this->data['just_purchased_notification_cache'] = $this->request->post['just_purchased_notification_cache'];
		} elseif ($this->config->get('just_purchased_notification_cache')) {
			$this->data['just_purchased_notification_cache'] = $this->config->get('just_purchased_notification_cache');
		} else {
			$this->data['just_purchased_notification_cache'] = '';
		}		

		if (isset($this->request->post['just_purchased_notification_localisation'])) {
			$this->data['just_purchased_notification_localisation'] = $this->request->post['just_purchased_notification_localisation'];
		} elseif ($this->config->get('just_purchased_notification_localisation')) {
			$this->data['just_purchased_notification_localisation'] = $this->config->get('just_purchased_notification_localisation');
		} else {
			$this->data['just_purchased_notification_localisation'] = array();
		}

		if (isset($this->request->post['just_purchased_notification_hide_older'])) {
			$this->data['just_purchased_notification_hide_older'] = $this->request->post['just_purchased_notification_hide_older'];
		} elseif ($this->config->get('just_purchased_notification_hide_older')) {
			$this->data['just_purchased_notification_hide_older'] = $this->config->get('just_purchased_notification_hide_older');
		} else {
			$this->data['just_purchased_notification_hide_older'] = '';
		}		

		if (isset($this->request->post['just_purchased_notification_background_color'])) {
			$this->data['just_purchased_notification_background_color'] = $this->request->post['just_purchased_notification_background_color'];
		} elseif ($this->config->get('just_purchased_notification_background_color')) {
			$this->data['just_purchased_notification_background_color'] = $this->config->get('just_purchased_notification_background_color');
		} else {
			$this->data['just_purchased_notification_background_color'] = '#EEEEEE';
		}
		
		if (isset($this->request->post['just_purchased_notification_border_color'])) {
			$this->data['just_purchased_notification_border_color'] = $this->request->post['just_purchased_notification_border_color'];
		} elseif ($this->config->get('just_purchased_notification_border_color')) {
			$this->data['just_purchased_notification_border_color'] = $this->config->get('just_purchased_notification_border_color');
		} else {
			$this->data['just_purchased_notification_border_color'] = '#CCCCCC';
		}	

		if (isset($this->request->post['just_purchased_notification_text_color'])) {
			$this->data['just_purchased_notification_text_color'] = $this->request->post['just_purchased_notification_text_color'];
		} elseif ($this->config->get('just_purchased_notification_text_color')) {
			$this->data['just_purchased_notification_text_color'] = $this->config->get('just_purchased_notification_text_color');
		} else {
			$this->data['just_purchased_notification_text_color'] = '#333333';
		}

		if (isset($this->request->post['just_purchased_notification_link_color'])) {
			$this->data['just_purchased_notification_link_color'] = $this->request->post['just_purchased_notification_link_color'];
		} elseif ($this->config->get('just_purchased_notification_link_color')) {
			$this->data['just_purchased_notification_link_color'] = $this->config->get('just_purchased_notification_link_color');
		} else {
			$this->data['just_purchased_notification_link_color'] = '#40a1c9';
		}		
		
		if (isset($this->request->post['just_purchased_notification_image_width'])) {
			$this->data['just_purchased_notification_image_width'] = $this->request->post['just_purchased_notification_image_width'];
		} elseif ($this->config->get('just_purchased_notification_image_width')) {
			$this->data['just_purchased_notification_image_width'] = $this->config->get('just_purchased_notification_image_width');
		} else {
			$this->data['just_purchased_notification_image_width'] = '';
		}	

		if (isset($this->request->post['just_purchased_notification_image_height'])) {
			$this->data['just_purchased_notification_image_height'] = $this->request->post['just_purchased_notification_image_height'];
		} elseif ($this->config->get('just_purchased_notification_image_height')) {
			$this->data['just_purchased_notification_image_height'] = $this->config->get('just_purchased_notification_image_height');
		} else {
			$this->data['just_purchased_notification_image_height'] = '';
		}	

		if (isset($this->request->post['just_purchased_notification_speed'])) {
			$this->data['just_purchased_notification_speed'] = $this->request->post['just_purchased_notification_speed'];
		} elseif ($this->config->get('just_purchased_notification_speed')) {
			$this->data['just_purchased_notification_speed'] = $this->config->get('just_purchased_notification_speed');
		} else {
			$this->data['just_purchased_notification_speed'] = '';
		}

		if (isset($this->request->post['just_purchased_notification_expire'])) {
			$this->data['just_purchased_notification_expire'] = $this->request->post['just_purchased_notification_expire'];
		} elseif ($this->config->get('just_purchased_notification_expire')) {
			$this->data['just_purchased_notification_expire'] = $this->config->get('just_purchased_notification_expire');
		} else {
			$this->data['just_purchased_notification_expire'] = '';
		}	

		if (isset($this->request->post['just_purchased_notification_click'])) {
			$this->data['just_purchased_notification_click'] = $this->request->post['just_purchased_notification_click'];
		} elseif ($this->config->get('just_purchased_notification_click')) {
			$this->data['just_purchased_notification_click'] = $this->config->get('just_purchased_notification_click');
		} else {
			$this->data['just_purchased_notification_click'] = '';
		}			
	
		$this->data['modules'] = array();

		if (isset($this->request->post['just_purchased_notification_module'])) {
			$this->data['modules'] = $this->request->post['just_purchased_notification_module'];
		} elseif ($this->config->get('just_purchased_notification_module')) { 
			$this->data['modules'] = $this->config->get('just_purchased_notification_module');
		}		

		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$this->load->model('design/layout');
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->data['token'] = $this->session->data['token'];

		$this->template = 'module/just_purchased_notification.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	
	public function clearcache() {
		$this->language->load('module/just_purchased_notification');
		
		$json = array();
		
		$this->cache->delete('just_purchased_notification');
		
		$json['success'] = $this->language->get('text_cache_success');
		
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/just_purchased_notification')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$dinamic_strlen = 'utf8_strlen';
		
		if ( !function_exists('utf8_strlen') ) {
			$dinamic_strlen = 'strlen';
		}
		
		if ($dinamic_strlen($this->request->post['just_purchased_notification_limit']) < 1) {
			$this->error['limit'] = $this->language->get('error_limit');
			$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_general'));
		}
		
		if (!isset($this->request->post['just_purchased_notification_order_status']) || count($this->request->post['just_purchased_notification_order_status']) < 1){
			$this->error['order_status'] = $this->language->get('error_order_status');
			$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_general'));
		}		
		
		foreach ($this->request->post['just_purchased_notification_localisation'] as $language_id => $value) {
			if ($dinamic_strlen($value['message']) < 1) {
        		$this->error['message'][$language_id] = $this->language->get('error_message');
				$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_message'));
      		}
			
			if ($dinamic_strlen($value['minute']) < 1) {
        		$this->error['minute'][$language_id] = $this->language->get('error_minute');
				$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_message'));
      		}
			
			if ($dinamic_strlen($value['hour']) < 1) {
        		$this->error['hour'][$language_id] = $this->language->get('error_hour');
				$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_message'));
      		}

			if ($dinamic_strlen($value['day']) < 1) {
        		$this->error['day'][$language_id] = $this->language->get('error_day');
				$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_message'));
      		}			
		}
		
		if ($dinamic_strlen($this->request->post['just_purchased_notification_hide_older']) > 1) {
			if (!is_numeric($this->request->post['just_purchased_notification_hide_older'])) {
				$this->error['hide_older'] = $this->language->get('error_hide_older');
				$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_message'));
			}
		}
		
		if ($dinamic_strlen($this->request->post['just_purchased_notification_image_width']) < 1 || $dinamic_strlen($this->request->post['just_purchased_notification_image_height']) < 1) {
			$this->error['image'] = $this->language->get('error_image');
			$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_design'));
		}
		
		if ($dinamic_strlen($this->request->post['just_purchased_notification_background_color']) != 7 ) {
			$this->error['background_color'] = $this->language->get('error_color');
			$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_design'));
		}
		
		if ($dinamic_strlen($this->request->post['just_purchased_notification_border_color']) != 7 ) {
			$this->error['border_color'] = $this->language->get('error_color');
			$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_design'));
		}

		if ($dinamic_strlen($this->request->post['just_purchased_notification_text_color']) != 7 ) {
			$this->error['text_color'] = $this->language->get('error_color');
			$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_design'));
		}		
		
		if ($dinamic_strlen($this->request->post['just_purchased_notification_link_color']) != 7 ) {
			$this->error['link_color'] = $this->language->get('error_color');
			$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_design'));
		}
		
		if ($dinamic_strlen($this->request->post['just_purchased_notification_speed']) < 1 ) {
			$this->error['speed'] = $this->language->get('error_speed');
			$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_extra'));
		}	

		if ($dinamic_strlen($this->request->post['just_purchased_notification_expire']) < 1 ) {
			$this->error['expire'] = $this->language->get('error_expire');
			$this->error['warning'] = sprintf($this->language->get('error_in_tab'), $this->language->get('tab_extra'));
		}		
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>