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

$chunks = array_chunk(ModBigtownwalksHelper::getWalks(), 3);
?>
<?php foreach ($chunks as $chunk) { ?>
	<div class="row row-margin-2 text-center inner equal">
		<?php foreach ($chunk as $walk) { ?>
			<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mb-4">
				<div class="custom feature-content">
					<p>
						<img src="templates/bigtownwalk/images/1-1.jpg" alt="Image" />
					</p>
					<h2 class="feature-content-title green-text">
						<?php echo $walk->title; ?>
					</h2>
					<p class="feature-content-description">
						<?php echo HTMLHelper::_('string.truncateComplex', $walk->description, 150); ?>
					</p>
					<p>
						<a href="#" class="feature-content-link green-btn">Read more...</a>
					</p>
				</div>
			</div>
		<?php } ?>
	</div>
<?php }
