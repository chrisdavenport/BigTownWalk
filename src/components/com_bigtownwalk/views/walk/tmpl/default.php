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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

$app = Factory::getApplication();

// Add canonical as Walk can have multiple routes.
$canonical = BigtownwalkHelperUrl::getCanonical('walk', $this->walk->id, $this->walk->alias);
BigtownwalkHelperUrl::addCanonical($canonical);

// Get the breadcrumb names.
$pathwayNames = $app->getPathway()->getPathwayNames();

// Add our breadcrumb only if it hasn't already been added.
if (end($pathwayNames) !== $this->walk->title)
{
	// Add breadcrumb.
	$app->getPathway()->addItem($this->walk->title);
}

// Set up the data to be pushed into the layouts.
$displayData = [
	'view'		=> $this,
	'params'	=> $this->params,
	'walk'		=> $this->walk,
	'Itemid'	=> $app->input->getInt('Itemid'),
	'canonical'	=> $canonical,
];
?>
<div itemprop="articleBody">

	<?php echo LayoutHelper::render('walk', $displayData); ?>

</div>
