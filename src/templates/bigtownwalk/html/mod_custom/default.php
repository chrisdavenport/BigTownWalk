<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;
?>
<div class="custom<?php echo $moduleclass_sfx; ?>">
	<?php
	if ($params->get('backgroundimage'))
	{
		echo HTMLHelper::image($params->get('backgroundimage'), $module->title, ['class' => 'img-responsive']);
	}
	?>
	<?php echo $module->content; ?>
</div>
