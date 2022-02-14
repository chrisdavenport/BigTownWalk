<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2022 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Table\Table;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;

/**
 * Walk model.
 *
 * @since  __DEPLOY_VERSION__
 */
final class BigtownwalkModelWalk extends AdminModel
{
	/**
	 * Generate a new alias if required.
	 *
	 * @param   string  $alias  Current alias.
	 * @param   string  $title  Source string from which the alias will be generated.
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function generateAlias(string $alias, string $title): array
	{
		$newAlias = $alias;
		$newTitle = $title;

		// Instantiate the table.
		$table = $this->getTable();
		$aliasField = $table->getColumnAlias('alias');
		$titleField = $table->getColumnAlias('title');

		// If there is no current alias, generate a fresh one.
		if (empty($alias))
		{
			$newAlias = ApplicationHelper::stringURLSafe($newTitle);
		}

		// Check that the alias is not already in use.
		// If we can load a record with the same alias then we have a duplicate.
		if ($table->load([$aliasField => $newAlias]))
		{
			Factory::getApplication()->enqueueMessage(Text::_('COM_BIGTOWNWALK_SAVE_WARNING'), 'warning');
		}

		// Look for the next available alias with a suffix number.
		while ($table->load([$aliasField => $newAlias]))
		{
			if ($newTitle === $table->$titleField)
			{
				$newTitle = StringHelper::increment($newTitle);
			}

			$newAlias = StringHelper::increment($newAlias, 'dash');
		}

		return [$newAlias, $newTitle];
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A Form object on success, false on failure
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getForm($data = array(), $loadData = true)
	{
		return $this->loadForm('com_bigtownwalk.walk', 'walk', array('control' => 'jform', 'load_data' => $loadData));
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  CMSObject|boolean  Object on success, false on failure.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		if (property_exists($item, 'attribs'))
		{
			$item->attribs = new Registry($item->attribs);
		}

		return $item;
	}

	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  Table  A Table object
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTable($name = '', $prefix = 'Table', $options = array()): Table
	{
		return Table::getInstance('Walk', 'BigtownwalkTable', $options);
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @throws  Exception
	 * @since   __DEPLOY_VERSION__
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_bigtownwalk.edit.walk.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_bigtownwalk.walk', $data);

		if (!is_object($data))
		{
			return $data;
		}

		// Get default access level for new walks from Global Configuration.
		if ((int) $data->access == 0)
		{
			$data->access = (int) Factory::getConfig()->get('access');
		}

		return $data;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   Table  $table  Table object.
	 *
	 * @return  void
	 *
	 * @since	__DEPLOY_VERSION__
	 */
	protected function prepareTable($table)
	{
		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			if (@$table->ordering === '')
			{
				$db = Factory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__bigtownwalk_walks');
				$max = $db->loadResult();
				$table->ordering = $max + 1;
			}
		}
	}

	/**
	 * Method to save the walk data.
	 *
	 * @param   array  $data  The walk data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws  Exception
	 */
	public function save($data)
	{
		// If saving as copy, increment the number in the walk title.
		if (Factory::getApplication()->input->getCmd('task') === 'save2copy')
		{
			if (isset($data['title']))
			{
				$data['title'] = StringHelper::increment($data['title']);
			}
		}

		// Auto-complete the alias if not explicitly entered.
		if (empty($data['alias']))
		{
			[$data['alias'], $data['title']] = $this->generateAlias($data['alias'], $data['title']);
		}

		// If the Start Publishing date is empty, then set it to now.
		if ($data['state'] === 1 && (int) $data['publish_up'] === 0)
		{
			$data['publish_up'] = Factory::getDate()->toSql();
		}

		// If the Finish Publishing date is empty, then set it to the default date.
		if ($data['state'] === 1 && (int) $data['publish_down'] === 0)
		{
			$data['publish_down'] = $this->getDbo()->getNullDate();
		}

		// Check that the finish publishing date is after the start publishing date.
		if ($data['publish_down'] < $data['publish_up'] && $data['publish_down'] > $this->getDbo()->getNullDate())
		{
			// Swap the dates.
			$temp = $data['publish_up'];
			$data['publish_up'] = $data['publish_down'];
			$data['publish_down'] = $temp;
		}

		return parent::save($data);
	}
}
