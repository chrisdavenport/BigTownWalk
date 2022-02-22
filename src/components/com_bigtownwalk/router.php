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
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Factory;

JLoader::registerPrefix('Bigtownwalk', JPATH_ROOT . '/components/com_bigtownwalk');

/**
 * Routing class of com_bigtownwalk
 *
 * @since  __DEPLOY_VERSION__
 */
class BigtownwalkRouter extends RouterView
{
	/**
	 * Bigtownwalk component router constructor.
	 *
	 * @param   JApplicationCms  $app   The application object.
	 * @param   JMenu            $menu  The menu object to work with.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct($app = null, $menu = null)
	{
		parent::__construct($app, $menu);

		// Configure the list views.
		$walks = new RouterViewConfiguration('walks');

		// Register all the list views.
		$this->registerView($walks);

		// Register all the item views.
		$this->registerView((new RouterViewconfiguration('walk'))->setKey('id')->setParent($walks));

		// Attach the standard routing rules.
		$this->attachRule(new MenuRules($this));
		$this->attachRule(new StandardRules($this));
		$this->attachRule(new BigtownwalkNomenurules($this));
	}

	/**
	 * Method to get the segment(s) for a given entity.
	 *
	 * @param   string  $entity   The name of the entity as a table name suffix.
	 * @param   string  $segment  Segment of the entity to retrieve the ID for.
	 *
	 * @return  integer The id of this item.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function getGenericId(string $entity, string $segment): int
	{
		$db = Factory::getDbo();
		$dbquery = $db->getQuery(true)
			->select('id')
			->from('#__bigtownwalk_' . $entity)
			->where('alias = ' . $db->quote($segment));

		return (int) $db->setQuery($dbquery)->loadResult();
	}

	/**
	 * Method to get the segment(s) for a given entity.
	 *
	 * @param   string  $entity  The name of the entity as a table name suffix.
	 * @param   string  $id      Id of the entity to retrieve the segments for.
	 *
	 * @return  array  The segments of this item.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function getGenericSegment(string $entity, string $id): array
	{
		if (!strpos($id, ':'))
		{
			$db = Factory::getDbo();
			$dbquery = $db->getQuery(true)
				->select('alias')
				->from('#__bigtownwalk_' . $entity)
				->where('id = ' . $db->quote($id));
			$id .= ':' . $db->setQuery($dbquery)->loadResult();
		}

		list($void, $segment) = explode(':', $id, 2);

		return [$void => $segment];
	}

	/**
	 * Method to get the segment(s) for a Walk.
	 *
	 * @param   string  $segment  Segment of the walk to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getWalkId($segment, $query)
	{
		return $this->getGenericId('walks', $segment);
	}

	/**
	 * Method to get the segment(s) for a Walk.
	 *
	 * @param   string  $id     ID of the Walk to retrieve the segments for.
	 * @param   array   $query  The request that is built right now.
	 *
	 * @return  array|string  The segments of this item.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getWalkSegment($id, $query)
	{
		return $this->getGenericSegment('walks', $id);
	}
}

/**
 * Content router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @param   array  &$query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 *
 * @throws  Exception
 * @since   __DEPLOY_VERSION__
 * @deprecated  4.0  Use Class based routers instead
 */
function bigtownwalkBuildRoute(&$query)
{
	$app    = Factory::getApplication();
	$router = new BigtownwalkRouter($app, $app->getMenu());

	return $router->build($query);
}

/**
 * Parse the segments of a URL.
 *
 * This function is a proxy for the new router interface
 * for old SEF extensions.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 *
 * @throws  Exception
 * @since       __DEPLOY_VERSION__
 * @deprecated  4.0  Use Class based routers instead
 */
function bigtownwalkParseRoute($segments)
{
	$app    = Factory::getApplication();
	$router = new BigtownwalkRouter($app, $app->getMenu());

	return $router->parse($segments);
}
