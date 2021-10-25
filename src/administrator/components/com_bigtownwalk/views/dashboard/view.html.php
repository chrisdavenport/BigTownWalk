<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2021 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\MVC\View\HtmlView;

/**
 * Dashboard View.
 *
 * @since  __DEPLOY_VERSION__
 */
final class BigtownwalkViewDashboard extends HtmlView
{
	// Model state object.
	protected $state;

	// Current user object.
	protected $user;

	// Access permissions for the current user.
	protected $canDo;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 * @since   __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->user	 = Factory::getUser();
		$this->canDo = ContentHelper::getActions('com_bigtownwalk');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		parent::display($tpl);
	}
}
