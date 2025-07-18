<?php
/**
 * @package         FLEXIcontent
 * @version         3.3
 *
 * @author          Emmanuel Danan, Georgios Papadakis, Yannick Berges, others, see contributor page
 * @link            https://flexicontent.org
 * @copyright       Copyright © 2018, FLEXIcontent team, All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.access.rules');
use Joomla\String\StringHelper;
require_once('flexicontent_basetable.php');

class _flexicontent_types_common extends flexicontent_basetable
{
	/**
	 * Get the parent asset id for the record
	 *
	 * @param   \Joomla\CMS\Table\Table   $table  A \Joomla\CMS\Table\Table object for the asset parent.
	 * @param   integer  $id     The id for the asset
	 *
	 * @return  integer  The id of the asset's parent
	 *
	 * @since   11.1
	 */
	protected function __getAssetParentId(?\Joomla\CMS\Table\Table $table = null, $id = null)
	{
		$asset = \Joomla\CMS\Table\Table::getInstance('Asset');
		$asset->loadByName('com_flexicontent');
		return $asset->id;
	}
}

// This code has not removed to be an example of how to workaround adding TYPE to method parameters of parent class
if (FLEXI_J30GE) {
	class _flexicontent_types extends _flexicontent_types_common {
		protected function _getAssetParentId(?\Joomla\CMS\Table\Table $table = null, $id = null) {
			return parent::__getAssetParentId($table, $id);
		}
	}
}

else {
	class _flexicontent_types extends _flexicontent_types_common {
		protected function _getAssetParentId($table = null, $id = null) {
			return parent::__getAssetParentId($table, $id);
		}
	}
}


class flexicontent_types extends _flexicontent_types
{
	/**
	 * Primary Key
	 * @var int
	 */
	var $id						= null;

	/** @var int */
	var $asset_id 		= null;

	/** @var string */
	var $name					= '';

	/** @var string */
	var $alias				= '';

	/** @var int */
	var $published		= null;

	/** @var int */
	var $itemscreatable	= 0;

	/** @var int */
	var $checked_out	= 0;

	/** @var date */
	var $checked_out_time	= '';

	/** @var int */
	var $access 			= 0;

	/** @var string */
	var $attribs	 		= null;

	/** @var boolean */
	var $_trackAssets	= true;

	// Non-table (private) properties
	var $_record_name = 'type';
	var $_title = 'name';
	var $_alias = 'alias';
	var $_force_ascii_alias = true;
	var $_allow_underscore = true;

	public function __construct(& $db)
	{
		$this->_records_dbtbl  = 'flexicontent_' . $this->_record_name . 's';
		$this->_NAME = strtoupper($this->_record_name);

		parent::__construct('#__' . $this->_records_dbtbl, 'id', $db);
	}


	/**
	 * Method to load a row from the database by primary key and bind the fields to the Table instance properties.
	 *
	 * @param   mixed    $keys   An optional primary key value to load the row by, or an array of fields to match. If not set the instance property value is used.
	 * @param   boolean  $reset  True to reset the default values before loading the new row.
	 *
	 * @return  boolean  True if successful. False if row not found.
	 *
	 * @since   3.3
	 * @throws  \InvalidArgumentException, \RuntimeException, \UnexpectedValueException
	 */
	public function load($keys = null, $reset = true)
	{
		return parent::load($keys, $reset);
	}


	/**
	 * Method to perform sanity checks on the Table instance properties to ensure they are safe to store in the database.
	 *
	 * Child classes should override this method to make sure the data they are storing in the database is safe and as expected before storage.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 *
	 * @since   3.3
	 */
	public function check()
	{
		$config = (object) array('automatic_alias' => true);

		// Check common properties, like title and alias 
		if (parent::_check_record($config) === false)
		{
			return false;
		}

		return true;
	}


	/**
	 * Method to compute the default name of the asset.
	 * The default name is in the form `table_name.id`
	 * where id is the value of the primary key of the table.
	 *
	 * @return	string
	 * @since	1.6
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_flexicontent.' . $this->_record_name . '.' . (int) $this->$k;
	}


	/**
	 * Overloaded bind function.
	 *
	 * @param   array   $array   named array
	 * @param   string  $ignore  An optional array or space separated list of properties
	 *                           to ignore while binding.
	 *
	 * @return  mixed   Null if operation was satisfactory, otherwise returns an error
	 *
	 * @see     \Joomla\CMS\Table\Table:bind
	 * @since   11.1
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['attribs']) && is_array($array['attribs'])) {
			$registry = new \Joomla\Registry\Registry;
			$registry->loadArray($array['attribs']);
			$array['attribs'] = (string)$registry;
		}

		// Bind the rules.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			// (a) prepare the rules, IF for some reason empty group id (=inherit), are not removed from action ACTIONS, we do it manually
			foreach($array['rules'] as $action_name => $identities)
			{
				foreach($identities as $grpid => $val)
				{
					if ($val==="") {
						unset($array['rules'][$action_name][$grpid]);
					}
				}
			}
			
			// (b) Assign the rules
			$rules = new \Joomla\CMS\Access\Rules($array['rules']);
			$this->setRules($rules);
		}
		
		return parent::bind($array, $ignore);
	}
}
