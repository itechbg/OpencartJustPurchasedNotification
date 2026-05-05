<?php
// Heading
$_['heading_title']          = 'Just Purchased Notification';

// Tab
$_['tab_general']            = 'General';
$_['tab_message']            = 'Message';
$_['tab_design']             = 'Design';
$_['tab_extra']              = 'Extra';
$_['tab_help']               = 'Help';

// Button
$_['button_clear_cache']     = 'Clear Just Purchased Notification Cache';

// Text
$_['text_module']            = 'Modules';
$_['text_success']           = 'Success: You have modified module Just Purchased Notification!';
$_['text_cache_success']     = 'Success: Just Purchased Notification cache was cleared!';
$_['text_hours']             = 'hours';

// Entry
$_['entry_limit']            = 'Limit:<span class="help">show notification about last x products purchased</span>'; 
$_['entry_order_status']     = 'Order Status:<span class="help">products from orders with selected status</span>'; 
$_['entry_shuffle']          = 'Shuffle Notifications:';
$_['entry_cache']            = 'Use cache:';

$_['entry_layout']           = 'Layout:';
$_['entry_status']           = 'Status:';
$_['entry_sort_order']       = 'Sort Order:';

$_['entry_message']          = 'Notification message:<span class="help"><br /><strong>Keywords:</strong><br />{country} = customer country<br />{zone} = customer zone/state<br />{city} = customer city<br />{quantity} = product quantity <br />{product_with_link} = product name with link</span>';
$_['entry_time_ago']         = 'Show "Time ago":';
$_['entry_time_ago_older']   = 'Hide old "Time ago" (hours):<span class="help">hide "time ago" text if order is older than x hours</span>';
$_['entry_time_ago_minute']  = 'Minutes ago:<span class="help">Keyword: <strong>{time_counter}</strong><br />Ex: {time_counter} minutes ago</span>';
$_['entry_time_ago_hour']    = 'Hours ago:<span class="help">Keyword: <strong>{time_counter}</strong><br />Ex: {time_counter} hours ago</span>';
$_['entry_time_ago_day']     = 'Days ago:<span class="help">Keyword: <strong>{time_counter}</strong><br />Ex: {time_counter} days ago</span>';
$_['entry_hide_older']       = 'Hide "Time ago" text:<span class="help">IF is older than <strong>X HOURS</strong></span>';

$_['entry_background_color'] = 'Background Color:<span class="help">default color: #EEEEEE</span>';
$_['entry_border_color']     = 'Border Color:<span class="help">default color: #CCCCCC</span>';
$_['entry_text_color']       = 'Text Color:<span class="help">default color: #333333</span>';
$_['entry_link_color']       = 'Link Color:<span class="help">default color: #40a1c9</span>';
$_['entry_image']            = 'Image Dimension (W x H):';
     
$_['entry_speed']            = 'Delay between (seconds):<span class="help">time between 2 notifications</span>';
$_['entry_expire']           = 'Display time (seconds):<span class="help">after how many seconds active notification is hidden</span>';
$_['entry_click']            = 'Extend click<span class="help">Enabled = redirect to product page on click anywhere in notification area<br /><br />Disabled = redirect to product page only if is clicked product image or product name (in notification area) </span>';

// Error
$_['error_permission']       = 'Warning: You do not have permission to modify module Just Purchased Notification!';
$_['error_in_tab']           = 'Please check again. Found error in tab %s!';

$_['error_limit']            = 'Limit is required!';
$_['error_order_status']     = 'Please choose at least one order status!';
$_['error_message']          = 'Notification message format is required!';
$_['error_minute']           = 'Time ago - minutes is required!';
$_['error_hour']             = 'Time ago - hours is required!';
$_['error_day']              = 'Time ago - days is required!';
$_['error_hide_older']       = 'Incorrect value. Good Value example: 24, 30 etc!';
$_['error_image']            = 'Image width &amp; height dimensions required!';
$_['error_color']            = 'Color value - 7 chars required. Ex: #E7C4AB';
$_['error_speed']            = 'Delay between notifications is required';
$_['error_expire']           = 'Display time is required';
?>