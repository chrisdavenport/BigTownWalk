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
use Joomla\CMS\Language\Text;

/**
 * Walk view.
 *
 * @since  __DEPLOY_VERSION__
 */
class BigtownwalkViewWalk extends BigtownwalkView
{
	// Form associated with the walk.
	protected $form;

	/**
	 * Itemid of the Walks page.
	 *
	 * @var    integer
	 * @since  __DEPLOY_VERSION__
	 */
	protected $Itemid;

	// The current walk.
	protected $walk;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @throws  Exception, RuntimeException
	 * @since   __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		$this->state     = $this->get('State');
		$this->walk      = $this->get('Walk');
		$this->params    = $this->getParams();
		$this->Itemid    = $this->getItemid();

		// Do we have a published walk?
		if ((int) $this->walk->state !== 1)
		{
			throw new Exception(Text::_('COM_BIGTOWNWALK_ERROR_WALK_NOT_FOUND'), 404);
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		// Prepare the document.
		$this->prepareDocument();

		return parent::display($tpl);
	}

	/**
	 * Get the Itemid of the Walks page.
	 *
	 * This is needed so we can construct canonical links for the Walk.
	 *
	 * @return  integer
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	private function getItemid()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('id')
			->from('#__menu')
			->where('menutype = ' . $db->quote('mainmenu'))
			->where('link = ' . $db->quote('index.php?option=com_bigtownwalk&view=walks'))
			->where('published = 1');

		return (int) $db->setQuery($query)->loadResult();
	}
}
