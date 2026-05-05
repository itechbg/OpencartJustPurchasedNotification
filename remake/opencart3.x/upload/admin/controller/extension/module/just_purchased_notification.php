<?php
class ControllerExtensionModuleJustPurchasedNotification extends Controller {
    private $error = array();

    public function install() {
        $this->load->model('setting/event');
        $this->load->model('extension/module/just_purchased_notification');
        $this->load->model('setting/setting');

        $defaults = $this->model_extension_module_just_purchased_notification->getDefaults();

        foreach ($defaults as $key => $value) {
            if ($this->config->get($key) === null) {
                $this->model_setting_setting->editSettingValue('module_just_purchased_notification', $key, $value);
            }
        }

        $this->model_setting_event->deleteEventByCode('just_purchased_notification_footer_inject');
        $this->model_setting_event->addEvent(
            'just_purchased_notification_footer_inject',
            'catalog/view/common/footer/after',
            'extension/module/just_purchased_notification/injectInFooter'
        );
    }

    public function uninstall() {
        $this->load->model('setting/event');
        $this->model_setting_event->deleteEventByCode('just_purchased_notification_footer_inject');
    }

    public function index() {
        $data = $this->load->language('extension/module/just_purchased_notification');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $this->load->model('extension/module/just_purchased_notification');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_just_purchased_notification', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        $data['breadcrumbs'] = array(
            array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
            ),
            array(
                'text' => $this->language->get('text_extension'),
                'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
            ),
            array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/just_purchased_notification', 'user_token=' . $this->session->data['user_token'], true)
            )
        );

        $data['action'] = $this->url->link('extension/module/just_purchased_notification', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        $data['clear_cache'] = $this->url->link('extension/module/just_purchased_notification/clearCache', 'user_token=' . $this->session->data['user_token'], true);
        $data['user_token'] = $this->session->data['user_token'];

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $defaults = $this->model_extension_module_just_purchased_notification->getDefaults();

        foreach ($defaults as $key => $value) {
            if (isset($this->request->post[$key])) {
                $data[$key] = $this->request->post[$key];
            } elseif ($this->config->get($key) !== null) {
                $data[$key] = $this->config->get($key);
            } else {
                $data[$key] = $value;
            }
        }

        $data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

        $error_fields = array('limit', 'order_status', 'message_template', 'image', 'color', 'timing');

        foreach ($error_fields as $field) {
            $data['error_' . $field] = isset($this->error[$field]) ? $this->error[$field] : '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/just_purchased_notification', $data));
    }

    public function clearCache() {
        $this->load->language('extension/module/just_purchased_notification');
        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/module/just_purchased_notification')) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('extension/module/just_purchased_notification');
            $this->model_extension_module_just_purchased_notification->clearCache();
            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/just_purchased_notification')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((int)$this->request->post['module_just_purchased_notification_limit'] < 1) {
            $this->error['limit'] = $this->language->get('error_limit');
        }

        if (empty($this->request->post['module_just_purchased_notification_order_status'])) {
            $this->error['order_status'] = $this->language->get('error_order_status');
        }

        if (!trim($this->request->post['module_just_purchased_notification_message_template'])) {
            $this->error['message_template'] = $this->language->get('error_message_template');
        }

        if ((int)$this->request->post['module_just_purchased_notification_image_width'] < 1 || (int)$this->request->post['module_just_purchased_notification_image_height'] < 1) {
            $this->error['image'] = $this->language->get('error_image');
        }

        $color_fields = array(
            'module_just_purchased_notification_background_color',
            'module_just_purchased_notification_border_color',
            'module_just_purchased_notification_text_color',
            'module_just_purchased_notification_link_color'
        );

        foreach ($color_fields as $field) {
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $this->request->post[$field])) {
                $this->error['color'] = $this->language->get('error_color');
                break;
            }
        }

        if ((int)$this->request->post['module_just_purchased_notification_delay'] < 100 || (int)$this->request->post['module_just_purchased_notification_display_time'] < 100) {
            $this->error['timing'] = $this->language->get('error_timing');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }
}
