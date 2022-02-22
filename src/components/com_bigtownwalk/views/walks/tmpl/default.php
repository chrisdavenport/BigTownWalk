<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2022 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Layout\LayoutHelper;

// Set up the data to be pushed into the layouts.
$displayData = [
	'view'		 => $this,
	'params'	 => $this->params,
	'walks'      => $this->walks,
];
?>
<div itemprop="articleBody">

	<?php echo LayoutHelper::render('walks', $displayData); ?>

</div>
