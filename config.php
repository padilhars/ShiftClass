<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme ShiftClass config file.
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Theme name
$THEME->name = 'shiftclass';

// Inherit from Boost theme - this will automatically inherit ALL layouts
$THEME->parents = ['boost'];

// Use theme settings
$THEME->enable_dock = false;
$THEME->yuicssmodules = [];
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->requiredblocks = '';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;

// SCSS configuration - Critical for proper compilation
$THEME->scss = function($theme) {
    return theme_shiftclass_get_main_scss_content($theme);
};

// Pre and Post SCSS
$THEME->prescsscallback = 'theme_shiftclass_get_pre_scss';
$THEME->extrascsscallback = 'theme_shiftclass_get_extra_scss';

// DO NOT define $THEME->layouts - let it inherit completely from Boost
// This ensures all sidebars, course index, and block regions work correctly

// Additional theme options - inherit from Boost
$THEME->iconsystem = \core\output\icon_system::FONTAWESOME;
$THEME->haseditswitch = true;
$THEME->usescourseindex = true;
$THEME->primarynavigation = true;