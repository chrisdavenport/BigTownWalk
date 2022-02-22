<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2022 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\Registry\Registry;

/**
 * Bigtownwalk Base View.
 *
 * @since  __DEPLOY_VERSION__
 */
abstract class BigtownwalkView extends HtmlView
{
	// Current logged in user object.
	protected $user;

	// Model state.
	protected $state;

	/**
	 * Menu item parameters.
	 *
	 * @var    Registry
	 * @since  __DEPLOY_VERSION__
	 */
	protected $params;

	/**
	 * Determine the page title.
	 *
	 * @return  string
	 *
	 * @throws  Exception
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getPageTitle()
	{
		$app = Factory::getApplication();

		$title = $this->params->get('page_title', $this->params->get('page_heading', ''));

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ((int) $app->get('sitename_pagetitles', 0) === 1)
		{
			$title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ((int) $app->get('sitename_pagetitles', 0) === 2)
		{
			$title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		return $title;
	}

	/**
	 * Get parameters.
	 *
	 * @return  Registry
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	protected function getParams(): Registry
	{
		$menuParameters = $this->get('State')->get('parameters.menu');

		return new Registry(
			array_merge(
				ComponentHelper::getParams('com_menus')->toArray(),
				$menuParameters === null ? [] : $menuParameters->toArray(),
				ComponentHelper::getParams('com_bigtownwalk')->toArray()
			)
		);
	}

	/**
	 * Prepares the document.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 * @since   __DEPLOY_VERSION__
	 */
	protected function prepareDocument()
	{
		// Set the page heading.
		$this->setPageHeading();

		// Determine the page title.
		$title = $this->getPageTitle();

		// Set the page title.
		if ($title)
		{
			$this->document->setTitle($title);
		}

		// Set the meta description.
		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		// Set the meta keywords.
		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		// Set the robots directive.
		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}

	/**
	 * Set page heading.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 * @since   __DEPLOY_VERSION__
	 */
	protected function setPageHeading()
	{
		// Get the active menu.
		$menu  = Factory::getApplication()->getMenu()->getActive();

		// Because the application sets a default page title we need to get it from the menu item itself.
		if ($menu)
		{
			if (empty($this->params->get('page_heading')))
			{
				$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
			}
		}
		else
		{
			$this->params->def('page_heading', Text::_('COM_BIGTOWNWALK_DEFAULT_PAGE_TITLE'));
		}
	}

	/**
	 * Set browser page title.
	 *
	 * @param   string  $title  New page title.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 * @since   __DEPLOY_VERSION__
	 */
	protected function setTitle(string $title = '')
	{
		Factory::getApplication()->getDocument()->setTitle($title);
	}
}
