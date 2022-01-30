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
	<div class="row-fluid">
		<div class="span9">
			<?php echo $form->renderField('description'); ?>
		</div>
		<div class="span3">
			<?php echo $form->renderField('state'); ?>
			<?php echo $form->renderField('access'); ?>
		</div>
	</div>
	<div class="span6">
	</div>
</div>
