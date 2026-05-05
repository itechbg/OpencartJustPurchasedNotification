<?php
namespace Opencart\Admin\Controller\Extension\JustPurchasedNotification\Module;

class JustPurchasedNotification extends \Opencart\System\Engine\Controller {
    private array $error = [];

    public function install(): void {
        $this->load->model('setting/event');
        $this->load->model('setting/setting');
        $this->load->model('extension/just_purchased_notification/module/just_purchased_notification');

        $defaults = $this->model_extension_just_purchased_notification_module_just_purchased_notification->getDefaults();

        foreach ($defaults as $key => $value) {
            if ($this->config->get($key) === null) {
                $this->model_setting_setting->editSettingValue('module_just_purchased_notification', $key, $value);
            }
        }

        $this->deleteEventCompat('just_purchased_notification_footer_inject');
        $this->addEventCompat(
            'just_purchased_notification_footer_inject',
            'Just Purchased Notification footer inject',
            'catalog/view/common/footer/after',
            'extension/just_purchased_notification/module/just_purchased_notification.injectInFooter',
            true,
            0
        );
    }

    public function uninstall(): void {
        $this->load->model('setting/event');
        $this->deleteEventCompat('just_purchased_notification_footer_inject');
    }

    public function index(): void {
        $data = $this->load->language('extension/just_purchased_notification/module/just_purchased_notification');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $this->load->model('extension/just_purchased_notification/module/just_purchased_notification');

        if ($this->request->server['REQUEST_METHOD'] === 'POST' && $this->validate()) {
            $this->model_setting_setting->editSetting('module_just_purchased_notification', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module'));
            return;
        }

        $data['breadcrumbs'] = [
            [
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
            ],
            [
                'text' => $this->language->get('text_extension'),
                'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
            ],
            [
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/just_purchased_notification/module/just_purchased_notification', 'user_token=' . $this->session->data['user_token'])
            ]
        ];

        $data['save'] = $this->url->link('extension/just_purchased_notification/module/just_purchased_notification.save', 'user_token=' . $this->session->data['user_token']);
        $data['action'] = $this->url->link('extension/just_purchased_notification/module/just_purchased_notification', 'user_token=' . $this->session->data['user_token']);
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');
        $data['clear_cache'] = $this->url->link('extension/just_purchased_notification/module/just_purchased_notification.clearCache', 'user_token=' . $this->session->data['user_token']);
        $data['user_token'] = $this->session->data['user_token'];

        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $defaults = $this->model_extension_just_purchased_notification_module_just_purchased_notification->getDefaults();

        foreach ($defaults as $key => $value) {
            if (isset($this->request->post[$key])) {
                $data[$key] = $this->request->post[$key];
            } elseif ($this->config->get($key) !== null) {
                $data[$key] = $this->config->get($key);
            } else {
                $data[$key] = $value;
            }
        }

        $data['error_warning'] = $this->error['warning'] ?? '';

        $error_fields = ['limit', 'order_status', 'message_template', 'image', 'color', 'timing'];

        foreach ($error_fields as $field) {
            $data['error_' . $field] = $this->error[$field] ?? '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/just_purchased_notification/module/just_purchased_notification', $data));
    }

    public function clearCache(): void {
        $this->load->language('extension/just_purchased_notification/module/just_purchased_notification');

        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/just_purchased_notification/module/just_purchased_notification')) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('extension/just_purchased_notification/module/just_purchased_notification');
            $this->model_extension_just_purchased_notification_module_just_purchased_notification->clearCache();
            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate(): bool {
        if (!$this->user->hasPermission('modify', 'extension/just_purchased_notification/module/just_purchased_notification')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((int)$this->request->post['module_just_purchased_notification_limit'] < 1) {
            $this->error['limit'] = $this->language->get('error_limit');
        }

        if (empty($this->request->post['module_just_purchased_notification_order_status'])) {
            $this->error['order_status'] = $this->language->get('error_order_status');
        }

        if (!trim((string)$this->request->post['module_just_purchased_notification_message_template'])) {
            $this->error['message_template'] = $this->language->get('error_message_template');
        }

        if ((int)$this->request->post['module_just_purchased_notification_image_width'] < 1 || (int)$this->request->post['module_just_purchased_notification_image_height'] < 1) {
            $this->error['image'] = $this->language->get('error_image');
        }

        $color_fields = [
            'module_just_purchased_notification_background_color',
            'module_just_purchased_notification_border_color',
            'module_just_purchased_notification_text_color',
            'module_just_purchased_notification_link_color'
        ];

        foreach ($color_fields as $field) {
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', (string)$this->request->post[$field])) {
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

    private function addEventCompat(string $code, string $description, string $trigger, string $action, bool $status, int $sort_order): void {
        $method = new \ReflectionMethod($this->model_setting_event, 'addEvent');
        $count = $method->getNumberOfParameters();

        if ($count >= 6) {
            $this->model_setting_event->addEvent($code, $description, $trigger, $action, $status, $sort_order);
            return;
        }

        if ($count === 3) {
            $this->model_setting_event->addEvent($code, $trigger, str_replace('.', '/', $action));
            return;
        }

        $this->model_setting_event->addEvent([
            'code' => $code,
            'description' => $description,
            'trigger' => $trigger,
            'action' => $action,
            'status' => $status,
            'sort_order' => $sort_order
        ]);
    }

    private function deleteEventCompat(string $code): void {
        if (method_exists($this->model_setting_event, 'deleteEventByCode')) {
            $this->model_setting_event->deleteEventByCode($code);
            return;
        }

        $this->model_setting_event->deleteEvent($code);
    }
}
