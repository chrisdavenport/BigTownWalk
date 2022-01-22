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
 * Walks View.
 *
 * @since  __DEPLOY_VERSION__
 */
final class BigtownwalkViewWalks extends HtmlView
{
	// Pagination object.
	protected $pagination;

	// Model state object.
	protected $state;

	// JForm object for list filtering.
	public $filterForm;

	// Array of currently active list filters.
	protected $activeFilters;

	// Current user object.
	protected $user;

	// Access permissions for the current user.
	protected $canDo;

	// Array of walk record items.
	protected $walks;

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
		$this->walks		 = $this->get('Walks');
		$this->pagination	 = $this->get('Pagination');
		$this->state		 = $this->get('State');
		$this->filterForm	 = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->user			 = Factory::getUser();
		$this->canDo         = ContentHelper::getActions('com_bigtownwalk');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		parent::display($tpl);
	}
}
