<?php
/**
 * @package     ChrisDavenport
 * @subpackage  BigTownWalk
 *
 * @copyright   Copyright (C) 2022 Davenport Technology Services. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

defined('_JEXEC') or die;

/*
 * 1-column layout.
 */
function modChrome_col1($module, &$params, &$attribs)
{
	$headerTag = htmlspecialchars($params->get('header_tag', 'h3'), ENT_QUOTES, 'UTF-8');

	// Temporarily store header class in variable
	$headerClass    = $params->get('header_class');
	$headerClass    = $headerClass ? ' class="' . htmlspecialchars($headerClass, ENT_COMPAT, 'UTF-8') . '"' : '';

	if (!empty ($module->content)) : ?>
		<div class="col-lg-12 mb-4">
			<div class="tm-box">
				<?php if ((bool) $module->showtitle) : ?>
					<<?php echo $headerTag . $headerClass . '>' . $module->title; ?></<?php echo $headerTag; ?>>
				<?php endif; ?>
				<?php echo $module->content; ?>
			</div>
		</div>
	<?php endif;
}

/*
 * 2-column layout.
 */
function modChrome_col2($module, &$params, &$attribs)
{
	$headerTag = htmlspecialchars($params->get('header_tag', 'h3'), ENT_QUOTES, 'UTF-8');

	// Temporarily store header class in variable
	$headerClass    = $params->get('header_class');
	$headerClass    = $headerClass ? ' class="' . htmlspecialchars($headerClass, ENT_COMPAT, 'UTF-8') . '"' : '';

	if (!empty ($module->content)) : ?>
		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
		<?php if ((bool) $module->showtitle) : ?>
			<<?php echo $headerTag . $headerClass . '>' . $module->title; ?></<?php echo $headerTag; ?>>
		<?php endif; ?>
		<?php echo $module->content; ?>
		</div>
	<?php endif;
}

/*
 * 3-column layout.
 */
function modChrome_col3($module, &$params, &$attribs)
{
	$headerTag = htmlspecialchars($params->get('header_tag', 'h3'), ENT_QUOTES, 'UTF-8');

	// Temporarily store header class in variable
	$headerClass    = $params->get('header_class');
	$headerClass    = $headerClass ? ' class="' . htmlspecialchars($headerClass, ENT_COMPAT, 'UTF-8') . '"' : '';

	if (!empty ($module->content)) : ?>
		<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 mb-4">
			<?php if ((bool) $module->showtitle) : ?>
				<<?php echo $headerTag . $headerClass . '>' . $module->title; ?></<?php echo $headerTag; ?>>
			<?php endif; ?>
			<?php echo $module->content; ?>
		</div>
	<?php endif;
}

/*
 * 4-column layout.
 */
function modChrome_col4($module, &$params, &$attribs)
{
	$headerTag = htmlspecialchars($params->get('header_tag', 'h3'), ENT_QUOTES, 'UTF-8');

	// Temporarily store header class in variable
	$headerClass    = $params->get('header_class');
	$headerClass    = $headerClass ? ' class="' . htmlspecialchars($headerClass, ENT_COMPAT, 'UTF-8') . '"' : '';

	if (!empty ($module->content)) : ?>
		<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6 col-xxs-12 mb-4">
			<?php if ((bool) $module->showtitle) : ?>
				<<?php echo $headerTag . $headerClass . '>' . $module->title; ?></<?php echo $headerTag; ?>>
			<?php endif; ?>
			<?php echo $module->content; ?>
		</div>
	<?php endif;
}

/*
 * xhtml (divs and font header tags)
 * With the new advanced parameter it does the same as the html5 chrome
 */
function modChrome_right($module, &$params, &$attribs)
{
	$headerTag = htmlspecialchars($params->get('header_tag', 'h3'), ENT_QUOTES, 'UTF-8');

	// Temporarily store header class in variable
	$headerClass    = $params->get('header_class');
	$headerClass    = $headerClass ? ' class="' . htmlspecialchars($headerClass, ENT_COMPAT, 'UTF-8') . '"' : '';

	if (!empty ($module->content)) : ?>
		<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
			<?php if ((bool) $module->showtitle) : ?>
				<<?php echo $headerTag . $headerClass . '>' . $module->title; ?></<?php echo $headerTag; ?>>
			<?php endif; ?>
			<?php echo $module->content; ?>
		</div>
	<?php endif;
}
