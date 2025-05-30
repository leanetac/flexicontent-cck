<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

/** tooltip in front */
$cparams = \Joomla\CMS\Component\ComponentHelper::getParams( 'com_flexicontent' );
// Load tooltips JS
if ($cparams->get('add_tooltips', 1)) \Joomla\CMS\HTML\HTMLHelper::_('bootstrap.popover', '.hasTooltip', array('trigger' => 'click hover'));

$page_classes  = '';
$page_classes .= $this->pageclass_sfx ? ' page'.$this->pageclass_sfx : '';
$page_classes .= ' fccategory fccat'.$this->category->id;
$menu = \Joomla\CMS\Factory::getApplication()->getMenu()->getActive();
if ($menu) $page_classes .= ' menuitem'.$menu->id; 

?>
<div id="flexicontent" class="flexicontent <?php echo $page_classes; ?>" >

<!-- BOF buttons -->
<?php if (\Joomla\CMS\Factory::getApplication()->input->getInt('print', 0)) : ?>

	<?php if ($this->params->get('print_behaviour', 'auto') == 'auto') : ?>
		<script>jQuery(document).ready(function(){ window.print(); });</script>
	<?php	elseif ($this->params->get('print_behaviour') == 'button') : ?>
		<input type='button' id='printBtn' name='printBtn' value='<?php echo \Joomla\CMS\Language\Text::_('Print');?>' class='btn btn-info' onclick='this.style.display="none"; window.print(); return false;'>
	<?php endif; ?>

<?php else : ?>
	<?php
	$_add_btn   = flexicontent_html::addbutton( $this->params, $this->category );
	$_print_btn = flexicontent_html::printbutton( $this->print_link, $this->params );
	$_mail_btn  = flexicontent_html::mailbutton( 'category', $this->params, $this->category->slug );
	$_csv_btn   = flexicontent_html::csvbutton( 'category', $this->params, $this->category->slug );
	$_feed_btn  = flexicontent_html::feedbutton( 'category', $this->params, $this->category->slug );
	?>

	<?php if ( $_add_btn || $_print_btn || $_mail_btn || $_csv_btn || $_feed_btn ) : ?>
	
		<?php if ($this->params->get('btn_grp_dropdown')) : ?>
		
		<div class="buttons btn-group">
		  <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
		    <span class="<?php echo $this->params->get('btn_grp_dropdown_class', 'icon-options'); ?>"></span>
		  </button>
		  <ul class="dropdown-menu" role="menu">
		    <?php echo $_add_btn   ? '<li>'.$_add_btn.'</li>' : ''; ?>
		    <?php echo $_print_btn ? '<li>'.$_print_btn.'</li>' : ''; ?>
		    <?php echo $_mail_btn  ? '<li>'.$_mail_btn.'</li>' : ''; ?>
		    <?php echo $_csv_btn  ? '<li>'.$_csv_btn.'</li>' : ''; ?>
		    <?php echo $_feed_btn  ? '<li>'.$_feed_btn.'</li>' : ''; ?>
		  </ul>
		</div>
		
	  <?php else : ?>
	  
		<div class="buttons">
	    <?php echo $_add_btn; ?>
	    <?php echo $_print_btn; ?>
	    <?php echo $_mail_btn; ?>
	    <?php echo $_csv_btn; ?>
	    <?php echo $_feed_btn; ?>
		</div>
		
		<?php endif; ?>
		
	<?php endif; ?>
	
<?php endif; ?>
<!-- EOF buttons -->

<!-- BOF page title -->
<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<header class="">
		<h1 class="componentheading">
			<?php echo $this->params->get( 'page_heading' ) ?>
		</h1>
<?php endif; ?>
<!-- EOF page title -->

<!-- BOF author description -->
<?php if (@$this->authordescr_item_html) echo $this->authordescr_item_html; ?>
<!-- EOF author description -->

<?php if ($this->params->get('show_page_heading', 1)) echo '</header>'; ?>

<?php if ($this->category->id || (count($this->categories) && $this->params->get('show_subcategories'))) echo '<section class="">'; ?>

<!-- BOF category info -->
<?php if ( $this->category->id > 0) : /* Category specific data may not be not available, e.g. for -author- layout view */ ?>
		<?php
		// Only show this part if some category info is to be printed
		if (
			$this->params->get('show_cat_title', 1) ||
			($this->params->get('show_description_image', 1) && $this->category->image) ||
			($this->params->get('show_description', 1) && $this->category->description)
		) :
			echo $this->loadTemplate('category_html5');
		endif;
		?>
<?php endif; ?>
<!-- EOF category info -->
	
<!-- BOF sub-categories info -->
<?php 
	// Only show this part if subcategories are available
	if ( count($this->categories) && $this->params->get('show_subcategories') ) :
		echo file_exists(dirname(__FILE__).DS.'category_subcategories_html5.php') ?  $this->loadTemplate('subcategories_html5') : 'FILE MISSING: category_subcategories_html5.php <br/>';
	endif;
?>
<!-- EOF sub-categories info -->
	
<!-- BOF peer-categories info -->
<?php 
	// Only show this part if subcategories are available
	if ( count($this->peercats) && $this->params->get('show_peercategories') ) :
		echo file_exists(dirname(__FILE__).DS.'category_peercategories_html5.php') ?  $this->loadTemplate('peercategories_html5') : 'FILE MISSING: category_peercategories_html5.php <br/>';
	endif;
?>
<!-- EOF peer-categories info -->


<?php if ($this->category->id || (count($this->categories) && $this->params->get('show_subcategories')))  echo '</section>'; ?>

<!-- BOF item list display -->
<?php
	echo $this->loadTemplate('items_html5');
	echo empty($this->items) && $this->getModel()->getState('limit') ? '<span class="fc_return_msg">'.\Joomla\CMS\Language\Text::sprintf('FLEXI_CLICK_HERE_TO_RETURN', '"JavaScript:window.history.back();"').'</span>' : "";
?>
<!-- BOF item list display -->

<!-- BOF pagination -->
<?php
/**
 * Body of form for (a) Text search, Field Filters, Alpha-Index, Items Total Statistics, Selectors(e.g. per page, orderby)
 * If customizing via CSS rules or JS scripts is not enough, then please copy the following file here to customize the HTML too
 *
 * First try current folder, otherwise load from common folder
 */
	file_exists('pagination.php')
		? include('pagination.php')
		: include(JPATH_SITE.DS.'components'.DS.'com_flexicontent'.DS.'tmpl_common'.DS.'pagination.php');
?>
<!-- EOF pagination -->

</div>

