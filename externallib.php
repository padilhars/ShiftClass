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
 * External functions for ShiftClass theme
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_shiftclass;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use external_multiple_structure;
use context_system;
use moodle_exception;

/**
 * External functions class
 */
class external extends external_api {
    
    /**
     * Parameters for create_profile
     * @return external_function_parameters
     */
    public static function create_profile_parameters() {
        return new external_function_parameters([
            'name' => new external_value(PARAM_TEXT, 'Profile name'),
            'primarycolor' => new external_value(PARAM_TEXT, 'Primary color in hex format'),
            'secondarycolor' => new external_value(PARAM_TEXT, 'Secondary color in hex format'),
            'backgroundcolor' => new external_value(PARAM_TEXT, 'Background color in hex format'),
            'defaultheaderimage' => new external_value(PARAM_URL, 'Default header image URL', VALUE_OPTIONAL)
        ]);
    }
    
    /**
     * Create a visual profile
     * 
     * @param string $name Profile name
     * @param string $primarycolor Primary color
     * @param string $secondarycolor Secondary color
     * @param string $backgroundcolor Background color
     * @param string $defaultheaderimage Header image URL
     * @return array Result
     */
    public static function create_profile($name, $primarycolor, $secondarycolor, $backgroundcolor, $defaultheaderimage = '') {
        global $USER;
        
        // Validate parameters
        $params = self::validate_parameters(self::create_profile_parameters(), [
            'name' => $name,
            'primarycolor' => $primarycolor,
            'secondarycolor' => $secondarycolor,
            'backgroundcolor' => $backgroundcolor,
            'defaultheaderimage' => $defaultheaderimage
        ]);
        
        // Check capability
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('theme/shiftclass:manageprofiles', $context);
        
        // Create profile
        $manager = new profiles_manager();
        
        try {
            $data = new \stdClass();
            $data->name = $params['name'];
            $data->primarycolor = $params['primarycolor'];
            $data->secondarycolor = $params['secondarycolor'];
            $data->backgroundcolor = $params['backgroundcolor'];
            $data->defaultheaderimage = $params['defaultheaderimage'];
            
            $profileid = $manager->create_profile($data);
            
            return [
                'success' => true,
                'profileid' => $profileid,
                'message' => get_string('profilecreated', 'theme_shiftclass')
            ];
            
        } catch (moodle_exception $e) {
            return [
                'success' => false,
                'profileid' => 0,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Return structure for create_profile
     * @return external_single_structure
     */
    public static function create_profile_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Success status'),
            'profileid' => new external_value(PARAM_INT, 'New profile ID'),
            'message' => new external_value(PARAM_TEXT, 'Result message')
        ]);
    }
    
    /**
     * Parameters for export_profiles
     * @return external_function_parameters
     */
    public static function export_profiles_parameters() {
        return new external_function_parameters([]);
    }
    
    /**
     * Export all visual profiles
     * 
     * @return array Exported profiles
     */
    public static function export_profiles() {
        // Check capability
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('theme/shiftclass:manageprofiles', $context);
        
        $manager = new profiles_manager();
        $profiles = $manager->get_all_profiles();
        
        $export = [];
        foreach ($profiles as $profile) {
            $export[] = [
                'name' => $profile->name,
                'primarycolor' => $profile->primarycolor,
                'secondarycolor' => $profile->secondarycolor,
                'backgroundcolor' => $profile->backgroundcolor,
                'defaultheaderimage' => $profile->defaultheaderimage ?? ''
            ];
        }
        
        return ['profiles' => $export];
    }
    
    /**
     * Return structure for export_profiles
     * @return external_single_structure
     */
    public static function export_profiles_returns() {
        return new external_single_structure([
            'profiles' => new external_multiple_structure(
                new external_single_structure([
                    'name' => new external_value(PARAM_TEXT, 'Profile name'),
                    'primarycolor' => new external_value(PARAM_TEXT, 'Primary color'),
                    'secondarycolor' => new external_value(PARAM_TEXT, 'Secondary color'),
                    'backgroundcolor' => new external_value(PARAM_TEXT, 'Background color'),
                    'defaultheaderimage' => new external_value(PARAM_URL, 'Header image URL')
                ])
            )
        ]);
    }
    
    /**
     * Parameters for import_profiles
     * @return external_function_parameters
     */
    public static function import_profiles_parameters() {
        return new external_function_parameters([
            'profiles' => new external_multiple_structure(
                new external_single_structure([
                    'name' => new external_value(PARAM_TEXT, 'Profile name'),
                    'primarycolor' => new external_value(PARAM_TEXT, 'Primary color'),
                    'secondarycolor' => new external_value(PARAM_TEXT, 'Secondary color'),
                    'backgroundcolor' => new external_value(PARAM_TEXT, 'Background color'),
                    'defaultheaderimage' => new external_value(PARAM_URL, 'Header image URL', VALUE_OPTIONAL)
                ])
            )
        ]);
    }
    
    /**
     * Import visual profiles
     * 
     * @param array $profiles Profiles to import
     * @return array Result
     */
    public static function import_profiles($profiles) {
        // Validate parameters
        $params = self::validate_parameters(self::import_profiles_parameters(), ['profiles' => $profiles]);
        
        // Check capability
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('theme/shiftclass:manageprofiles', $context);
        
        $manager = new profiles_manager();
        $imported = 0;
        $errors = 0;
        
        foreach ($params['profiles'] as $profiledata) {
            try {
                $data = (object)$profiledata;
                $manager->create_profile($data);
                $imported++;
            } catch (moodle_exception $e) {
                $errors++;
            }
        }
        
        return [
            'success' => $errors == 0,
            'imported' => $imported,
            'errors' => $errors,
            'message' => get_string('profilesimported', 'theme_shiftclass', ['imported' => $imported, 'errors' => $errors])
        ];
    }
    
    /**
     * Return structure for import_profiles
     * @return external_single_structure
     */
    public static function import_profiles_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Success status'),
            'imported' => new external_value(PARAM_INT, 'Number of profiles imported'),
            'errors' => new external_value(PARAM_INT, 'Number of errors'),
            'message' => new external_value(PARAM_TEXT, 'Result message')
        ]);
    }
    
    /**
     * Parameters for check_contrast
     * @return external_function_parameters
     */
    public static function check_contrast_parameters() {
        return new external_function_parameters([
            'color1' => new external_value(PARAM_TEXT, 'First color in hex format'),
            'color2' => new external_value(PARAM_TEXT, 'Second color in hex format')
        ]);
    }
    
    /**
     * Check contrast ratio between two colors
     * 
     * @param string $color1 First color
     * @param string $color2 Second color
     * @return array Contrast information
     */
    public static function check_contrast($color1, $color2) {
        // Validate parameters
        $params = self::validate_parameters(self::check_contrast_parameters(), [
            'color1' => $color1,
            'color2' => $color2
        ]);
        
        $manager = new profiles_manager();
        $ratio = $manager->calculate_contrast_ratio($params['color1'], $params['color2']);
        
        return [
            'ratio' => $ratio,
            'wcag_aa' => $ratio >= 4.5,
            'wcag_aa_large' => $ratio >= 3,
            'wcag_aaa' => $ratio >= 7,
            'wcag_aaa_large' => $ratio >= 4.5
        ];
    }
    
    /**
     * Return structure for check_contrast
     * @return external_single_structure
     */
    public static function check_contrast_returns() {
        return new external_single_structure([
            'ratio' => new external_value(PARAM_FLOAT, 'Contrast ratio'),
            'wcag_aa' => new external_value(PARAM_BOOL, 'Meets WCAG AA standard'),
            'wcag_aa_large' => new external_value(PARAM_BOOL, 'Meets WCAG AA for large text'),
            'wcag_aaa' => new external_value(PARAM_BOOL, 'Meets WCAG AAA standard'),
            'wcag_aaa_large' => new external_value(PARAM_BOOL, 'Meets WCAG AAA for large text')
        ]);
    }
}