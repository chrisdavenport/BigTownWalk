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
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Language\Text;

JLoader::registerPrefix('BigtownwalkHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helper');

// Is user allowed access to the backend of this component?
if (!ContentHelper::getActions('com_bigtownwalk')->get('core.manage'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

// Get an instance of the controller.
$controller = BaseController::getInstance('Bigtownwalk');

// Perform the Request task.
$controller->execute(Factory::getApplication()->input->getCmd('task'));

// Redirect if set by the controller.
$controller->redirect();
