<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2022 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\Rules\RulesInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Rule to process URLs without a menu item
 *
 * @since  __DEPLOY_VERSION__
 */
class BigtownwalkNomenurules implements RulesInterface
{
	/**
	 * Router this rule belongs to
	 *
	 * @var RouterView
	 * @since __DEPLOY_VERSION__
	 */
	protected $router;

	/**
	 * Class constructor.
	 *
	 * @param   RouterView  $router  Router this rule belongs to
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(RouterView $router)
	{
		$this->router = $router;
	}

	/**
	 * Dummymethod to fullfill the interface requirements
	 *
	 * @param   array  &$query  The query array to process
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 * @codeCoverageIgnore
	 */
	public function preprocess(&$query)
	{
		// Get the language-specific default memu item.
		$default = $this->router->menu->getDefault(Factory::getLanguage()->getTag());

		// If the query includes an Itemid which matches the language-specific default menu item, then remove it.
		if (isset($default->id) && isset($query['Itemid']) && $default->id == $query['Itemid'])
		{
			unset($query['option']);
			unset($query['Itemid']);
		}
	}

	/**
	 * Parse a menu-less URL.
	 *
	 * @param   array  &$segments  The URL segments to parse
	 * @param   array  &$vars      The vars that result from the segments
	 *
	 * @return  void
	 *
	 * @throws  InvalidArgumentException
	 * @since   __DEPLOY_VERSION__
	 */
	public function parse(&$segments, &$vars)
	{
		if (isset($vars['view']) && $vars['view'] === 'walks')
		{
			throw new InvalidArgumentException(Text::_('COM_BIGTOWNWALK_ERROR_BAD_URL'), 404);
		}

		// If there is an active menu item, just return.
		if (is_object($this->router->menu->getActive()))
		{
			return;
		}

		// Get the registered views.
		$views = $this->router->getViews();

		// If the view requested is not one of our registered views, just return.
		if (!isset($views[$segments[0]]))
		{
			return;
		}

		// The first segment will be the view name.
		$viewName = array_shift($segments);
		$vars['view'] = $viewName;

		// The next segment should be the key, but if there isn't one, just return.
		if (!isset($views[$viewName]->key) || !isset($segments[0]))
		{
			return;
		}

		$keyName = $views[$viewName]->key;
		$vars[$keyName] = array_shift($segments);
		$methodName = 'get' . ucfirst($viewName) . 'Id';

		// Get the id from the alias.
		if (is_callable([$this->router, $methodName]))
		{
			$vars[$keyName] = call_user_func_array([$this->router, $methodName], [$vars[$keyName], []]);
		}
	}

	/**
	 * Build a menu-less URL.
	 *
	 * @param   array  &$query     The vars that should be converted
	 * @param   array  &$segments  The URL segments to create
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function build(&$query, &$segments)
	{
		// If we've been given an Itemid, see if it's one of ours.
		if (isset($query['Itemid']))
		{
			// Get the menu item corresponding with the Itemid.
			$item = $this->router->menu->getItem($query['Itemid']);

			// Does the Itemid correspond to our component?
			if (!isset($query['option']) || ($item && $item->query['option'] === $query['option']))
			{
				// Menu item found, so leave it alone.
				return;
			}
		}

		// No view name given, so leave it alone.
		if (!isset($query['view']))
		{
			return;
		}

		// Get the registered views from the router.
		$views = $this->router->getViews();
		$viewName = $query['view'];

		// If the requested view is not one of our registered views, leave it alone.
		if (!isset($views[$viewName]))
		{
			return;
		}

		// Get the requested view from the router.
		$view = $views[$viewName];

		// Add the view name to the route.
		$segments[] = $viewName;

		// Is there a key associated with the requested view and have we been given a key?
		if ($view->key && isset($query[$view->key]))
		{
			// Do we have a method for converting a numeric key to an alias string?
			if (is_callable(array($this->router, 'get' . ucfirst($view->name) . 'Segment')))
			{
				$result = call_user_func_array([$this->router, 'get' . ucfirst($view->name) . 'Segment'], [$query[$view->key], $query]);
				$segments[] = str_replace(':', '-', array_shift($result));
			}

			unset($query[$view->key]);
		}

		unset($query['view']);
	}
}
