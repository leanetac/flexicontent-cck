<?php  // *** DO NOT EDIT THIS FILE, CREATE A COPY !!

/**
 * (Popup) Gallery layout  --  Multibox
 *
 * This layout supports inline_info, pretext, posttext
 */


// ***
// *** Values loop
// ***

$i = -1;
foreach ($values as $n => $value)
{
	// Include common layout code for preparing values, but you may copy here to customize
	$result = include( JPATH_ROOT . '/plugins/flexicontent_fields/image/tmpl_common/prepare_value_display.php' );
	if ($result === _FC_CONTINUE_) continue;
	if ($result === _FC_BREAK_) break;

	$group_str = $group_name ? 'data-rel="['.$group_name.']"' : '';
	$field->{$prop}[] = $pretext.
		'<a style="'.$style.'" href="'.\Joomla\CMS\Uri\Uri::root(true).'/'.$srcl.'" id="mb'.$uniqueid.'" class="fc_image_thumb mb field_' . $field->id . '" '.$group_str.' >
			'.$img_legend.'
		</a>
		<div class="multiBoxDesc mb'.$uniqueid.'">
			' . ($desc ? '<b>' . $title . '</b><br> ' . $desc : $title) . '
		</div>'
		. $inline_info
		. $posttext;
}



// ***
// *** Add per field custom JS
// ***

if ( !isset(static::$js_added[$field->id][__FILE__]) )
{
	flexicontent_html::loadFramework('jmultibox');

	$js = "
	jQuery(document).ready(function() {
		jQuery('a.mb.field_" . $field->id . "').jmultibox({
			initialWidth: 250,  //(number) the width of the box when it first opens while loading the item. Default: 250
			initialHeight: 250, //(number) the width of the box when it first opens while loading the item. Default: 250
			container: document.body, //(element) the element that the box will take it coordinates from. Default: document.body
			contentColor: '#000', //(string) the color of the content area inside the box. Default: #000
			showNumbers: ".(static::$isItemsManager ? 'false' : 'true').",    //(boolean) show the number of the item e.g. 2/10. Default: true
			showControls: ".(static::$isItemsManager ? 'false' : 'true').",   //(boolean) show the navigation controls. Default: true
			descClassName: 'multiBoxDesc',  //(string) the classname of the divs that contain the description for the item. Default: false
			descMinWidth: 400,     //(number) the min width of the description text, useful when the item is small. Default: 400
			descMaxWidth: 600,     //(number) the max width of the description text, so it can wrap to multiple lines instead of being on a long single line. Useful when the item is large. Default: 600
			movieWidth: 576,    //(number) the default width of the box that contains content of a movie type. Default: 576
			movieHeight: 324,   //(number) the default height of the box that contains content of a movie type. Default: 324
			offset: {x: 0, y: 0},  //(object) containing x & y coords that adjust the positioning of the box. Default: {x:0, y:0}
			fixedTop: false,       //(number) gives the box a fixed top position relative to the container. Default: false
			path: '',            //(string) location of the resources files, e.g. flv player, etc. Default: ''
			openFromLink: true,  //(boolean) opens the box relative to the link location. Default: true
			opac:0.7,            //(decimal) overlay opacity Default: 0.7
			useOverlay:false,    //(boolean) use a semi-transparent background. Default: false
			overlaybg:'01.png',  //(string) overlay image in 'overlays' folder. Default: '01.png'
			onOpen:function(){},   //(object) a function to call when the box opens. Default: function(){}
			onClose:function(){},  //(object) a function to call when the box closes. Default: function(){}
			easing:'swing',        //(string) effect of jQuery Default: 'swing'
			useratio:false,        //(boolean) windows size follows ratio. (iframe or Youtube) Default: false
			ratio:'90'             //(number) window ratio Default: '90'
		});
	});";

	if ($js) \Joomla\CMS\Factory::getDocument()->addScriptDeclaration($js);

	static::$js_added[$field->id][__FILE__] = true;
}



/**
 * Include common layout code before finalize values
 */

$result = include( JPATH_ROOT . '/plugins/flexicontent_fields/image/tmpl_common/before_values_finalize.php' );
if ($result !== _FC_RETURN_)
{
	// ***
	// *** Add container HTML (if required by current layout) and add value separator (if supported by current layout), then finally apply open/close tags
	// ***

	// Add value separator
	$field->{$prop} = implode($separatorf, $field->{$prop});

	// Apply open/close tags
	$field->{$prop}  = $opentag . $field->{$prop} . $closetag;
}
