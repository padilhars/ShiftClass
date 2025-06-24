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
 * Profile data source for ShiftClass theme
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_shiftclass\datasource;

defined('MOODLE_INTERNAL') || die();

use cache;

/**
 * Data source for visual profiles with optimized loading
 */
class profile_datasource {
    
    /** @var cache Cache instance */
    private $cache;
    
    /** @var array Cached profiles */
    private static $profiles = null;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->cache = cache::make('theme_shiftclass', 'profiles');
    }
    
    /**
     * Get all profiles with optimized loading
     * 
     * @param bool $refresh Force refresh from database
     * @return array Array of profiles
     */
    public function get_all_profiles($refresh = false) {
        global $DB;
        
        // Use static cache first
        if (!$refresh && self::$profiles !== null) {
            return self::$profiles;
        }
        
        // Try persistent cache
        if (!$refresh) {
            $cached = $this->cache->get('all_profiles');
            if ($cached !== false) {
                self::$profiles = $cached;
                return $cached;
            }
        }
        
        // Load from database with optimized query
        $sql = "SELECT p.*, 
                       COUNT(DISTINCT cp.courseid) as usage_count,
                       MAX(cp.timemodified) as last_used
                FROM {theme_shiftclass_profiles} p
                LEFT JOIN {theme_shiftclass_course_profiles} cp ON cp.profileid = p.id
                GROUP BY p.id, p.name, p.primarycolor, p.secondarycolor, 
                         p.backgroundcolor, p.defaultheaderimage, 
                         p.timecreated, p.timemodified, p.usermodified
                ORDER BY p.name ASC";
        
        $profiles = $DB->get_records_sql($sql);
        
        // Store in caches
        self::$profiles = $profiles;
        $this->cache->set('all_profiles', $profiles);
        
        return $profiles;
    }
    
    /**
     * Get profiles for select menu
     * 
     * @param bool $includenone Include 'No profile' option
     * @return array Array suitable for select menus
     */
    public function get_profiles_menu($includenone = true) {
        $profiles = $this->get_all_profiles();
        $menu = [];
        
        if ($includenone) {
            $menu[0] = get_string('novisualprofile', 'theme_shiftclass');
        }
        
        foreach ($profiles as $profile) {
            $menu[$profile->id] = format_string($profile->name);
        }
        
        return $menu;
    }
    
    /**
     * Get profiles with usage statistics
     * 
     * @return array Array of profiles with stats
     */
    public function get_profiles_with_stats() {
        global $DB;
        
        $profiles = $this->get_all_profiles();
        
        // Get detailed usage stats
        $sql = "SELECT cp.profileid, 
                       COUNT(DISTINCT c.id) as course_count,
                       COUNT(DISTINCT c.category) as category_count,
                       COUNT(DISTINCT ue.userid) as user_count
                FROM {theme_shiftclass_course_profiles} cp
                JOIN {course} c ON c.id = cp.courseid
                LEFT JOIN {enrol} e ON e.courseid = c.id
                LEFT JOIN {user_enrolments} ue ON ue.enrolid = e.id AND ue.status = 0
                GROUP BY cp.profileid";
        
        $stats = $DB->get_records_sql($sql);
        
        // Merge stats with profiles
        foreach ($profiles as $profile) {
            if (isset($stats[$profile->id])) {
                $profile->course_count = $stats[$profile->id]->course_count;
                $profile->category_count = $stats[$profile->id]->category_count;
                $profile->user_count = $stats[$profile->id]->user_count;
            } else {
                $profile->course_count = 0;
                $profile->category_count = 0;
                $profile->user_count = 0;
            }
        }
        
        return $profiles;
    }
    
    /**
     * Get most used profiles
     * 
     * @param int $limit Number of profiles to return
     * @return array Array of most used profiles
     */
    public function get_most_used_profiles($limit = 5) {
        $profiles = $this->get_profiles_with_stats();
        
        // Sort by usage
        usort($profiles, function($a, $b) {
            return $b->course_count - $a->course_count;
        });
        
        return array_slice($profiles, 0, $limit);
    }
    
    /**
     * Get courses using a specific profile
     * 
     * @param int $profileid Profile ID
     * @param int $limit Limit number of courses
     * @return array Array of courses
     */
    public function get_courses_using_profile($profileid, $limit = 0) {
        global $DB;
        
        $sql = "SELECT c.id, c.fullname, c.shortname, c.category, 
                       cat.name as categoryname,
                       cp.timecreated as profile_assigned
                FROM {course} c
                JOIN {theme_shiftclass_course_profiles} cp ON cp.courseid = c.id
                JOIN {course_categories} cat ON cat.id = c.category
                WHERE cp.profileid = :profileid
                ORDER BY c.fullname ASC";
        
        $params = ['profileid' => $profileid];
        
        if ($limit > 0) {
            return $DB->get_records_sql($sql, $params, 0, $limit);
        } else {
            return $DB->get_records_sql($sql, $params);
        }
    }
    
    /**
     * Search profiles by name or color
     * 
     * @param string $query Search query
     * @return array Matching profiles
     */
    public function search_profiles($query) {
        $query = strtolower(trim($query));
        if (empty($query)) {
            return $this->get_all_profiles();
        }
        
        $profiles = $this->get_all_profiles();
        $results = [];
        
        foreach ($profiles as $profile) {
            $name = strtolower($profile->name);
            $match = false;
            
            // Search in name
            if (strpos($name, $query) !== false) {
                $match = true;
            }
            
            // Search in colors
            if (!$match && (
                strpos($profile->primarycolor, $query) !== false ||
                strpos($profile->secondarycolor, $query) !== false ||
                strpos($profile->backgroundcolor, $query) !== false
            )) {
                $match = true;
            }
            
            if ($match) {
                $results[] = $profile;
            }
        }
        
        return $results;
    }
    
    /**
     * Prefetch profiles for multiple courses
     * 
     * @param array $courseids Array of course IDs
     * @return array Associative array of courseid => profile
     */
    public function prefetch_course_profiles($courseids) {
        global $DB;
        
        if (empty($courseids)) {
            return [];
        }
        
        // Check cache first
        $cached = [];
        $missing = [];
        
        foreach ($courseids as $courseid) {
            $cachekey = 'course_profile_' . $courseid;
            $profile = $this->cache->get($cachekey);
            
            if ($profile !== false) {
                $cached[$courseid] = $profile;
            } else {
                $missing[] = $courseid;
            }
        }
        
        // Load missing from database
        if (!empty($missing)) {
            list($insql, $params) = $DB->get_in_or_equal($missing, SQL_PARAMS_NAMED);
            
            $sql = "SELECT cp.courseid, p.*
                    FROM {theme_shiftclass_course_profiles} cp
                    JOIN {theme_shiftclass_profiles} p ON p.id = cp.profileid
                    WHERE cp.courseid $insql";
            
            $results = $DB->get_records_sql($sql, $params);
            
            foreach ($results as $result) {
                $courseid = $result->courseid;
                unset($result->courseid);
                
                $cached[$courseid] = $result;
                
                // Store in cache
                $cachekey = 'course_profile_' . $courseid;
                $this->cache->set($cachekey, $result);
            }
        }
        
        return $cached;
    }
    
    /**
     * Clear all profile caches
     */
    public function clear_cache() {
        self::$profiles = null;
        $this->cache->purge();
        theme_reset_all_caches();
    }
    
    /**
     * Get profile statistics summary
     * 
     * @return stdClass Statistics object
     */
    public function get_statistics_summary() {
        global $DB;
        
        $stats = new \stdClass();
        
        // Total profiles
        $stats->total_profiles = $DB->count_records('theme_shiftclass_profiles');
        
        // Profiles in use
        $sql = "SELECT COUNT(DISTINCT profileid) FROM {theme_shiftclass_course_profiles}";
        $stats->profiles_in_use = $DB->count_records_sql($sql);
        
        // Total courses using profiles
        $stats->total_courses = $DB->count_records('theme_shiftclass_course_profiles');
        
        // Most popular profile
        $sql = "SELECT p.id, p.name, COUNT(cp.courseid) as count
                FROM {theme_shiftclass_profiles} p
                JOIN {theme_shiftclass_course_profiles} cp ON cp.profileid = p.id
                GROUP BY p.id, p.name
                ORDER BY count DESC
                LIMIT 1";
        
        $popular = $DB->get_record_sql($sql);
        $stats->most_popular = $popular ? $popular : null;
        
        return $stats;
    }
}