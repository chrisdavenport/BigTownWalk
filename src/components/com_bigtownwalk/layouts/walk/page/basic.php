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
<h1>
	<?php echo $walk->title; ?>
</h1>
<div class="description">
	<?php echo $walk->description; ?>
</div>
