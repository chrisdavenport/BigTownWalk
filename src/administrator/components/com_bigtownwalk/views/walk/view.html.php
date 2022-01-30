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
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\MVC\View\HtmlView;

/**
 * Walk View.
 *
 * @since  __DEPLOY_VERSION__
 */
final class BigtownwalkViewWalk extends HtmlView
{
	// Form object.
	protected $form;

	// Walk database record object.
	protected $item;

	// Current user object.
	protected $user;

	// Access permissions for the current user.
	protected $canDo;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');
		$this->user  = Factory::getUser();
		$this->canDo = ContentHelper::getActions('com_bigtownwalk');

		// Check for errors and show them as warnings.
		if (count($errors = $this->get('Errors')))
		{
			foreach ($errors as $error)
			{
				Factory::getApplication()->enqueueMessage($error, 'warning');
			}
		}

		return parent::display($tpl);
	}
}
