<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2022 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Form\Form;

/* @var   array   $displayData  Data pushed into layout. */
/* @var   Form    $form         Form object.             */
/* @var   object  $item         Walk object.             */
$form = $displayData['form'];
$item = $displayData['item'];
?>
<div class="row-fluid">
	<div class="span6">
		<?php echo $form->renderField('created'); ?>
		<?php echo $form->renderField('modified'); ?>
		<?php echo $form->renderField('publish_up'); ?>
		<?php echo $form->renderField('publish_down'); ?>
		<?php echo $form->renderField('id'); ?>
	</div>
</div>
