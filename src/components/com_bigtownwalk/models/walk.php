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
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Table\Table;
use Joomla\Utilities\ArrayHelper;

/**
 * Walk model.
 *
 * @since  __DEPLOY_VERSION__
 */
class BigtownwalkModelWalk extends ItemModel
{
	/**
	 * Method to get a Walk object.
	 *
	 * @param   integer  $id  The id of the object to get.
	 *
	 * @return  mixed object on success, false on failure.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getWalk($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id))
			{
				$id = $this->getState('walk.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.state'))
				{
					if ($table->state != $published)
					{
						return $this->_item;
					}
				}

				// Convert the Table to a clean JObject.
				$properties = $table->getProperties(1);
				$this->_item = ArrayHelper::toObject($properties, 'JObject');
			}
			elseif ($error = $table->getError())
			{
				$this->setError($error);
			}
		}

		if (isset($this->_item->created_by))
		{
			$this->_item->created_by_name = Factory::getUser($this->_item->created_by)->name;
		}

		return $this->_item;
	}

	/**
	 * Get table instance.
	 *
	 * @param   string  $type    Table name.
	 * @param   string  $prefix  Table prefix.
	 * @param   array   $config  Configuration variables.
	 *
	 * @return  Table
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTable($type = 'Walk', $prefix = 'BigtownwalkTable', $config = array())
	{
		$this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

		return Table::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 * @since	__DEPLOY_VERSION__
	 */
	protected function populateState()
	{
		$app = Factory::getApplication('com_bigtownwalk');

		// Load state from the request userState on edit or from the passed variable on default.
		if (Factory::getApplication()->input->get('layout') === 'edit')
		{
			$id = Factory::getApplication()->getUserState('com_bigtownwalk.edit.walk.id');
		}
		else
		{
			$id = Factory::getApplication()->input->get('id');
			Factory::getApplication()->setUserState('com_bigtownwalk.edit.walk.id', $id);
		}

		$this->setState('walk.id', $id);

		// Load the parameters.
		$params = $app->getParams();
		$params_array = $params->toArray();

		if (isset($params_array['item_id']))
		{
			$this->setState('walk.id', $params_array['item_id']);
		}

		$this->setState('params', $params);
	}
}
