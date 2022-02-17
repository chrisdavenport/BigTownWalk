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

/**
 * Helper for mod_bigtownwalks
 *
 * @since  __DEPLOY_VERSION__
 */
class ModBigtownwalksHelper
{
	/**
	 * Get walks.
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getWalks(): array
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from('#__bigtownwalk_walks')
			->where('state = 1')
			->order('ordering ASC');

		return (array) $db->setQuery($query)->loadObjectList();
	}
}
