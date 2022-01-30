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
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Utilities\ArrayHelper;

/**
 * Walks model.
 *
 * @since  __DEPLOY_VERSION__
 */
final class BigtownwalkModelWalks extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'state', 'a.state',
				'title', 'a.title',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'modifed', 'a.modified',
				'published',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getListQuery()
	{
		$db = $this->getDbo();

		// Construct base query.
		$query = $db->getQuery(true)
			->select('a.*')
			->from('#__bigtownwalk_walks AS a')

			// Join to view groups.
			->select('vg.title AS access_level')
			->leftJoin('#__viewlevels AS vg ON vg.id = a.access')

			// Join over the users for the checked out user.
			->select('uc.name AS editor')
			->leftJoin('#__users AS uc ON uc.id = a.checked_out');

		// Filter by published state.
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state = 0 OR a.state = 1)');
		}

		// Filter by access level.
		$access = $this->getState('filter.access');

		if (is_numeric($access))
		{
			$query->where('a.access = ' . (int) $access);
		}
		elseif (is_array($access))
		{
			$access = ArrayHelper::toInteger($access);
			$query->where('a.access IN (' . implode(',', $access) . ')');
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(a.title LIKE ' . $search . ' OR a.alias LIKE ' . $search . ')');
			}
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'DESC');
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . serialize($this->getState('filter.access'));

		return parent::getStoreId($id);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$input = Factory::getApplication()->input;

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', '');

		// This covers the case where the access filter is cleared so the access field is not present in the request.
		if ($input->getMethod() === 'POST')
		{
			$access = $input->post->get('access');
		}

		$this->setState('filter.access', $access);

		parent::populateState('a.id', 'DESC');
	}
}
