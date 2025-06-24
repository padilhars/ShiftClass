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
 * Cache definitions for ShiftClass theme
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$definitions = [
    // Cache for visual profiles
    'profiles' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'simplevalues' => false,
        'staticacceleration' => true,
        'staticaccelerationsize' => 100,
        'ttl' => 3600, // 1 hour
        'invalidationevents' => [
            'theme_shiftclass_profile_created',
            'theme_shiftclass_profile_updated',
            'theme_shiftclass_profile_deleted',
        ],
    ],
    
    // Cache for course profile assignments
    'courseprofiles' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'simplevalues' => false,
        'staticacceleration' => true,
        'staticaccelerationsize' => 500,
        'ttl' => 7200, // 2 hours
        'invalidationevents' => [
            'theme_shiftclass_course_profile_assigned',
            'theme_shiftclass_course_profile_removed',
        ],
    ],
    
    // Cache for compiled CSS per profile
    'profilecss' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'simplevalues' => true,
        'staticacceleration' => true,
        'staticaccelerationsize' => 50,
        'ttl' => 86400, // 24 hours
        'invalidationevents' => [
            'theme_shiftclass_profile_updated',
            'theme_shiftclass_profile_deleted',
        ],
    ],
];