<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="jpn-button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="jpn-button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content facebook-login-content">
	
	<div id="tabs" class="vtabs">
		<a href="#tab-general"><?php echo $tab_general; ?></a>
		<a href="#tab-message"><?php echo $tab_message; ?></a>
		<a href="#tab-design"><?php echo $tab_design; ?></a>
		<a href="#tab-extra"><?php echo $tab_extra; ?></a>
		<a href="#tab-help"><?php echo $tab_help; ?></a>
	</div>
  
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	  
		<div id="tab-general" class="vtabs-content">
		  <table class="form">
			<tr>
				<td class="left"><span class="required">* </span><?php echo $entry_limit; ?></td>
				<td><input type="text" name="just_purchased_notification_limit" value="<?php echo $just_purchased_notification_limit; ?>">
				<?php if ($error_limit) { ?>
				<span class="error"><?php echo $error_limit; ?></span>
				<?php } ?>
				</td>
			</tr>
			<tr>
				<td class="left"><span class="required">* </span><?php echo $entry_order_status; ?></td>
				<td>
					<div class="scrollbox">
					<?php $class = 'odd'; ?>
					<?php foreach($order_statuses as $order_status) { ?>
					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
						<div class="<?php echo $class;?>">
						<?php   if (in_array($order_status['order_status_id'], $just_purchased_notification_order_status)) { ?>
									<input type="checkbox" name="just_purchased_notification_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" /><?php echo $order_status['name']; ?>
						<?php   } else { ?>
									<input type="checkbox" name="just_purchased_notification_order_status[]" value="<?php echo $order_status['order_status_id']; ?>" /><?php echo $order_status['name']; ?>
						<?php   } ?>
						</div>	
					<?php } ?>
					</div>
					
					<?php if ($error_order_status){  ?>
					<span class="error"><?php echo $error_order_status; ?></span>
					<?php } ?>
				</td>
			</tr>				
			<tr>
				<td class="left"><?php echo $entry_cache; ?></td>
				<td><select name="just_purchased_notification_cache">
				<?php if ($just_purchased_notification_cache) { ?>
				<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_enabled; ?></option>
				<option value="0" selected="selected"><?php echo $text_disabled; ?></option>				
				<?php } ?>
				</select>
				<a id="jpn-clear-cache" class="jpn-button"><?php echo $button_clear_cache; ?></a>
				</td>
			</tr>			
		  </table>
		  
		  <table id="module" class="list">
			<thead>
			  <tr>
				<td class="left"><?php echo $entry_layout; ?></td>
				<td class="left"><?php echo $entry_status; ?></td>
				<td class="right"><?php echo $entry_sort_order; ?></td>
				<td></td>
			  </tr>
			</thead>
			<?php $module_row = 0; ?>
			<?php foreach ($modules as $module) { ?>
			<tbody id="module-row<?php echo $module_row; ?>">
			  <tr>
				<td class="left"><select name="just_purchased_notification_module[<?php echo $module_row; ?>][layout_id]">
					<?php foreach ($layouts as $layout) { ?>
					<?php if ($layout['layout_id'] == $module['layout_id']) { ?>
					<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
					<?php } ?>
					<?php } ?>
				  </select></td>				
				<td class="left" style="display:none;"><select name="just_purchased_notification_module[<?php echo $module_row; ?>][position]">
					<option value="content_bottom" selected="selected"></option>
				  </select></td>
				<td class="left"><select name="just_purchased_notification_module[<?php echo $module_row; ?>][status]">
					<?php if ($module['status']) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select></td>
				<td class="right"><input type="text" name="just_purchased_notification_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
				<td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="jpn-button"><?php echo $button_remove; ?></a></td>
			  </tr>
			</tbody>
			<?php $module_row++; ?>
			<?php } ?>
			<tfoot>
			  <tr>
				<td colspan="3"></td>
				<td class="left"><a onclick="addModule();" class="jpn-button"><?php echo $button_add_module; ?></a></td>
			  </tr>
			</tfoot>
		  </table>
		</div> 		
		
		<div id="tab-message" class="vtabs-content">		
			<div id="languages" class="htabs">
				<?php foreach ($languages as $language) { ?>
				<a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
				<?php } ?>
			</div>
			
			<?php foreach ($languages as $language) { ?>
			<div id="language<?php echo $language['language_id']; ?>">
				<table class="form">
					<tr>
						<td class="left"><span class="required">* </span><?php echo $entry_message; ?></td>
						<td><textarea name="just_purchased_notification_localisation[<?php echo $language['language_id']; ?>][message]" cols="60" rows="4"><?php echo isset($just_purchased_notification_localisation[$language['language_id']]) ? $just_purchased_notification_localisation[$language['language_id']]['message'] : ''; ?></textarea>
						<?php if (isset($error_message[$language['language_id']])) { ?>
						<span class="error"><?php echo $error_message[$language['language_id']]; ?></span>
						<?php } ?>
						</td>
					</tr>
					<tr>
						<td class="left"><span class="required">* </span><?php echo $entry_time_ago_minute; ?></td>
						<td><input name="just_purchased_notification_localisation[<?php echo $language['language_id']; ?>][minute]" size="50" value="<?php echo isset($just_purchased_notification_localisation[$language['language_id']]) ? $just_purchased_notification_localisation[$language['language_id']]['minute'] : ''; ?>" />
						<?php if (isset($error_minute[$language['language_id']])) { ?>
						<span class="error"><?php echo $error_minute[$language['language_id']]; ?></span>
						<?php } ?>
						</td>
					</tr>
					<tr>
						<td class="left"><span class="required">* </span><?php echo $entry_time_ago_hour; ?></td>
						<td><input name="just_purchased_notification_localisation[<?php echo $language['language_id']; ?>][hour]" size="50" value="<?php echo isset($just_purchased_notification_localisation[$language['language_id']]) ? $just_purchased_notification_localisation[$language['language_id']]['hour'] : ''; ?>" />
						<?php if (isset($error_hour[$language['language_id']])) { ?>
						<span class="error"><?php echo $error_hour[$language['language_id']]; ?></span>
						<?php } ?>
						</td>
					</tr>
					<tr>
						<td class="left"><span class="required">* </span><?php echo $entry_time_ago_day; ?></td>
						<td><input name="just_purchased_notification_localisation[<?php echo $language['language_id']; ?>][day]" size="50" value="<?php echo isset($just_purchased_notification_localisation[$language['language_id']]) ? $just_purchased_notification_localisation[$language['language_id']]['day'] : ''; ?>" />
						<?php if (isset($error_day[$language['language_id']])) { ?>
						<span class="error"><?php echo $error_day[$language['language_id']]; ?></span>
						<?php } ?>
						</td>
					</tr>					
				</table>
			</div>
			<?php } ?>	
			
			<table class="form">
				<tr>
					<td class="left"><?php echo $entry_hide_older; ?></td>
					<td><input type="text" name="just_purchased_notification_hide_older" value="<?php echo $just_purchased_notification_hide_older; ?>" size="10" />  <?php echo $text_hours; ?>
					<?php if ($error_hide_older) { ?>
					<span class="error"><?php echo $error_hide_older; ?></span>
					<?php } ?>
					</td>
				</tr>
			</table>
		</div>	
		
		<div id="tab-design" class="vtabs-content">
			<table class="form">
				<tr>
					<td class="left"><span class="required">* </span><?php echo $entry_image; ?></td>
					<td><input type="text" name="just_purchased_notification_image_width" value="<?php echo $just_purchased_notification_image_width; ?>" size="10" /> x 
					<input type="text" name="just_purchased_notification_image_height" value="<?php echo $just_purchased_notification_image_height; ?>" size="10" /> 
					<?php if ($error_image) { ?>
					<span class="error"><?php echo $error_image; ?></span>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td class="left"><span class="required">* </span><?php echo $entry_background_color; ?></td>
					<td><input type="text" name="just_purchased_notification_background_color" value="<?php echo $just_purchased_notification_background_color; ?>" class="choose-color" size="10" />
					<span class="color-sample" style="background-color: <?php echo $just_purchased_notification_background_color; ?>"></span>
					<?php if ($error_background_color) { ?>
					<span class="error"><?php echo $error_background_color; ?></span>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td class="left"><span class="required">* </span><?php echo $entry_border_color; ?></td>
					<td><input type="text" name="just_purchased_notification_border_color" value="<?php echo $just_purchased_notification_border_color; ?>" class="choose-color" size="10" />
					<span class="color-sample" style="background-color: <?php echo $just_purchased_notification_border_color; ?>"></span>
					<?php if ($error_border_color) { ?>
					<span class="error"><?php echo $error_border_color; ?></span>
					<?php } ?>
					</td>
				</tr>	
				<tr>
					<td class="left"><span class="required">* </span><?php echo $entry_text_color; ?></td>
					<td><input type="text" name="just_purchased_notification_text_color" value="<?php echo $just_purchased_notification_text_color; ?>" class="choose-color" size="10" />
					<span class="color-sample" style="background-color: <?php echo $just_purchased_notification_text_color; ?>"></span>
					<?php if ($error_text_color) { ?>
					<span class="error"><?php echo $error_text_color; ?></span>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td class="left"><span class="required">* </span><?php echo $entry_link_color; ?></td>
					<td><input type="text" name="just_purchased_notification_link_color" value="<?php echo $just_purchased_notification_link_color; ?>" class="choose-color" size="10" />
					<span class="color-sample" style="background-color: <?php echo $just_purchased_notification_link_color; ?>"></span>
					<?php if ($error_link_color) { ?>
					<span class="error"><?php echo $error_link_color; ?></span>
					<?php } ?>
					</td>
				</tr>				
			</table>	
		</div>
		
		<div id="tab-extra" class="vtabs-content">
			<table class="form">
				<tr>
					<td class="left"><span class="required">* </span><?php echo $entry_speed; ?></td>
					<td><input type="text" name="just_purchased_notification_speed" value="<?php echo $just_purchased_notification_speed; ?>" size="10" /> 
					<?php if ($error_speed) { ?>
					<span class="error"><?php echo $error_speed; ?></span>
					<?php } ?>
					</td>
				</tr>
				<tr>
					<td class="left"><span class="required">* </span><?php echo $entry_expire; ?></td>
					<td><input type="text" name="just_purchased_notification_expire" value="<?php echo $just_purchased_notification_expire; ?>" size="10" /> 
					<?php if ($error_expire) { ?>
					<span class="error"><?php echo $error_expire; ?></span>
					<?php } ?>
					</td>
				</tr>	
				<tr>
					<td class="left"><?php echo $entry_click; ?></td>
					<td><select name="just_purchased_notification_click">
					<?php if ($just_purchased_notification_click) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>				
					<?php } ?>
					</select></td>
				</tr>	
				<tr>
					<td class="left"><?php echo $entry_shuffle; ?></td>
					<td><select name="just_purchased_notification_shuffle">
					<?php if ($just_purchased_notification_shuffle) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>				
					<?php } ?>
					</select></td>
				</tr>				
			</table>	
		</div>
		
		<div id="tab-help" class="vtabs-content">
			Changelog and HELP you can find  : <a href="http://oc-extensions.com/Just-Purchased-Notification" target="blank">HERE</a><br /><br />
			If you need support email us at <strong>support@oc-extensions.com</strong><br /><br /><br />
			
			<u><strong>Become a Premium Member:</strong></u><br /><br />
			With Premium Membership you will can download all our products (past, present and future) starting with the payment date, until the same day and month, a year later. <br />
			Find more on <a href="http://www.oc-extensions.com">www.oc-extensions.com</a>
		</div>
    </form>
  </div>
</div>

<script type="text/javascript"><!--	
$('#tabs a').tabs();
$('#languages a').tabs();

// -- color picker ---
$('input.choose-color').each(function(){
	
	var color_picker_input = $(this);
	
	color_picker_input.ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val(hex);
			$(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);   
		},
		onChange: function (hsb, hex, rgb) {
			color_picker_input.val('#' + hex);
			recolorElements(color_picker_input);
		}
	})
	.bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});	
});

$('input.choose-color').bind('change', function(){
	recolorElements($(this));
});
// -- stop color picker

// -- start cache button 
$('select[name=\'just_purchased_notification_cache\']').bind('change', function() {
	if ($(this).val() == 1) {
		$('#jpn-clear-cache').show();
	} else {
		$('#jpn-clear-cache').hide();
	}
});

$('select[name=\'just_purchased_notification_cache\']').trigger('change');

$('#jpn-clear-cache').bind('click', function(){
	$.ajax({
		url: 'index.php?route=module/just_purchased_notification/clearcache&token=<?php echo $token; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#jpn-clear-cache').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
		},
		success: function(json){
			$('.success').remove();
			
			$('#jpn-clear-cache').after('<div class="success" style="margin-top: 10px;">' + json['success'] + '</div>');
			
			setTimeout(function() {
					$(".success").hide('blind', {}, 500)
			}, 2000);
		}
	});
});
// -- stop cache button


var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="just_purchased_notification_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';	
	html += '    <td class="left" style="display:none;"><select name="just_purchased_notification_module[' + module_row + '][position]">';
	html += '      <option value="content_bottom"></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="just_purchased_notification_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="just_purchased_notification_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="jpn-button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}

function recolorElements(object) {
	var color = object.val();
	
	object.next().css('background-color', color);
	object.val(color);
}
//--></script>
<?php echo $footer; ?>