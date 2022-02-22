<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2022 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/* @var   array  $displayData  Data pushed into layout. */
$walk = $displayData['walk'];
?>
<a href="<?php echo BigtownwalkHelperWalk::route($walk->id, $walk->alias); ?>">
	<?php if (!empty($walk->image)) { ?>
		<img src="<?php echo $walk->image; ?>" alt="<?php echo $walk->title; ?>" />
	<?php } ?>
	<span class="title"><?php echo $walk->title; ?></span>
	<span class="cta"><?php echo Text::_('COM_BIGTOWNWALK_WALKS_LIST_VIEW_MORE'); ?></span>
</a>
