<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2022 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;

/**
 * Walk table class.
 *
 * @since  __DEPLOY_VERSION__
 */
final class BigtownwalkTableWalk extends Table
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  Database connector object.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__bigtownwalk_walks', 'id', $db);

		$this->setColumnAlias('published', 'state');
	}

	/**
	 * Method to delete a row from the database table by primary key value.
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function delete($pk = null)
	{
		if (!$delete = parent::delete($pk))
		{
			return false;
		}

		return true;
	}

	/**
	 * Overrides Table::store.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function store($updateNulls = false)
	{
		$date = Factory::getDate();

		// New walk?
		if ($this->id == 0)
		{
			$this->created = $date->toSql();
		}

		// Update last modified date.
		$this->modified = $date->toSql();

		return parent::store($updateNulls);
	}
}
