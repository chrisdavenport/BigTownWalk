<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2022 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

/**
 * Methods supporting a list of Walks records.
 *
 * @since  __DEPLOY_VERSION__
 */
class BigtownwalkModelWalks extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      __DEPLOY_VERSION__
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = [
				'id', 'a.id',
				'ordering', 'a.ordering',
				'state', 'a.state',
			];
		}

		parent::__construct($config);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return    JDatabaseQuery
	 *
	 * @since    __DEPLOY_VERSION__
	 */
	protected function getListQuery()
	{
		// Select the required fields from the table.
		$query = $this->getDbo()->getQuery(true)
			->select('DISTINCT a.*')
			->from('`#__bigtownwalk_walks` AS a')
			->where('a.state = 1')
			->order('ordering ASC');

		return $query;
	}
}
