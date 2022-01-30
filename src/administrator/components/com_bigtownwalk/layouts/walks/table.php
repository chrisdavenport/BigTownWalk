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
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Pagination\Pagination;

/* @var  array      $displayData  Data pushed into layout.        */
/* @var  array      $walks        Array of walk items.            */
/* @var  Pagination $pagination   Pagination object.              */
/* @var  string     $order        Field that table is ordered by. */
/* @var  string     $direction    Direction of ordering.          */
/* @var  JUser      $user         User object.                    */
/* @var  JObject    $canDo        User permissions.               */
$walks      = $displayData['walks'];
$order	    = $displayData['order'];
$direction  = $displayData['direction'];
$pagination = $displayData['pagination'];
?>
<table class="table table-striped" id="walkList">
	<thead>
		<tr>
			<th width="1%" class="nowrap center hidden-phone">
				<?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $direction, $order, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
			</th>

			<th width="1%" class="nowrap center">
				<?php echo HTMLHelper::_('grid.checkall'); ?>
			</th>

			<th width="1%" class="nowrap center" style="min-width:55px">
				<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $direction, $order); ?>
			</th>

			<th class="title nowrap">
				<?php echo HTMLHelper::_('searchtools.sort', 'COM_BIGTOWNWALK_HEADING_WALK_TITLE', 'a.title', $direction, $order); ?>
			</th>

			<th width="1%" class="nowrap hidden-phone">
				<?php echo HTMLHelper::_('searchtools.sort', 'COM_BIGTOWNWALK_HEADING_ID', 'a.id', $direction, $order); ?>
			</th>

		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($walks as $rownumber => $walk)
		{
			$displayData['rownumber'] = $rownumber;
			$displayData['walk']	  = $walk;
			echo LayoutHelper::render('walks.row', $displayData);
		}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5">
				<?php echo $pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
</table>
