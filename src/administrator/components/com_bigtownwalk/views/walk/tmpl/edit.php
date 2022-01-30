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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

$options = [];
$isNew = $this->item->id == 0;

// Add sidebar.
BigtownwalkHelperAdmin::addSubmenu('walk', $this->item->id);

// Toolbar title.
if ($this->item->id)
{
	ToolbarHelper::title(Text::sprintf('COM_BIGTOWNWALK_ADMIN_WALK_TITLE', $this->item->title));
}
else
{
	ToolbarHelper::title(Text::_('COM_BIGTOWNWALK_ADMIN_WALK_TITLE_NEW'));
}

// Show Save, Save & Close and Save as Copy buttons but only if user has sufficient permission.
if (($isNew && $this->canDo->get('core.create')) | $this->canDo->get('core.edit'))
{
	ToolbarHelper::apply('walk.apply');
	ToolbarHelper::save('walk.save');

	// We can save this walk, but check the create permission to see if we can return to make a new one.
	if ($this->canDo->get('core.create'))
	{
		ToolbarHelper::save2copy('walk.save2copy');
	}
}

ToolbarHelper::cancel('walk.cancel');
ToolbarHelper::help('walk', true);

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.tabstate');
HTMLHelper::_('formbehavior.chosen', 'select');

Factory::getApplication()->getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "walk.cancel" || document.formvalidator.isValid(document.getElementById("form-walk")))
		{
			Joomla.submitform(task, document.getElementById("form-walk"));
		}

		var msg = [];

		if (jQuery("#jform_title").hasClass("invalid"))
		{
			msg.push("' . Text::_('COM_BIGTOWNWALK_WALK_MUST_HAVE_TITLE') . '");
		}

		// Show all error messages.
		for (var i = 0, len = msg.length; i < len; i++)
		{
			jQuery(".alert").append("<div>" + msg[i] + "</div>");
		}
	};
');

$displayData = [
	'view' => $this,
	'form' => $this->form,
	'item' => $this->item,
];
?>
<form action="index.php" method="post" encType="multipart/form-data" name="adminForm" id="form-walk" class="form-validate">

	<div class="form-inline form-inline-header">
		<?php
		echo $this->form->renderField('title');
		echo $this->form->renderField('alias');
		?>
	</div>

	<div class="form-horizontal">
		<?php
		echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active' => 'general'));

		// General tab.
		echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'general', Text::_('COM_BIGTOWNWALK_TAB_GENERAL_LABEL'));
		echo LayoutHelper::render('walk.tab.general', $displayData);
		echo HTMLHelper::_('bootstrap.endTab');

		// Publishing tab.
		echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'publishing', Text::_('COM_BIGTOWNWALK_TAB_PUBLISHING_LABEL'));
		echo LayoutHelper::render('walk.tab.publishing', $displayData);
		echo HTMLHelper::_('bootstrap.endTab');

		echo HTMLHelper::_('bootstrap.endTabSet');
		?>
	</div>

	<input type="hidden" name="option" value="com_bigtownwalk" />
	<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo HTMLHelper::_('form.token'); ?>

</form>
