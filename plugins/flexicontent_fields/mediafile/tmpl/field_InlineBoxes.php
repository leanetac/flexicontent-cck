<?php
$FT = 'FILE';
$PRV_TYPE='-2';
$image_placeholder = 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';

$allowdownloads   = false;
$compactWaveform  = true;
$wf_zoom_slider   = $field->parameters->get('wf_zoom_slider', 1);
$wf_load_progress = $field->parameters->get('wf_load_progress', 1);

$per_value_js = "";
$n = 0;

foreach ($field->value as $file_id)
{
	$fieldname_n = $fieldname.'['.$n.']';
	$elementid_n = $elementid.'_'.$n;
	$FN_n        = $field_name_js.'_'.$n;

	$file_data         = $files_data[$file_id];
	$filename_original = $file_data->filename_original ? $file_data->filename_original : $file_data->filename;

	$ext = strtolower(flexicontent_upload::getExt($file_data->filename));
	$file_data->waveform_preview = !$file_data->filename ? '' : 'audio_preview/' . str_ireplace('.'.$ext, '', basename($file_data->filename)) . '.mp3';
	$file_data->waveform_peaks   = !$file_data->filename ? '' : 'audio_preview/' . str_ireplace('.'.$ext, '', basename($file_data->filename)) . '.json';

	$preview_css = 'width:100px; height:100px;';

	if (!in_array(strtolower($file_data->ext), $imageexts))
	{
		$preview_src = $image_placeholder;

		// form_file_preview (2): Automatic, so if not an image hide the preview elements
		if ($form_file_preview === 2 && $fields_box_placing)
		{
			$preview_css .= 'display:none;';
		}
		$preview_text = mb_strtoupper($file_data->ext);
		$has_preview = false;
	}
	else
	{
		switch((int) $file_data->url)
		{
			case 0:
				$img_path = \Joomla\CMS\Uri\Uri::root(true) . '/' . (empty($file_data->secure) ? $mediapath : $docspath) . '/' . $file_data->filename;
				break;
			case 1:
				$img_path = $file_data->filename;
				break;
			case 2:
			default:
				$img_path = \Joomla\CMS\Uri\Uri::root(true) . '/' . $file_data->filename;
				break;
		}
		$preview_src = \Joomla\CMS\Uri\Uri::root() . 'components/com_flexicontent/librairies/phpthumb/phpThumb.php?src=' . $img_path . '&amp;w=100&amp;h=100&amp;zc=1&amp;q=95&amp;ar=x';
		$preview_text = '';
		$has_preview = true;
	}

	$info_txt_classes = $file_data->published ? '' : 'file_unpublished '.$tooltip_class;
	$info_txt_tooltip = $file_data->published ? '' : 'title="'.flexicontent_html::getToolTip('FLEXI_FILE_FIELD_FILE_UNPUBLISHED', 'FLEXI_FILE_FIELD_FILE_UNPUBLISHED_DESC', 1, 1).'"';

	$_select_file_lbl = '
			<label class="' . $add_on_class . ' fc-lbl inlinefile-data-lbl '.$tooltip_class.'" title="'.flexicontent_html::getToolTip('FLEXI_FIELD_'.$FT.'_ABOUT_SELECTED_FILE', 'FLEXI_FIELD_'.$FT.'_ABOUT_SELECTED_FILE_DESC', 1, 1).'" id="'.$elementid_n.'_file-data-lbl" for="'.$elementid_n.'_file-data-txt">
				' . \Joomla\CMS\Language\Text::_('FLEXI_FIELD_'.$FT.'_SELECT_FILE') . '
			</label>
	';

	// Get from session for new values
	$default_secure_val = isset($form_data[$file_id])  ?  (int)$form_data[$file_id]['secure']  :  1;

	// Get from session for new values
	$default_stamp_val = isset($form_data[$file_id])  ?  (int)$form_data[$file_id]['stamp']  :  1;

	$addExistingURL_onclick = "fc_openFileSelection_".$field->id."(this);";
	$toggleUploader_onclick = 'var box = jQuery(this).closest(\'.fcfieldval_container\'); ' .
		'var isVisible = box.find(\'.fc_file_uploader\').is(\':visible\'); ' .
		'isVisible ? jQuery(this).removeClass(\'active\') : jQuery(this).addClass(\'active\'); ' .
		'isVisible ? box.find(\'.fcfield_preview_box\').show() : box.find(\'.fcfield_preview_box\').hide(); ' .
		'isVisible ? box.find(\'.inlinefile-prv-box\').addClass(\'empty\') : box.find(\'.inlinefile-prv-box\').removeClass(\'empty\');';

	if ($use_inline_uploaders)
	{
		$uploader_html = $uploader_html_arr[$n] = \Joomla\CMS\HTML\HTMLHelper::_('fcuploader.getUploader', $field, $u_item_id, null, $n,
			array(
			'container_class' => ($multiple ? 'fc_inline_uploader fc_uploader_thumbs_view fc-box' : '') . ' fc_compact_uploader fc_auto_uploader thumb_'.$thumb_size_default,
			'upload_maxcount' => 1,
			'autostart_on_select' => true,
			'refresh_on_complete' => false,
			'thumb_size_default' => $thumb_size_default,
			'toggle_btn' => array(
				'class' => ($file_btns_position ? $btn_item_class : '') . ' fcfield-uploadvalue dropdown-item' . $font_icon_class,
				'text' => (!$file_btns_position ? '&nbsp; ' . \Joomla\CMS\Language\Text::_('FLEXI_UPLOAD') : ''),
				'onclick' => $toggleUploader_onclick,
				'action' => null
			),
			'thumb_size_slider_cfg' => ($thumb_size_resizer ? $thumb_size_slider_cfg : 0),
			'resize_cfg' => ($thumb_size_resizer ? $resize_cfg : 0),
			'handle_FileFiltered' => 'fcfield_FileFiltered_'.$field->id,
			'handle_FileUploaded' => 'fcfield_FileUploaded_'.$field->id
			)
		);

		$multi_icon = $form_font_icons ? ' <span class="icon-stack"></span>' : '<span class="pages_stack"></span>';
		$btn_classes = 'fc-files-modal-link ' . ($file_btns_position ? $btn_item_class : '') . ' ' . $font_icon_class;
		$uploader_html->multiUploadBtn = '';  /*'
			<span data-href="'.$addExistingURL.'" onclick="'.$addExistingURL_onclick.'" class="'.$btn_classes.' fc-up fcfield-uploadvalue multi dropdown-item" id="'.$elementid_n.'_mul_uploadvalue">
				&nbsp; ' . $multi_icon . ' ' . (!$file_btns_position || $file_btns_position==2 ? \Joomla\CMS\Language\Text::_('FLEXI_UPLOAD') : '') . '
			</span>';*/
		$uploader_html->myFilesBtn = '
			<span data-href="'.$addExistingURL.'" onclick="'.$addExistingURL_onclick.'" class="'.$btn_classes.' fc-sel fcfield-selectvalue multi dropdown-item" data-rowno="'.$n.'" id="'.$elementid_n.'_selectvalue">
				' .  ($file_btns_position ? $multi_icon : '') . ' ' . (!$file_btns_position || $file_btns_position==2 ? '&nbsp; ' . \Joomla\CMS\Language\Text::_('FLEXI_MY_FILES') : '') . ' ' . (!$file_btns_position ? $multi_icon : '') .'
			</span>';
		$uploader_html->mediaUrlBtn = !$usemediaurl ? '' : '
			<span class="' . ($file_btns_position ? $btn_item_class : '') . ' fcfield-medialurlvalue ' . $font_icon_class . ' dropdown-item" onclick="fcfield_mediafile.toggleMediaURL(\''.$elementid_n.'\', \''.$field_name_js.'\'); return false;">
				' . (!$file_btns_position || $file_btns_position==2 ? '&nbsp; ' . \Joomla\CMS\Language\Text::_('FLEXI_FIELD_MEDIA_URL') : '') . '
			</span>';
		$uploader_html->clearBtn = '
			 <span class="' . $btn_item_class . ' fcfield-clearvalue ' . $font_icon_class . '" title="'.\Joomla\CMS\Language\Text::_('FLEXI_CLEAR').'" onclick="fcfield_mediafile.clearField(this, {}, \''.$field_name_js.'\');">
			</span>';
	}

	$drop_btn_class =
		(FLEXI_J40GE
			? 'btn btn-sm toolbar dropdown-toggle dropdown-toggle-split'
			: 'btn btn-small toolbar dropdown-toggle'
		);

	$fnn = $FN_n;   // Alias ...

	$field->html[] = '

		<span class="fc_filedata_storage_name" style="display:none;">'.$file_data->filename.'</span>
		<div class="fc_filedata_txt_nowrap nowrap_hidden">'.$file_data->filename.'<br/>'.$file_data->altname.'</div>
		<input class="fc_filedata_txt inlinefile-data-txt '. $info_txt_classes . $required_class .'"
			readonly="readonly" name="'.$fieldname_n.'[file-data-txt]" id="'.$elementid_n.'_file-data-txt" '.$info_txt_tooltip.'
			value="'.htmlspecialchars($filename_original, ENT_COMPAT, 'UTF-8').'"
			data-label_text="'.$field->label.'"
			data-filename="'.htmlspecialchars($file_data->filename, ENT_COMPAT, 'UTF-8').'"
			data-wfpreview="'.htmlspecialchars($file_data->waveform_preview, ENT_COMPAT, 'UTF-8').'"
			data-wfpeaks="'.htmlspecialchars($file_data->waveform_peaks, ENT_COMPAT, 'UTF-8').'"
		/>
		<input type="hidden" id="'.$elementid_n.'_file-id" name="'.$fieldname_n.'[file-id]" value="'.htmlspecialchars($file_id, ENT_COMPAT, 'UTF-8').'" class="fc_fileid" />'.'

		'.( (!$multiple || $use_ingroup) && !$required_class ? '
		<div class="fcclear"></div>
		<fieldset class="group-fcset">
			<input type="checkbox" id="'.$elementid_n.'_file-del" class="inlinefile-del" name="'.$fieldname_n.'[file-del]" value="1" onchange="file_fcfield_del_existing_value'.$field->id.'(this);" />
			<label class="label inlinefile-del-lbl '.$tooltip_class.'" title="'.flexicontent_html::getToolTip('FLEXI_FIELD_'.$FT.'_ABOUT_REMOVE_FILE', 'FLEXI_FIELD_'.$FT.'_ABOUT_REMOVE_FILE_DESC', 1, 1).'" id="'.$elementid_n.'_file-del-lbl" for="'.$elementid_n.'_file-del" >
				'.\Joomla\CMS\Language\Text::_( 'FLEXI_FIELD_FILE_DELETE_FROM_SERVER_STORAGE' ).'
			</label>
		</fieldset>
		<div class="fcclear"></div>

		' : ( (!$multiple || $use_ingroup) && $required_class && $file_data->filename ? '
			<div class="fcclear"></div>
			<div class="alert alert-info fc-small fc-iblock" style="margin: 8px 0;">'.\Joomla\CMS\Language\Text::_('FLEXI_FIELD_'.$FT.'_REQUIRED_UPLOAD_NEW_TO_REPLACE').'</div>
			<div class="fcclear"></div>
		' : '')).'

		'.(!$iform_title ? '
		<div class="fcclear"></div>
		<div class="'.$input_grp_class.' fc-xpended-row">
			<label class="' . $add_on_class . ' fc-lbl fc_filedata_title-lbl">'.\Joomla\CMS\Language\Text::_( 'FLEXI_FILE_DISPLAY_TITLE' ).'</label>
			<span class="' . $add_on_class . ' fc_filedata_title">'. ($file_data->altname && $filename_original!=$file_data->altname ? $file_data->altname : '-').'</span>
		</div>' : '').'

		<div class="fcclear"></div>

		<div class="fc_uploader_n_props_box">

			<div class="inlinefile-prv-box" style="flex-basis: auto;">
				'.($form_file_preview ? '<div class="fcfield_preview_box' . ($form_file_preview === 2 ? ' auto' : '') . '" style="'.$preview_css.'">
					<div class="fc_preview_text">' . $preview_text . '</div>
					<img id="'.$elementid_n.'_image_preview" src="'.$preview_src.'" class="fc_preview_thumb" alt="Preview image placeholder"/></div>' : '').'
				'.(!empty($uploader_html) ? $uploader_html->container : '').'
			</div>


			<div class="inlinefile-data-box" style="flex-basis: auto; flex-shrink: 50;">
				'.

			( $iform_title ? '
				<div class="'.$input_grp_class.' fc-xpended-row inlinefile-title-box">
					<label class="' . $add_on_class . ' fc-lbl '.$tooltip_class.'" title="'.flexicontent_html::getToolTip('FLEXI_FILE_DISPLAY_TITLE', 'FLEXI_FILE_DISPLAY_TITLE_DESC', 1, 1).'" id="'.$elementid_n.'_file-title-lbl" for="'.$elementid_n.'_file-title">
						'.\Joomla\CMS\Language\Text::_( 'FLEXI_FILE_DISPLAY_TITLE' ).'
					</label>
					<input type="text" id="'.$elementid_n.'_file-title" size="44" name="'.$fieldname_n.'[file-title]" value="'.htmlspecialchars(!isset($form_data[$file_id]) ? $file_data->altname : $form_data[$file_id]['file-title'], ENT_COMPAT, 'UTF-8').'" class="fc_filetitle '.$required_class.' fcfield_textval" />
				</div>' : '').

			( $iform_lang ? '
				<div class="'.$input_grp_class.' fc-xpended-row inlinefile-lang-box">
					<label class="' . $add_on_class . ' fc-lbl '.$tooltip_class.'" title="'.flexicontent_html::getToolTip('FLEXI_LANGUAGE', 'FLEXI_FILE_LANGUAGE_DESC', 1, 1).'" id="'.$elementid_n.'_file-lang-lbl" for="'.$elementid_n.'_file-lang">
						'.\Joomla\CMS\Language\Text::_( 'FLEXI_LANGUAGE' ).'
					</label>
					'.flexicontent_html::buildlanguageslist($fieldname_n.'[file-lang]', 'class="fc_filelang use_select2_lib"', (!isset($form_data[$file_id]) ? $file_data->language : $form_data[$file_id]['file-lang']), 1).'
				</div>' : '').

			( $iform_access ? '
				<div class="'.$input_grp_class.' fc-xpended-row inlinefile-access-box">
					<label class="' . $add_on_class . ' fc-lbl '.$tooltip_class.'" title="'.flexicontent_html::getToolTip('FLEXI_ACCESS', 'FLEXI_FILE_ACCESS_DESC', 1, 1).'" id="'.$elementid_n.'_file-access-lbl" for="'.$elementid_n.'_file-access">
						'.\Joomla\CMS\Language\Text::_( 'FLEXI_ACCESS' ).'
					</label>
					'.\Joomla\CMS\HTML\HTMLHelper::_('access.assetgrouplist', $fieldname_n.'[file-access]', (!isset($form_data[$file_id]) ? $file_data->access : $form_data[$file_id]['file-access']), $attribs=' class="fc_fileaccess use_select2_lib" ', $config=array(/*'title' => \Joomla\CMS\Language\Text::_('FLEXI_SELECT'), */'id' => $elementid_n.'_file-access')).'
				</div>' : '').

			( $iform_desc ? '
				<div class="'.$input_grp_class.' fc-xpended-row inlinefile-desc-box">
					<label class="' . $add_on_class . ' fc-lbl inlinefile-desc-lbl '.$tooltip_class.'" title="'.flexicontent_html::getToolTip('FLEXI_DESCRIPTION', 'FLEXI_FILE_DESCRIPTION_DESC', 1, 1).'" id="'.$elementid_n.'_file-desc-lbl" for="'.$elementid_n.'_file-desc">
						'.\Joomla\CMS\Language\Text::_( 'FLEXI_DESCRIPTION' ).'
					</label>
					<textarea id="'.$elementid_n.'_file-desc" cols="24" rows="3" name="'.$fieldname_n.'[file-desc]" class="fc_filedesc fcfield_textareaval">'.(!isset($form_data[$file_id]) ? $file_data->description : $form_data[$file_id]['file-desc']).'</textarea>
				</div>' : '').

			( $iform_dir ? '
				<div class="'.$input_grp_class.' fc-xpended-row inlinefile-secure-box">
					<label class="' . $add_on_class . ' fc-lbl inlinefile-secure-lbl '.$tooltip_class.'" data-placement="top" title="'.flexicontent_html::getToolTip('FLEXI_URL_SECURE', 'FLEXI_URL_SECURE_DESC', 1, 1).'" id="'.$elementid_n.'_secure-lbl">
						'.\Joomla\CMS\Language\Text::_( 'FLEXI_URL_SECURE' ).'
					</label>
					'.($has_values ? '
						<span class="add-on fcinfo" style="font-style: italic; min-width:64px;">'.\Joomla\CMS\Language\Text::_($file_data->secure ?  'FLEXI_YES' : 'FLEXI_NO').'</span>
					' : '
						'.flexicontent_html::buildradiochecklist( array(1=> \Joomla\CMS\Language\Text::_( 'FLEXI_YES' ), 0=> \Joomla\CMS\Language\Text::_( 'FLEXI_NO' )) , $fieldname_n.'[secure]', (!isset($form_data[$file_id]) ? $file_data->secure : (int)$form_data[$file_id]['secure']), 1, ' class="fc_filedir" ', $elementid_n.'_secure').'

					').'
				</div>' : '').

			( $iform_stamp ? '
				<div class="'.$input_grp_class.' fc-xpended-row inlinefile-stamp-box">
					<label class="' . $add_on_class . ' fc-lbl inlinefile-stamp-lbl '.$tooltip_class.'" data-placement="top" title="'.flexicontent_html::getToolTip('FLEXI_DOWNLOAD_STAMPING', 'FLEXI_FILE_DOWNLOAD_STAMPING_DESC', 1, 1).'" id="'.$elementid_n.'_stamp-lbl">
						'.\Joomla\CMS\Language\Text::_( 'FLEXI_DOWNLOAD_STAMPING' ).'
					</label>
					'.flexicontent_html::buildradiochecklist( array(1=> \Joomla\CMS\Language\Text::_( 'FLEXI_YES' ), 0=> \Joomla\CMS\Language\Text::_( 'FLEXI_NO' )) , $fieldname_n.'[stamp]', (!isset($form_data[$file_id]) ? $file_data->stamp : (int)$form_data[$file_id]['stamp']), 1, ' class="fc_filestamp" ', $elementid_n.'_stamp').'
				</div>' : '').
			'

			</div>
		</div>

		<div class="fcclear"></div>

		<div class="fc_mediafile_player_box' . ($compactWaveform ? ' fc_compact' : '') . '">

			<div class="fc_mediafile_controls_outer">

				<!--div id="fc_mediafile_current_time_' . $fnn . '" class="media_time">00:00:00</div-->
				<div id="fc_mediafile_controls_' . $fnn . '" class="fc_mediafile_controls">
					<a href="javascript:;" class="btn playBtn">
						<span class="icon-play-circle controls"></span><span class="btnControlsText">' . \Joomla\CMS\Language\Text::_('FLEXI_FIELD_MEDIAFILE_PLAY') . '</span>
					</a>
					<a href="javascript:;" class="btn pauseBtn" style="display: none;">
						<span class="icon-pause-circle controls"></span><span class="btnControlsText">' . \Joomla\CMS\Language\Text::_('FLEXI_FIELD_MEDIAFILE_PAUSE') . '</span>
					</a>
					<a href="javascript:;" class="btn stopBtn" style="display: none;">
						<span class="icon-stop-circle controls"></span><span class="btnControlsText">' . \Joomla\CMS\Language\Text::_('FLEXI_FIELD_MEDIAFILE_STOP') . '</span>
					</a>
					<a href="javascript:;" class="btn loadBtn" style="display: none;">
						<span class="icon-loop controls"></span><span class="btnControlsText">' . \Joomla\CMS\Language\Text::_('FLEXI_FIELD_MEDIAFILE_LOAD') . '</span>
					</a>
					' . ($allowdownloads ? $_download_btn_html : '') . '
					' . (!$wf_zoom_slider ? '' : '
					<div class="fc_mediafile_wf_zoom_box">
						- <input id="fc_mediafile_slider_' . $fnn. '" type="range" min="0.5" max="200" value="0.5" class="fc_mediafile_wf_zoom" /> +
					</div>
					') . '
				</div>

			</div>

			<div class="fc_mediafile_audio_spectrum_box_outer" >

				<div id="fc_mediafile_audio_spectrum_box_' . $fnn . '" class="fc_mediafile_audio_spectrum_box"
					data-fc_tagid="' . $field->name . '_' . $n . '"
					data-fc_fname="' .$field_name_js . '"
				>
					' . (!$wf_load_progress ? '' : '
					<div class="fc_mediafile_audio_spectrum_progressbar">
						<div class="barText"></div>
						<div class="bar" style="width: 100%;"></div>
					</div>
					') . '
					<div id="fc_mediafile_audio_spectrum_' . $fnn . '" class="fc_mediafile_audio_spectrum"></div>
				</div>

			</div>

		</div>
		';


	/*if ($filename_original)
	{
		$per_value_js .= "
			fcfield_mediafile.initValue('" . $field->name . '_' . $n . "', '".$field_name_js."');
		";
	}*/

	if (!$filename_original)
	{
		$per_value_js .= "
			fcfield_mediafile.showUploader('" . $field->name . '_' . $n . "', '".$field_name_js."');
		";
	}

	$n++;
	if (!$multiple) break;  // multiple values disabled, break out of the loop, not adding further values even if the exist
}


//document.addEventListener('DOMContentLoaded', function()
$js = ""
."
	fcfield_mediafile_base_url['".$field_name_js."'] = '".$base_url."';
"
. (!$per_value_js ? "" : "
	jQuery(document).ready(function()
	{
		" . $per_value_js . "
	});
");

if ($js) $document->addScriptDeclaration($js);

