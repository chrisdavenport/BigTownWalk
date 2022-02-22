<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2022 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View class for a list of Walks.
 *
 * @since  __DEPLOY_VERSION__
 */
final class BigtownwalkViewWalks extends BigtownwalkView
{
	// Array of walks.
	protected $walks;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @throws  Exception, RuntimeException
	 * @since   __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		$this->state  = $this->get('State');
		$this->walks  = $this->get('Items');
		$this->params = $this->getParams();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->prepareDocument();

		return parent::display($tpl);
	}
}
