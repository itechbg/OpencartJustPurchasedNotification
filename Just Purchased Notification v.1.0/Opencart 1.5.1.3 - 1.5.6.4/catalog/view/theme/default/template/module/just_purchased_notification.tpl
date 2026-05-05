<?php if ($notifications) { ?>
<style>
.ui-notify-message-style {
	background: <?php echo $background_color;?> !important;
	border: 1px solid <?php echo $border_color; ?> !important;
}

.jpn-message, .jpn-time-ago {
	color: <?php echo $text_color; ?> !important;
}

.jpn-message a{
	color: <?php echo $link_color; ?> !important;
}
</style>

<div id="just-purchased-notification">
	<div id="just-purchased-notification-queue">
		<div class="jpn-image-area"><a href="#{product_href}"><img src="#{image}" /></a></div>
		<div class="jpn-message-area">
			<div class="jpn-message">#{message}</div>
			<div class="jpn-time-ago">#{time_ago}</div>
		</div>
	</div>
</div>

<script type="text/javascript"><!--
$(document).ready(function() {
var jpn_container = $("#just-purchased-notification").notify();

<?php foreach($notifications as $notification) { ?>
jpn_container.notify("create", "just-purchased-notification-queue", { 
	product_href: '<?php echo addslashes($notification['product_href']); ?>',
	image: '<?php echo addslashes($notification['image']); ?>',
	message: '<?php echo addslashes($notification['message']); ?>',
	<?php if ($notification['show_time_ago']) { ?>
	time_ago: '<?php echo addslashes($notification['time_ago']); ?>'	
	<?php } else { ?>
	time_ago: ''	
	<?php } ?>
}, { 
	speed: <?php echo $speed; ?>,
	expires: <?php echo $expire; ?>,
	queue: 1,
	<?php if ($click) { ?>
	click: function() {
		location = '<?php echo addslashes($notification['product_href']); ?>'.replace(/&amp;/g, '&');
	}
	<?php } ?>
});
<?php } ?>
});
--></script>
<?php } ?>