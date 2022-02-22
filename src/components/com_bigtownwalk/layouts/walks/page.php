<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2022 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

/* @var   array  $displayData  Data injected into layout. */
?>
<ul class="grid-2 walks">
	<?php foreach ($displayData['walks'] as $walk) { ?>
		<li>
			<?php
			$displayData['walk'] = $walk;
			echo $this->sublayout('walk', $displayData);
			?>
		</li>
	<?php } ?>
</ul>
