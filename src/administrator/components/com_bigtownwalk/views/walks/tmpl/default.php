<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2022 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\ToolbarHelper;

// Searchtools options.
$options = [
	'filtersHidden' => (boolean) empty($this->activeFilters),
	'noResultsText' => Text::_('COM_BIGTOWNWALK_NO_WALKS'),
	];

$data = [
	'walks'		 => $this->walks,
	'pagination' => $this->pagination,
	'order'		 => $this->escape($this->state->get('list.ordering')),
	'direction'	 => $this->escape($this->state->get('list.direction')),
	'user'		 => $this->user,
	'canDo'		 => $this->canDo,
	];

ToolbarHelper::title(Text::_('COM_BIGTOWNWALK_ADMIN_MENU_TITLE'));

// Show the New toolbar button but only if user has sufficient permission.
if ($this->canDo->get('core.create'))
{
	ToolbarHelper::addNew('walk.add');
}

// Show Publish, Unpublish and Archive buttons but only if user has sufficient permission.
if ($this->canDo->get('core.edit.state'))
{
	ToolbarHelper::publish('walks.publish', 'JTOOLBAR_PUBLISH', true);
	ToolbarHelper::unpublish('walks.unpublish', 'JTOOLBAR_UNPUBLISH', true);
	ToolbarHelper::archiveList('walks.archive');
}

// Show the Trash toolbar button but only if user has sufficient permission.
if ($this->canDo->get('core.delete'))
{
	if ($this->state->get('filter.published') == -2)
	{
		ToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'walks.delete', 'JTOOLBAR_EMPTY_TRASH');
	}
	else
	{
		ToolbarHelper::trash('walks.trash');
	}
}

// Show the Options toolbar button only if user has sufficient permission.
if ($this->canDo->get('core.admin'))
{
	ToolbarHelper::preferences('com_bigtownwalk');
}

ToolbarHelper::help('walks', true);

HTMLHelper::_('jquery.framework');
HTMLHelper::_('script', 'jui/jquery.searchtools.min.js', false, true);
HTMLHelper::_('stylesheet', 'jui/jquery.searchtools.css', false, true);
JHtmlBehavior::core();
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', '.multipleAccessLevels', null, array('placeholder_text_multiple' => Text::_('JOPTION_SELECT_ACCESS')));
HTMLHelper::_('formbehavior.chosen', 'select');

// Add sidebar.
BigtownwalkHelperAdmin::addSubmenu('walks');
?>
<form action="<?php echo Route::_('index.php?option=com_bigtownwalk&view=walks'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo JHtmlSidebar::render(); ?>
	</div>

	<div id="j-main-container" class="span12">

		<?php echo LayoutHelper::render('walks.warnings'); ?>

		<?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this, 'options' => $options]); ?>

		<?php echo LayoutHelper::render('walks.table', $data); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>
