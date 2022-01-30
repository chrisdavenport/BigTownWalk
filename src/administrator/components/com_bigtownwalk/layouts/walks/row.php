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

/* @var  array  $displayData  Array of data passed into the layout. */
extract($displayData);

/* @var  array        $walks       Array of walk items. */
/* @var  JPagination  $pagination  Pagination object. */
/* @var  string       $order       Field that table is ordered by. */
/* @var  string       $direction   Direction of ordering. */
/* @var  JUser        $user        User object. */
/* @var  JObject      $canDo       User permissions. */
/* @var  integer      $rownumber   Row number in table. */
/* @var  stdClass     $walk      walk object. */
$walk		= $displayData['walk'];
$rownumber	= $displayData['rownumber'];
$order	    = $displayData['order'];
$direction  = $displayData['direction'];

$canChange  = $canDo->get('core.edit.state');
$canEdit	= $canDo->get('core.edit');
$canDelete	= $canDo->get('core.delete');
$canCheckin = $user->authorise('core.manage', 'com_checkin') || $walk->checked_out === $user->id || $walk->checked_out === 0;

$rowclass	= $rownumber % 2 == 0 ? 'row0' : 'row1';
$saveOrder	= ($order === 'a.ordering');

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_bigtownwalk&task=walks.saveOrderAjax&tmpl=component';
	HTMLHelper::_('sortablelist.sortable', 'walkList', 'adminForm', strtolower($direction), $saveOrderingUrl);
}
?>
<tr class="<?php echo $rowclass ?>">

	<td class="order nowrap center hidden-phone">
		<?php
		$iconClass = '';

		if (!$canChange)
		{
			$iconClass = ' inactive';
		}
		elseif (!$saveOrder)
		{
			$iconClass = ' inactive tip-top hasTooltip" title="' . HTMLHelper::_('tooltipText', 'JORDERINGDISABLED');
		}
		?>

		<span class="sortable-handler<?php echo $iconClass; ?>">
			<span class="icon-menu"></span>
		</span>

		<?php if ($canChange && $saveOrder) { ?>
			<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $walk->ordering; ?>" class="width-20 text-area-order" />
		<?php } ?>
	</td>

	<td>
		<?php echo HTMLHelper::_('grid.id', $rownumber, $walk->id); ?>
	</td>

	<td class="center">
		<div class="btn-group">
			<?php
			echo HTMLHelper::_('jgrid.published', $walk->state, $rownumber, 'walks.', $canChange, 'cb');

			if ($canChange)
			{
				HTMLHelper::_('actionsdropdown.' . ((int) $walk->state === 2 ? 'un' : '') . 'archive', 'cb' . $rownumber, 'walks');
			}

			if ($canDelete)
			{
				HTMLHelper::_('actionsdropdown.' . ((int) $walk->state === -2 ? 'un' : '') . 'trash', 'cb' . $rownumber, 'walks');
			}

			if ($canChange || $canDelete)
			{
				echo HTMLHelper::_('actionsdropdown.render', $this->escape($walk->title));
			}
			?>
		</div>
	</td>

	<td>
		<?php if ($walk->checked_out) { ?>
			<?php echo HTMLHelper::_('jgrid.checkedout', $rownumber, $walk->editor, $walk->checked_out_time, 'walks.', $canCheckin); ?>
		<?php } ?>
		<?php echo HTMLHelper::link('index.php?option=com_bigtownwalk&task=walk.edit&id=' . $walk->id, $walk->title); ?>
		<span class="small break-word">
			<?php if (!empty($walk->alias)) { ?>
				<?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($walk->alias)); ?>
			<?php } ?>
		</span>
	</td>

	<td>
		<?php echo $walk->id; ?>
	</td>

</tr>
