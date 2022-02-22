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
use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Bigtownwalk base controller
 *
 * @since  __DEPLOY_VERSION__
 */
class BigtownwalkController extends BaseController
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  BigtownwalkController This object to support chaining.
	 *
	 * @throws  Exception
	 * @since	__DEPLOY_VERSION__
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$input = Factory::getApplication()->input;

		$view = $input->getCmd('view', 'walks');
		$input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
}
