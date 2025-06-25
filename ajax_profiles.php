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
 * AJAX endpoint for visual profiles operations
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

use theme_shiftclass\profiles_manager;

// Check permissions
require_login();
require_capability('theme/shiftclass:manageprofiles', context_system::instance());
require_sesskey();

// Get action
$action = required_param('action', PARAM_ALPHA);

// Initialize profiles manager
$manager = new profiles_manager();

// Rate limiting - simple implementation
$sessionkey = 'theme_shiftclass_ajax_' . $USER->id;
$lastaccess = $SESSION->$sessionkey ?? 0;
$now = time();

if ($now - $lastaccess < 1) { // Maximum 1 request per second
    http_response_code(429);
    echo json_encode(['success' => false, 'error' => 'Too many requests']);
    exit;
}

$SESSION->$sessionkey = $now;

// Set JSON header
header('Content-Type: application/json');

try {
    $response = ['success' => false];
    
    switch ($action) {
        case 'getprofile':
            $profileid = required_param('profileid', PARAM_INT);
            $profile = $manager->get_profile($profileid);
            
            if ($profile) {
                $response['success'] = true;
                $response['profile'] = $profile;
            } else {
                $response['error'] = get_string('error:profilenotfound', 'theme_shiftclass');
            }
            break;
            
        case 'checkcontrast':
            $primary = required_param('primary', PARAM_TEXT);
            $background = required_param('background', PARAM_TEXT);
            
            $ratio = $manager->calculate_contrast_ratio($primary, $background);
            
            $response['success'] = true;
            $response['ratio'] = $ratio;
            $response['wcag_aa'] = $ratio >= 4.5;
            $response['wcag_aa_large'] = $ratio >= 3;
            $response['wcag_aaa'] = $ratio >= 7;
            break;
            
        case 'getusage':
            $profileid = required_param('profileid', PARAM_INT);
            $count = $manager->get_profile_usage_count($profileid);
            
            $response['success'] = true;
            $response['count'] = $count;
            
            // Get list of courses using this profile
            if ($count > 0) {
                global $DB;
                $sql = "SELECT c.id, c.fullname, c.shortname
                        FROM {course} c
                        JOIN {theme_shiftclass_course_profiles} cp ON cp.courseid = c.id
                        WHERE cp.profileid = ?
                        ORDER BY c.fullname";
                $courses = $DB->get_records_sql($sql, [$profileid], 0, 10);
                $response['courses'] = array_values($courses);
                $response['has_more'] = $count > 10;
            }
            break;
            
        case 'validatename':
            $name = required_param('name', PARAM_TEXT);
            $excludeid = optional_param('excludeid', 0, PARAM_INT);
            
            global $DB;
            $params = ['name' => $name];
            $sql = "SELECT id FROM {theme_shiftclass_profiles} WHERE name = :name";
            
            if ($excludeid > 0) {
                $sql .= " AND id != :excludeid";
                $params['excludeid'] = $excludeid;
            }
            
            $exists = $DB->record_exists_sql($sql, $params);
            
            $response['success'] = true;
            $response['available'] = !$exists;
            break;
            
        case 'quickcreate':
            // Quick create profile from modal
            $data = new stdClass();
            $data->name = required_param('name', PARAM_TEXT);
            $data->primarycolor = required_param('primarycolor', PARAM_TEXT);
            $data->secondarycolor = required_param('secondarycolor', PARAM_TEXT);
            $data->backgroundcolor = required_param('backgroundcolor', PARAM_TEXT);
            
            try {
                $profileid = $manager->create_profile($data);
                $response['success'] = true;
                $response['profileid'] = $profileid;
                $response['message'] = get_string('profilecreated', 'theme_shiftclass');
            } catch (moodle_exception $e) {
                $response['error'] = $e->getMessage();
            }
            break;
            
        case 'exportall':
            // Export all profiles
            $profiles = $manager->get_all_profiles();
            $export = [];
            
            foreach ($profiles as $profile) {
                $export[] = [
                    'name' => $profile->name,
                    'primarycolor' => $profile->primarycolor,
                    'secondarycolor' => $profile->secondarycolor,
                    'backgroundcolor' => $profile->backgroundcolor,
                    'defaultheaderimage' => $profile->defaultheaderimage
                ];
            }
            
            $response['success'] = true;
            $response['profiles'] = $export;
            $response['count'] = count($export);
            $response['exported_date'] = userdate(time());
            break;
            
        case 'preview':
            // Generate preview CSS for a profile
            $profileid = required_param('profileid', PARAM_INT);
            $profile = $manager->get_profile($profileid);
            
            if ($profile) {
                $css = theme_shiftclass_generate_profile_css($profile);
                $response['success'] = true;
                $response['css'] = $css;
                $response['profile'] = $profile;
            } else {
                $response['error'] = get_string('error:profilenotfound', 'theme_shiftclass');
            }
            break;
            
        default:
            $response['error'] = 'Invalid action';
            break;
    }
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
    
    // Log the error
    debugging('AJAX error in theme_shiftclass: ' . $e->getMessage(), DEBUG_DEVELOPER);
}

// Output response
echo json_encode($response);

/**
 * Generate CSS for profile preview
 * 
 * @param stdClass $profile Profile object
 * @return string CSS
 */
function theme_shiftclass_generate_profile_css($profile) {
    $css = ":root {\n";
    $css .= "    --shiftclass-primary: {$profile->primarycolor};\n";
    $css .= "    --shiftclass-secondary: {$profile->secondarycolor};\n";
    $css .= "    --shiftclass-background: {$profile->backgroundcolor};\n";
    $css .= "}\n\n";
    
    $css .= ".navbar { background-color: var(--shiftclass-primary) !important; }\n";
    $css .= ".btn-primary { background-color: var(--shiftclass-primary); border-color: var(--shiftclass-primary); }\n";
    $css .= ".btn-secondary { background-color: var(--shiftclass-secondary); border-color: var(--shiftclass-secondary); }\n";
    $css .= "body, #page { background-color: var(--shiftclass-background); }\n";
    
    return $css;
}
