<?php  // *** DO NOT EDIT THIS FILE, DO NOT CREATE A COPY !! -- Galleriffic JS will be removed / replaced

/**
 * (Inline) Gallery layout  --  Galleriffic
 *
 * This layout does not support inline_info, pretext, posttext
 */

if ($is_ingroup)
{
	$field->{$prop}[] = 'Usage of this gallery inside field-group not possible, outer container can not be added';

	return;
}


// ***
// *** Values loop
// ***

$uid = 'es_'.$field_name_js."_fcitem".$item->id;
$i   = -1;

// Gallery options

$auto_start          = (int) $field->parameters->get( $PPFX_ . 'auto_start', 1 );
$transition_duration = (int) $field->parameters->get( $PPFX_ . 'transition_duration', 600 );
$slideshow_delay     = (int) $field->parameters->get( $PPFX_ . 'slideshow_delay', 2500 );
$preload_image       = (int) $field->parameters->get( $PPFX_ . 'preload_image', 10 );
$enable_popup        = (int) $field->parameters->get( $PPFX_ . 'enable_popup', 1 );

$thumb_display       = (int) $field->parameters->get( $PPFX_ . 'thumb-display', 1 ); //0:none, 1:images, 2:dots //TODO check dots and none
$thumb_position      = (int) $field->parameters->get( $PPFX_ . 'thumb-position', 1 ); //0:top, 1:bottom
$thumb_height        = (int) $field->parameters->get( $PPFX_ . 'thumb_height', 86 );
$slide_height        = (int) $field->parameters->get( $PPFX_ . 'slide_height', 600 );//TODO check solution for responsive height

$use_pages           = (int) $field->parameters->get( $PPFX_ . 'use_pages', 0 );
$number_thumbs       = !$use_pages ? 9999 : (int) $field->parameters->get( $PPFX_ . 'number_thumbs', 5 );
$number_pages        = (int) $field->parameters->get( $PPFX_ . 'number_pages', 10 );

$enable_top_pager    = (int) $field->parameters->get( $PPFX_ . 'enable_top_pager', 0 );
$enable_bottom_pager = (int) $field->parameters->get( $PPFX_ . 'enable_bottom_pager', 0 );

$display_playbtn = (int) $field->parameters->get( $PPFX_ . 'display_playbtn', 1 );

$display_title = (int) $field->parameters->get( 'showtitle', 0 );
$display_desc = (int) $field->parameters->get( 'showdesc', 0 );

$over_image_btns     = 1;

$thumb_container_height = $thumb_height + (!$use_pages ? 32 : 0) + ($use_pages && $enable_top_pager  === 0 ? 40 : 0) + ($use_pages && $enable_bottom_pager === 1 ? 40 : 0);

foreach ($values as $n => $value)
{
	// Include common layout code for preparing values, but you may copy here to customize
	$result = include( JPATH_ROOT . '/plugins/flexicontent_fields/image/tmpl_common/prepare_value_display.php' );
	if ($result === _FC_CONTINUE_) continue;
	if ($result === _FC_BREAK_) break;


	$size_w_s = isset($value['size_w_s']) ? $value['size_w_s'] : 0;
	$size_h_s = isset($value['size_h_s']) ? $value['size_h_s'] : 0;
	$size_w_m = isset($value['size_w_m']) ? $value['size_w_m'] : 0;
	$size_h_m = isset($value['size_h_m']) ? $value['size_h_m'] : 0;
	$size_w_l = isset($value['size_w_l']) ? $value['size_w_l'] : 0;
	$size_h_l = isset($value['size_h_l']) ? $value['size_h_l'] : 0;

	$group_str = $group_name ? 'data-fancybox="' . $group_name . '"' : '';

	$field->{$prop}[] =
		'<a class="thumb" name="drop" href="'.\Joomla\CMS\Uri\Uri::root(true).'/'.$srcl.'" data-width="' . $size_w_l . '" data-height="' . $size_h_l . '" title="' . $title_encoded . '">
			'.$img_legend.'
		</a>
		<a class="gf_fancybox" href="'.\Joomla\CMS\Uri\Uri::root(true).'/'.$srcl.'" data-title="' . $title_encoded . '" data-caption="' . $desc_encoded . '"' . $group_str . '
			onclick="if (gf_gallery_' . $uid . '.mSlider.isDragging) {event.preventDefault(); event.stopPropagation(); return false; }"; style="display: none;">
			<div id="gf_caption_' . $uid . '" class="caption-container">
		' . ($display_title || $display_desc ? '
				<div class="caption">
					' . ($display_title && $title ? '<div class="image-title">' . $title_encoded .'</div>' : '') . '
					' . ($display_desc && $desc ?  '<div class="image-desc">' . nl2br(preg_replace("/(\r\n|\r|\n){3,}/", "\n\n", $desc_encoded)) . '</div>' : '') . '
				</div>' : '') .'
			</div>	
		</a>
';
}
$js = "
var gf_gallery_" . $uid . ";

(function($) {
$(document).ready(function()
{
	var use_pages = " . $use_pages . ";

	// Opacity for non-active, non-hovered thumbnails.
	// Active or Hovered thumbnails have opacity: 1
	var onMouseOutOpacity = 0.8;

	var sliderBox  = $('#gf_thumbs_" . $uid . "');

	sliderBox.find('.gf_fancybox').fancybox({
		'openEffect'	: 'elastic',
		'closeEffect'	: 'elastic',
		'openEasing'  : 'easeOutCubic',
		'closeEasing' : 'easeInCubic',
		'idleTime'    : 0,
		afterLoad: function(data)
		{
			gf_gallery_" . $uid . ".gotoIndex(data.currIndex, false, true);
		}
	});

	// Initialize Advanced Galleriffic Gallery
	var gallery = sliderBox.galleriffic({
		unique_id:                 '".$uid."',
		use_pages:                 ".($use_pages ? 'true' : 'false').",
		delay:                     ".$slideshow_delay.",	
		numThumbs:                 ".$number_thumbs.",
		preloadAhead:              ".$preload_image.",
		enableTopPager:            ".($enable_top_pager === 2 ? 2 : ($enable_top_pager ? 'true' : 'false')).",
		enableBottomPager:         ".($enable_bottom_pager ? 'true' : 'false').",
		maxPagesToShow:            ".$number_pages.",
		imageContainerSel:         '#gf_slideshow_" . $uid . "',
		ssControlsContainerSel:    ".($display_playbtn ? '"#gf_sscontrols_' . $uid.'"'  : '""').",
		navControlsContainerSel:   '#gf_navcontrols_" . $uid . "',
		captionContainerSel:       '#gf_caption_" . $uid . "',
		loadingContainerSel:       '#gf_loading_" . $uid . "',
		playLinkText:              '". '<span class="icon-play-circle" style="color: white"></span> ' . \Joomla\CMS\Language\Text::_('FLEXI_FIELD_IMAGE_PLAY_SLIDESHOW') ."',
		pauseLinkText:             '". '<span class="icon-pause-circle" style="color: white"></span> ' . \Joomla\CMS\Language\Text::_('FLEXI_FIELD_IMAGE_PAUSE_SLIDESHOW') ."',
		prevLinkText:              '". ($over_image_btns ? '&lt;' : \Joomla\CMS\Language\Text::_('FLEXI_FIELD_IMAGE_PREV_LINK')) ."',
		nextLinkText:              '". ($over_image_btns ? '&gt;' : \Joomla\CMS\Language\Text::_('FLEXI_FIELD_IMAGE_NEXT_LINK')) ."',
		prevPageLinkText:          '". ($enable_top_pager === 2 ? '&lt;' : \Joomla\CMS\Language\Text::_('FLEXI_FIELD_IMAGE_PREV_PAGE_LINK')) ."',
		nextPageLinkText:          '". ($enable_top_pager === 2 ? '&gt;' : \Joomla\CMS\Language\Text::_('FLEXI_FIELD_IMAGE_NEXT_PAGE_LINK')) ."',
		enableHistory:             false,
		enableFancybox:            ".($enable_popup ? 'true' : 'false').",
		fancyOptions:              {},
		enableKeyboardNavigation:  true,
		autoStart:                 ".($auto_start ? 'true' : 'false').",
		syncTransitions:           true,

		defaultTransitionDuration: ".$transition_duration."
	});

	gf_gallery_" . $uid . " = gallery;

	gf_gallery_" . $uid . ".find('ul.thumbs').on('mouseenter', function(ev)
	{
		$('body').addClass('gf_clear_tips');
	});
	gf_gallery_" . $uid . ".find('ul.thumbs').on('mouseleave', function(ev)
	{
		$('body').removeClass('gf_clear_tips');
	});
});
})(jQuery);
";

if ($js) \Joomla\CMS\Factory::getDocument()->addScriptDeclaration($js);


/**
 * Add per field custom JS
 */

if ( !isset(static::$js_added[$field->id][__FILE__]) )
{
	flexicontent_html::loadFramework('galleriffic');

	// Load Fancybox if needed
	if ( $enable_popup )
	{
		flexicontent_html::loadFramework('fancybox');
	}

	static::$js_added[$field->id][__FILE__] = array();
}

	// calcul pagination display


	if	($thumb_display == 0){//TODO check to remove thumbs
		$thumb_display ='<div id="gf_thumbs_' . $uid . '" class="navigation' . (!$use_pages ? ' no_pagination' : '') . '" style="display: none;">
		<ul class="thumbs noscript" style="display: none;">
			<li>
			'. implode("</li><li>", $field->{$prop}) .'
			</li>
		</ul>
	</div>';
	} elseif ($thumb_display == 1) {
		$thumb_display ='<div id="gf_thumbs_' . $uid . '" class="navigation' . (!$use_pages ? ' no_pagination' : '') . '" style="display: none;">
		<ul class="thumbs noscript">
			<li>
			'. implode("</li><li>", $field->{$prop}) .'
			</li>
		</ul>
	</div>
		';
}else{
	$field->{$prop} = preg_replace('/<img[^>]+\>/i', '', $field->{$prop});//TODO remove img tag and all inside juste need a link
	$thumb_display ='<div id="gf_thumbs_' . $uid . '" class="navigation' . (!$use_pages ? ' no_pagination' : '') . '" style="display: none;">
		<ul class="thumbs noscript dot">
			<li>
			'. implode("</li><li>", $field->{$prop}) .'
			</li>
		</ul>
	</div>';
	}

	if ($thumb_position == 0){
		$navigation_top = $thumb_display;
		$navigation_bottom = '';
	}else {
		$navigation_top = '';
		$navigation_bottom = $thumb_display;
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

	

	// Add container HTML
	$field->{$prop} = '

	<style>
		div#gf_thumbs_' . $uid . '{' . ($use_pages ? 'min-height: ' : 'height: ') . $thumb_container_height . 'px; }
		div#gf_thumbs_' . $uid . ' ul.thumbs li a {
			height:' . $thumb_height . 'px !important;
			max-width:' . $thumb_height . 'px !important;
		}
		div#gf_thumbs_' . $uid . ' ul.thumbs li a img {
			width:' . $thumb_height . 'px !important;
		}
		div#gf_container_' . $uid . ' div.slideshow-container .image-wrapper a,
		div#gf_container_' . $uid . ' div.slideshow-container {
			height: ' . $slide_height . 'px;
		}
	</style>

	<div id="gf_container_' . $uid . '" class="gf_container">

			'. $navigation_top .'

		<div class="gf_controls_box" ' . ($over_image_btns ? 'style="height: 0;"' : '') . '>
			<div id="gf_sscontrols_' . $uid . '" class="controls ss-controls-box"></div>
			' . (!$over_image_btns ? '
			<div id="gf_navcontrols_' . $uid . '" class="controls nav-controls-box"></div>
			' : '') . '
		</div>

		<div id="gf_content_' . $uid . '" class="content">
			<div class="slideshow-container">
				' . ($over_image_btns ? '
				<div id="gf_navcontrols_' . $uid . '" class="controls nav-controls-box"></div>
				' : '') . '
				<div id="gf_loading_' . $uid . '" class="loader"></div>
				<div id="gf_slideshow_' . $uid . '" class="slideshow"></div>
			</div>
		</div>

		'. $navigation_bottom .'

	</div>
	<div class="fcclear"></div>
	';

	// Apply open/close tags
	$field->{$prop}  = $opentag . $field->{$prop} . $closetag;
}
