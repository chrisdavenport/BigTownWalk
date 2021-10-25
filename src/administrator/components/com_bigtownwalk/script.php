<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2021 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Installer\Adapter\ComponentAdapter;
use Joomla\CMS\Installer\InstallerScript;

/**
 * Installation class to perform additional changes during install/uninstall/update
 *
 * @since  __DEPLOY_VERSION__
 */
final class Com_BigtownwalkInstallerScript extends InstallerScript
{
	/**
	 * A list of files to be deleted
	 *
	 * @var    array
	 * @since  __DEPLOY_VERSION__
	 */
	protected $deleteFiles = [];

	/**
	 * Function to perform changes during install
	 *
	 * @param   ComponentAdapter  $parent  The class calling this method
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function install($parent)
	{
	}

	/**
	 * Method to run after the install routine.
	 *
	 * @param   string            $type    The action being performed
	 * @param   ComponentAdapter  $parent  The class calling this method
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 * @since   __DEPLOY_VERSION__
	 */
	public function postflight($type, $parent)
	{
		// Delete files that are no longer required.
		$this->removeFiles();
	}
}
