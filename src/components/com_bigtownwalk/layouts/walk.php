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
echo $this->sublayout('above', $displayData);
echo $this->sublayout('page', $displayData);
echo $this->sublayout('below', $displayData);
