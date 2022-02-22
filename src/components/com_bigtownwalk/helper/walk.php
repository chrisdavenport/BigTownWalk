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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/**
 * Walk helper class.
 *
 * @since  __DEPLOY_VERSION__
 */

final class BigtownwalkHelperWalk
{
	/**
	 * Get menu item path pointing to a single walk view.
	 *
	 * @param   integer  $id  Walk id.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private static function getPathToWalkView(int $id): ?string
	{
		static $paths = [];

		// If we've already calculated this path, return it.
		if (isset($paths[$id]))
		{
			return $paths[$id];
		}

		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('path')
			->from('#__menu')
			->where('menutype != ' . $db->quote('main'))
			->where('link = ' . $db->quote('index.php?option=com_bigtownwalk&view=walk&id=' . $id))
			->where('published = 1');
		$paths[$id] = $db->setQuery($query)->loadResult();

		return $paths[$id];
	}

	/**
	 * Get menu item path pointing to an walks view.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private static function getPathToWalksView(): ?string
	{
		static $path;

		// If we've already calculated the path, return it.
		if ($path !== null)
		{
			return $path;
		}

		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('path')
			->from('#__menu')
			->where('menutype != ' . $db->quote('main'))
			->where('link = ' . $db->quote('index.php?option=com_bigtownwalk&view=walks'))
			->where('published = 1');
		$path = $db->setQuery($query)->loadResult();

		return $path;
	}

	/**
	 * Build a pretty (relative) route to the walk.
	 *
	 * @param   integer  $id     Walk id.
	 * @param   ?string  $alias  Walk alias.
	 *
	 * @return  string
	 *
	 * @throws  Exception
	 * @since   __DEPLOY_VERSION__
	 */
	public static function relative(int $id, ?string $alias): string
	{
		// Prefix not required when SEF rewrite enabled.
		$prefix = Factory::getApplication()->get('sef_rewrite') === '1' ? '' : 'index.php/';

		// Is there a menu item pointing to the exact walk?
		$path = self::getPathToWalkView($id);

		if (!empty($path))
		{
			return $prefix . $path;
		}

		// Is there a menu item pointing to a full walk view?
		$path = self::getPathToWalksView();

		if (!empty($path))
		{
			return $prefix . $path . ($alias !== '' ? '/' . $alias : '');
		}

		// Otherwise, return the default ugly route.
		return Route::_('index.php?option=com_bigtownwalk&view=walk&id=' . $id . ($alias !== '' ? ':' . $alias : ''));
	}

	/**
	 * Build a pretty (absolute) route to the walk.
	 *
	 * @param   integer  $id     Walk id.
	 * @param   ?string  $alias  Walk alias.
	 *
	 * @return  string
	 *
	 * @throws  Exception
	 * @since   __DEPLOY_VERSION__
	 */
	public static function route(int $id, ?string $alias): string
	{
		return Uri::root() . self::relative($id, $alias);
	}
}
