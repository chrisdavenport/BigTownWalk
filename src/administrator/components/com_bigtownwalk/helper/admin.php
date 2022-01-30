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
use Joomla\CMS\Object\CMSObject;

/**
 * Admin helper.
 *
 * @since  __DEPLOY_VERSION__
 */
class BigtownwalkHelperAdmin
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  View name.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function addSubmenu(string $vName = '')
	{
		JHtmlSidebar::addEntry(
			'Dashboard',
			'index.php?option=com_bigtownwalk&view=dashboard',
			$vName === 'dashboard'
		);

		JHtmlSidebar::addEntry(
			'Walks',
			'index.php?option=com_bigtownwalk&view=walks',
			$vName === 'walks'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return  CMSObject
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getActions()
	{
		$user = Factory::getUser();
		$result = new CMSObject;

		$actions = [
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		];

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, 'com_bigtownwalk'));
		}

		return $result;
	}
}
