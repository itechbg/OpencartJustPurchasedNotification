<?php
namespace Opencart\Admin\Model\Extension\JustPurchasedNotification\Module;

class JustPurchasedNotification extends \Opencart\System\Engine\Model {
    public function getDefaults(): array {
        return [
            'module_just_purchased_notification_status' => 1,
            'module_just_purchased_notification_auto_inject' => 1,
            'module_just_purchased_notification_limit' => 15,
            'module_just_purchased_notification_order_status' => [5],
            'module_just_purchased_notification_shuffle' => 1,
            'module_just_purchased_notification_cache' => 1,
            'module_just_purchased_notification_cache_ttl' => 180,
            'module_just_purchased_notification_message_template' => 'Someone from {city} purchased {quantity} x {product_with_link}',
            'module_just_purchased_notification_time_minute_template' => 'about {time_counter} minutes ago',
            'module_just_purchased_notification_time_hour_template' => 'about {time_counter} hours ago',
            'module_just_purchased_notification_time_day_template' => 'about {time_counter} days ago',
            'module_just_purchased_notification_show_time_ago' => 1,
            'module_just_purchased_notification_hide_time_ago_after' => '',
            'module_just_purchased_notification_image_width' => 64,
            'module_just_purchased_notification_image_height' => 64,
            'module_just_purchased_notification_background_color' => '#F6F6F2',
            'module_just_purchased_notification_border_color' => '#D7D5CF',
            'module_just_purchased_notification_text_color' => '#2F2D2A',
            'module_just_purchased_notification_link_color' => '#9C3D18',
            'module_just_purchased_notification_delay' => 7000,
            'module_just_purchased_notification_display_time' => 4500,
            'module_just_purchased_notification_start_delay' => 2000,
            'module_just_purchased_notification_click_anywhere' => 0,
            'module_just_purchased_notification_position' => 'left_bottom',
            'module_just_purchased_notification_motion' => 'slide',
            'module_just_purchased_notification_bottom_offset' => 20,
            'module_just_purchased_notification_side_offset' => 20,
            'module_just_purchased_notification_exclude_product_ids' => '',
            'module_just_purchased_notification_debug' => 0
        ];
    }

    public function clearCache(): void {
        $this->cache->delete('just_purchased_notification.');
    }
}
