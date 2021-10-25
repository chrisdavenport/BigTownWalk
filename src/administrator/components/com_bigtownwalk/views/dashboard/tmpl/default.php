<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2021 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\ToolbarHelper;

// Searchtools options.
$options = ['filtersHidden' => (boolean) empty($this->activeFilters)];

$data = [
	'user'	=> $this->user,
	'canDo'	=> $this->canDo,
];

ToolbarHelper::title(Text::_('COM_BIGTOWNWALK_ADMIN_MENU_TITLE'));

// Show the Options toolbar button only if user has sufficient permission.
if ($this->canDo->get('core.admin'))
{
	ToolbarHelper::preferences('com_bigtownwalk');
}

ToolbarHelper::help('dashboard', true);

HTMLHelper::_('jquery.framework');
HTMLHelper::_('script', 'jui/jquery.searchtools.min.js', false, true);
HTMLHelper::_('stylesheet', 'jui/jquery.searchtools.css', false, true);
JHtmlBehavior::core();
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', '.multipleAccessLevels', null, array('placeholder_text_multiple' => Text::_('JOPTION_SELECT_ACCESS')));
HTMLHelper::_('formbehavior.chosen', 'select');
?>
<form action="<?php echo Route::_('index.php?option=com_bigtownwalk&view=dashboard'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container" class="span12">

		Hello World!

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>
