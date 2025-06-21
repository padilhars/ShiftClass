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
 * Theme ShiftClass event observers
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Event observers for theme_shiftclass
$observers = array(
    // Observer for course deletion to clean up profile associations
    array(
        'eventname' => '\core\event\course_deleted',
        'callback' => 'theme_shiftclass_course_deleted_observer',
        'includefile' => '/theme/shiftclass/lib.php',
        'priority' => 0,
    ),
    
    // Observer for course viewing to apply visual profile
    array(
        'eventname' => '\core\event\course_viewed',
        'callback' => 'theme_shiftclass_course_viewed_observer',
        'includefile' => '/theme/shiftclass/lib.php',
        'priority' => 0,
    ),
);