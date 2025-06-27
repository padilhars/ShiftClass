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
 * Theme ShiftClass version file.
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2025012100;  // YYYYMMDDXX format
$plugin->requires  = 2024100700;  // Requires Moodle 5.0+
$plugin->component = 'theme_shiftclass';
$plugin->maturity  = MATURITY_ALPHA;
$plugin->release   = 'v1.0.0';

// Theme dependencies
$plugin->dependencies = [
    'theme_boost' => 2024100700,  // Depends on Boost theme
];

// Plugin capabilities
$plugin->cron      = 0;
$plugin->supported = [500, 500];  // Moodle 5.0.x only.