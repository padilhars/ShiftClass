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
 * Visual Profiles Manager for ShiftClass theme
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_shiftclass;

defined('MOODLE_INTERNAL') || die();

use stdClass;
use moodle_exception;
use cache;
use context_system;

/**
 * Class for managing visual profiles
 */
class profiles_manager {
    
    /** @var string Cache key for profiles */
    const CACHE_KEY_PROFILES = 'theme_shiftclass_profiles';
    
    /** @var string Cache key for course profiles */
    const CACHE_KEY_COURSE_PROFILES = 'theme_shiftclass_course_profiles';
    
    /** @var int Maximum profile name length */
    const MAX_NAME_LENGTH = 50;
    
    /** @var cache Cache instance */
    private $cache;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->cache = cache::make('theme_shiftclass', 'profiles');
    }
    
    /**
     * Get all visual profiles
     * 
     * @return array Array of profile objects
     */
    public function get_all_profiles() {
        global $DB;
        
        // Try to get from cache first
        $profiles = $this->cache->get(self::CACHE_KEY_PROFILES);
        
        if ($profiles === false) {
            // Not in cache, get from database
            $profiles = $DB->get_records('theme_shiftclass_profiles', null, 'name ASC');
            
            // Store in cache
            $this->cache->set(self::CACHE_KEY_PROFILES, $profiles);
        }
        
        return $profiles;
    }
    
    /**
     * Get a single profile by ID
     * 
     * @param int $profileid Profile ID
     * @return stdClass|false Profile object or false if not found
     */
    public function get_profile($profileid) {
        global $DB;
        
        $profile = $DB->get_record('theme_shiftclass_profiles', ['id' => $profileid]);
        
        if (!$profile) {
            return false;
        }
        
        return $profile;
    }
    
    /**
     * Create a new visual profile
     * 
     * @param stdClass $data Profile data
     * @return int New profile ID
     * @throws moodle_exception
     */
    public function create_profile($data) {
        global $DB, $USER;
        
        // Validate data
        $this->validate_profile_data($data);
        
        // Check for duplicate name
        if ($this->profile_name_exists($data->name)) {
            throw new moodle_exception('error:duplicateprofilename', 'theme_shiftclass');
        }
        
        // Prepare record
        $record = new stdClass();
        $record->name = clean_param($data->name, PARAM_TEXT);
        $record->primarycolor = $this->validate_color($data->primarycolor);
        $record->secondarycolor = $this->validate_color($data->secondarycolor);
        $record->backgroundcolor = $this->validate_color($data->backgroundcolor);
        $record->defaultheaderimage = !empty($data->defaultheaderimage) ? clean_param($data->defaultheaderimage, PARAM_URL) : null;
        $record->timecreated = time();
        $record->timemodified = time();
        $record->usermodified = $USER->id;
        
        // Insert into database
        $profileid = $DB->insert_record('theme_shiftclass_profiles', $record);
        
        // Clear cache
        $this->clear_cache();
        
        // Trigger event
        $event = \theme_shiftclass\event\profile_created::create([
            'objectid' => $profileid,
            'context' => context_system::instance(),
            'other' => ['name' => $record->name]
        ]);
        $event->trigger();
        
        return $profileid;
    }
    
    /**
     * Update an existing visual profile
     * 
     * @param int $profileid Profile ID
     * @param stdClass $data Profile data
     * @return bool Success
     * @throws moodle_exception
     */
    public function update_profile($profileid, $data) {
        global $DB, $USER;
        
        // Check if profile exists
        $profile = $this->get_profile($profileid);
        if (!$profile) {
            throw new moodle_exception('error:profilenotfound', 'theme_shiftclass');
        }
        
        // Validate data
        $this->validate_profile_data($data);
        
        // Check for duplicate name (excluding current profile)
        if ($this->profile_name_exists($data->name, $profileid)) {
            throw new moodle_exception('error:duplicateprofilename', 'theme_shiftclass');
        }
        
        // Update record
        $profile->name = clean_param($data->name, PARAM_TEXT);
        $profile->primarycolor = $this->validate_color($data->primarycolor);
        $profile->secondarycolor = $this->validate_color($data->secondarycolor);
        $profile->backgroundcolor = $this->validate_color($data->backgroundcolor);
        $profile->defaultheaderimage = !empty($data->defaultheaderimage) ? clean_param($data->defaultheaderimage, PARAM_URL) : null;
        $profile->timemodified = time();
        $profile->usermodified = $USER->id;
        
        // Update in database
        $result = $DB->update_record('theme_shiftclass_profiles', $profile);
        
        // Clear cache
        $this->clear_cache();
        
        // Trigger event
        $event = \theme_shiftclass\event\profile_updated::create([
            'objectid' => $profileid,
            'context' => context_system::instance(),
            'other' => ['name' => $profile->name]
        ]);
        $event->trigger();
        
        return $result;
    }
    
    /**
     * Delete a visual profile
     * 
     * @param int $profileid Profile ID
     * @return bool Success
     * @throws moodle_exception
     */
    public function delete_profile($profileid) {
        global $DB;
        
        // Check if profile exists
        $profile = $this->get_profile($profileid);
        if (!$profile) {
            throw new moodle_exception('error:profilenotfound', 'theme_shiftclass');
        }
        
        // Check if profile is in use
        if ($this->profile_in_use($profileid)) {
            throw new moodle_exception('error:profileinuse', 'theme_shiftclass');
        }
        
        // Delete profile
        $result = $DB->delete_records('theme_shiftclass_profiles', ['id' => $profileid]);
        
        // Clear cache
        $this->clear_cache();
        
        // Trigger event
        $event = \theme_shiftclass\event\profile_deleted::create([
            'objectid' => $profileid,
            'context' => context_system::instance(),
            'other' => ['name' => $profile->name]
        ]);
        $event->trigger();
        
        return $result;
    }
    
    /**
     * Check if a profile is in use by any course
     * 
     * @param int $profileid Profile ID
     * @return bool True if in use
     */
    public function profile_in_use($profileid) {
        global $DB;
        
        return $DB->record_exists('theme_shiftclass_course_profiles', ['profileid' => $profileid]);
    }
    
    /**
     * Get the number of courses using a profile
     * 
     * @param int $profileid Profile ID
     * @return int Number of courses
     */
    public function get_profile_usage_count($profileid) {
        global $DB;
        
        return $DB->count_records('theme_shiftclass_course_profiles', ['profileid' => $profileid]);
    }
    
    /**
     * Assign a profile to a course
     * 
     * @param int $courseid Course ID
     * @param int $profileid Profile ID (0 to remove)
     * @return bool Success
     */
    public function assign_profile_to_course($courseid, $profileid) {
        global $DB, $USER;
        
        // Delete existing assignment
        $DB->delete_records('theme_shiftclass_course_profiles', ['courseid' => $courseid]);
        
        if ($profileid > 0) {
            // Check if profile exists
            if (!$this->get_profile($profileid)) {
                throw new moodle_exception('error:profilenotfound', 'theme_shiftclass');
            }
            
            // Create new assignment
            $record = new stdClass();
            $record->courseid = $courseid;
            $record->profileid = $profileid;
            $record->timecreated = time();
            $record->timemodified = time();
            $record->usermodified = $USER->id;
            
            $DB->insert_record('theme_shiftclass_course_profiles', $record);
        }
        
        // Clear course profile cache
        $cachekey = self::CACHE_KEY_COURSE_PROFILES . '_' . $courseid;
        $this->cache->delete($cachekey);
        
        return true;
    }
    
    /**
     * Get the profile assigned to a course
     * 
     * @param int $courseid Course ID
     * @return stdClass|false Profile object or false if none
     */
    public function get_course_profile($courseid) {
        global $DB;
        
        // Try cache first
        $cachekey = self::CACHE_KEY_COURSE_PROFILES . '_' . $courseid;
        $profile = $this->cache->get($cachekey);
        
        if ($profile === false) {
            // Not in cache, get from database
            $sql = "SELECT p.*
                    FROM {theme_shiftclass_profiles} p
                    JOIN {theme_shiftclass_course_profiles} cp ON cp.profileid = p.id
                    WHERE cp.courseid = ?";
            
            $profile = $DB->get_record_sql($sql, [$courseid]);
            
            // Store in cache (even if false)
            $this->cache->set($cachekey, $profile);
        }
        
        return $profile;
    }
    
    /**
     * Validate profile data
     * 
     * @param stdClass $data Profile data
     * @throws moodle_exception
     */
    private function validate_profile_data($data) {
        // Validate name
        if (empty($data->name)) {
            throw new moodle_exception('error:profilenamerequired', 'theme_shiftclass');
        }
        
        if (strlen($data->name) > self::MAX_NAME_LENGTH) {
            throw new moodle_exception('error:profilenametoolong', 'theme_shiftclass');
        }
        
        // Validate colors
        if (empty($data->primarycolor)) {
            throw new moodle_exception('error:primarycolorrequired', 'theme_shiftclass');
        }
        
        if (empty($data->secondarycolor)) {
            throw new moodle_exception('error:secondarycolorrequired', 'theme_shiftclass');
        }
        
        if (empty($data->backgroundcolor)) {
            throw new moodle_exception('error:backgroundcolorrequired', 'theme_shiftclass');
        }
        
        // Validate color formats
        $this->validate_color($data->primarycolor);
        $this->validate_color($data->secondarycolor);
        $this->validate_color($data->backgroundcolor);
    }
    
    /**
     * Validate color format
     * 
     * @param string $color Color value
     * @return string Validated color
     * @throws moodle_exception
     */
    private function validate_color($color) {
        $color = trim($color);
        
        // Check hexadecimal format
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
            throw new moodle_exception('error:invalidcolor', 'theme_shiftclass');
        }
        
        return strtoupper($color);
    }
    
    /**
     * Check if profile name exists
     * 
     * @param string $name Profile name
     * @param int $excludeid Profile ID to exclude (for updates)
     * @return bool
     */
    private function profile_name_exists($name, $excludeid = 0) {
        global $DB;
        
        $params = ['name' => $name];
        $sql = "SELECT id FROM {theme_shiftclass_profiles} WHERE name = :name";
        
        if ($excludeid > 0) {
            $sql .= " AND id != :excludeid";
            $params['excludeid'] = $excludeid;
        }
        
        return $DB->record_exists_sql($sql, $params);
    }
    
    /**
     * Clear all profile-related caches
     */
    private function clear_cache() {
        $this->cache->delete(self::CACHE_KEY_PROFILES);
        theme_reset_all_caches();
    }
    
    /**
     * Install default profiles
     * 
     * @return bool Success
     */
    public function install_default_profiles() {
        $defaults = [
            [
                'name' => get_string('profile:corporate_blue', 'theme_shiftclass'),
                'primarycolor' => '#0066CC',
                'secondarycolor' => '#004499',
                'backgroundcolor' => '#F0F5FF'
            ],
            [
                'name' => get_string('profile:nature_green', 'theme_shiftclass'),
                'primarycolor' => '#228B22',
                'secondarycolor' => '#006400',
                'backgroundcolor' => '#F0FFF0'
            ],
            [
                'name' => get_string('profile:modern_purple', 'theme_shiftclass'),
                'primarycolor' => '#6A4C93',
                'secondarycolor' => '#483D8B',
                'backgroundcolor' => '#F5F0FF'
            ],
            [
                'name' => get_string('profile:dynamic_orange', 'theme_shiftclass'),
                'primarycolor' => '#FF6B35',
                'secondarycolor' => '#E55100',
                'backgroundcolor' => '#FFF5F0'
            ]
        ];
        
        foreach ($defaults as $default) {
            try {
                $data = (object)$default;
                $this->create_profile($data);
            } catch (moodle_exception $e) {
                // Profile might already exist, continue
                continue;
            }
        }
        
        return true;
    }
    
    /**
     * Check color contrast ratio for WCAG compliance
     * 
     * @param string $color1 First color (hex)
     * @param string $color2 Second color (hex)
     * @return float Contrast ratio
     */
    public function calculate_contrast_ratio($color1, $color2) {
        // Convert hex to RGB
        $rgb1 = $this->hex_to_rgb($color1);
        $rgb2 = $this->hex_to_rgb($color2);
        
        // Calculate relative luminance
        $l1 = $this->calculate_luminance($rgb1);
        $l2 = $this->calculate_luminance($rgb2);
        
        // Calculate contrast ratio
        $ratio = ($l1 > $l2) ? ($l1 + 0.05) / ($l2 + 0.05) : ($l2 + 0.05) / ($l1 + 0.05);
        
        return round($ratio, 2);
    }
    
    /**
     * Convert hex color to RGB array
     * 
     * @param string $hex Hex color
     * @return array RGB values
     */
    private function hex_to_rgb($hex) {
        $hex = str_replace('#', '', $hex);
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        ];
    }
    
    /**
     * Calculate relative luminance
     * 
     * @param array $rgb RGB values
     * @return float Luminance
     */
    private function calculate_luminance($rgb) {
        $rgb = array_map(function($val) {
            $val = $val / 255;
            return ($val <= 0.03928) ? $val / 12.92 : pow(($val + 0.055) / 1.055, 2.4);
        }, $rgb);
        
        return 0.2126 * $rgb['r'] + 0.7152 * $rgb['g'] + 0.0722 * $rgb['b'];
    }
}