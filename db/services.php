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
 * Web services for ShiftClass theme
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'theme_shiftclass_create_profile' => [
        'classname'   => 'theme_shiftclass\external',
        'methodname'  => 'create_profile',
        'classpath'   => 'theme/shiftclass/externallib.php',
        'description' => 'Create a new visual profile',
        'type'        => 'write',
        'capabilities' => 'theme/shiftclass:manageprofiles',
        'ajax'        => true,
        'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE]
    ],
    
    'theme_shiftclass_export_profiles' => [
        'classname'   => 'theme_shiftclass\external',
        'methodname'  => 'export_profiles',
        'classpath'   => 'theme/shiftclass/externallib.php',
        'description' => 'Export all visual profiles',
        'type'        => 'read',
        'capabilities' => 'theme/shiftclass:manageprofiles',
        'ajax'        => true,
        'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE]
    ],
    
    'theme_shiftclass_import_profiles' => [
        'classname'   => 'theme_shiftclass\external',
        'methodname'  => 'import_profiles',
        'classpath'   => 'theme/shiftclass/externallib.php',
        'description' => 'Import visual profiles',
        'type'        => 'write',
        'capabilities' => 'theme/shiftclass:manageprofiles',
        'ajax'        => true,
        'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE]
    ],
    
    'theme_shiftclass_check_contrast' => [
        'classname'   => 'theme_shiftclass\external',
        'methodname'  => 'check_contrast',
        'classpath'   => 'theme/shiftclass/externallib.php',
        'description' => 'Check contrast ratio between two colors',
        'type'        => 'read',
        'capabilities' => '',
        'ajax'        => true,
        'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE]
    ],
];

$services = [
    'ShiftClass theme service' => [
        'functions' => [
            'theme_shiftclass_create_profile',
            'theme_shiftclass_export_profiles',
            'theme_shiftclass_import_profiles',
            'theme_shiftclass_check_contrast'
        ],
        'restrictedusers' => 0,
        'enabled' => 1,
    ]
];