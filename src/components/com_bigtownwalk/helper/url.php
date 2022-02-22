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
use Joomla\CMS\Uri\Uri;

/**
 * URL helper class.
 *
 * @since  __DEPLOY_VERSION__
 */

final class BigtownwalkHelperUrl
{
	/**
	 * Add canonical tag.
	 *
	 * @param   string  $url  Canonical URL.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function addCanonical(string $url)
	{
		Factory::getApplication()->getDocument()->addCustomTag('<link rel="canonical" href="' . $url . '" />');
	}

	/**
	 * Determine the canonical link for a given item.
	 *
	 * @param   string   $entity  Entity name (singular).
	 * @param   integer  $id      Entity id.
	 * @param   string   $alias   Entity alias.
	 * @param   string   $idName  Name of the id variable.
	 *
	 * @return  string
	 *
	 * @throws  Exception
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getCanonical(string $entity, int $id, string $alias, string $idName = 'id')
	{
		$db = Factory::getDbo();

		// Look for a menu item pointing to the specific item.
		$query = $db->getQuery(true)
			->select('path')
			->from('#__menu')
			->where('menutype = ' . $db->quote('mainmenu'))
			->where('link = ' . $db->quote('index.php?option=com_bigtownwalk&view=' . $entity . '&' . $idName . '=' . $id))
			->where('published = 1');
		$path = $db->setQuery($query)->loadResult();

		// If we found a menu item pointing to this specific id then return the path.
		if ($path !== null)
		{
			return self::getRoot() . $path;
		}

		// Look for a menu item pointing to the list view for the entity.
		$query = $db->getQuery(true)
			->select('path')
			->from('#__menu')
			->where('menutype = ' . $db->quote('mainmenu'))
			->where('link LIKE ' . $db->quote('index.php?option=com_bigtownwalk&view=' . $entity . 's%'))
			->where('published = 1');
		$path = $db->setQuery($query)->loadResult();

		// If we found a menu item pointing to the list view then return the path.
		if ($path !== null)
		{
			return self::getRoot() . $path . ($alias !== '' ? '/' . $alias : '');
		}

		return Uri::root() . 'index.php?option=com_bigtownwalk&view=' . $entity . '&' . $idName . '=' . $id . ($alias !== '' ? ':' . $alias : '');
	}

	/**
	 * Get the root URL of the site.
	 *
	 * @return  string
	 *
	 * @throws  Exception
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getRoot()
	{
		$root = Uri::root();

		if (Factory::getApplication()->get('sef_rewrite') === '0')
		{
			$root .= 'index.php/';
		}

		return $root;
	}
}
