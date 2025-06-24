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
 * Privacy provider for ShiftClass theme
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_shiftclass\privacy;

defined('MOODLE_INTERNAL') || die();

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;
use core_privacy\local\request\helper;

/**
 * Privacy provider class
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\core_userlist_provider,
    \core_privacy\local\request\plugin\provider {
    
    /**
     * Returns metadata about this plugin's privacy policy
     *
     * @param collection $collection The collection to add metadata to
     * @return collection The updated collection
     */
    public static function get_metadata(collection $collection) : collection {
        // Profile creation/modification data
        $collection->add_database_table(
            'theme_shiftclass_profiles',
            [
                'usermodified' => 'privacy:metadata:profiles:usermodified',
                'timecreated' => 'privacy:metadata:profiles:timecreated',
                'timemodified' => 'privacy:metadata:profiles:timemodified'
            ],
            'privacy:metadata:profiles'
        );
        
        // Course profile assignment data
        $collection->add_database_table(
            'theme_shiftclass_course_profiles',
            [
                'usermodified' => 'privacy:metadata:courseprofiles:usermodified',
                'timecreated' => 'privacy:metadata:courseprofiles:timecreated',
                'timemodified' => 'privacy:metadata:courseprofiles:timemodified'
            ],
            'privacy:metadata:courseprofiles'
        );
        
        // User preferences
        $collection->add_user_preference(
            'theme_shiftclass_highcontrast',
            'privacy:metadata:preference:highcontrast'
        );
        
        $collection->add_user_preference(
            'theme_shiftclass_reducedmotion',
            'privacy:metadata:preference:reducedmotion'
        );
        
        return $collection;
    }
    
    /**
     * Get the list of contexts that contain user information
     *
     * @param int $userid The user to search
     * @return contextlist The contextlist containing the user's data
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        $contextlist = new contextlist();
        
        // System context for profile management
        $sql = "SELECT DISTINCT ctx.id
                FROM {context} ctx
                WHERE ctx.contextlevel = :contextlevel
                AND (EXISTS (SELECT 1 FROM {theme_shiftclass_profiles} WHERE usermodified = :userid1)
                    OR EXISTS (SELECT 1 FROM {theme_shiftclass_course_profiles} WHERE usermodified = :userid2))";
        
        $params = [
            'contextlevel' => CONTEXT_SYSTEM,
            'userid1' => $userid,
            'userid2' => $userid
        ];
        
        $contextlist->add_from_sql($sql, $params);
        
        return $contextlist;
    }
    
    /**
     * Get the list of users within a specific context
     *
     * @param userlist $userlist The userlist containing the list of users
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();
        
        if ($context->contextlevel != CONTEXT_SYSTEM) {
            return;
        }
        
        // Users who created/modified profiles
        $sql = "SELECT DISTINCT usermodified FROM {theme_shiftclass_profiles}";
        $userlist->add_from_sql('usermodified', $sql, []);
        
        // Users who assigned profiles to courses
        $sql = "SELECT DISTINCT usermodified FROM {theme_shiftclass_course_profiles}";
        $userlist->add_from_sql('usermodified', $sql, []);
    }
    
    /**
     * Export user data for the specified contexts
     *
     * @param approved_contextlist $contextlist The approved contexts
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;
        
        $userid = $contextlist->get_user()->id;
        
        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel != CONTEXT_SYSTEM) {
                continue;
            }
            
            // Export profiles created/modified by user
            $profiles = $DB->get_records('theme_shiftclass_profiles', ['usermodified' => $userid]);
            if ($profiles) {
                $profiledata = [];
                foreach ($profiles as $profile) {
                    $profiledata[] = [
                        'name' => $profile->name,
                        'primarycolor' => $profile->primarycolor,
                        'secondarycolor' => $profile->secondarycolor,
                        'backgroundcolor' => $profile->backgroundcolor,
                        'timecreated' => transform::datetime($profile->timecreated),
                        'timemodified' => transform::datetime($profile->timemodified)
                    ];
                }
                writer::with_context($context)->export_data(['Visual Profiles Created'], (object)$profiledata);
            }
            
            // Export course profile assignments by user
            $sql = "SELECT cp.*, c.fullname as coursename, p.name as profilename
                    FROM {theme_shiftclass_course_profiles} cp
                    JOIN {course} c ON c.id = cp.courseid
                    JOIN {theme_shiftclass_profiles} p ON p.id = cp.profileid
                    WHERE cp.usermodified = :userid";
            
            $assignments = $DB->get_records_sql($sql, ['userid' => $userid]);
            if ($assignments) {
                $assignmentdata = [];
                foreach ($assignments as $assignment) {
                    $assignmentdata[] = [
                        'course' => $assignment->coursename,
                        'profile' => $assignment->profilename,
                        'timecreated' => transform::datetime($assignment->timecreated),
                        'timemodified' => transform::datetime($assignment->timemodified)
                    ];
                }
                writer::with_context($context)->export_data(['Profile Assignments'], (object)$assignmentdata);
            }
            
            // Export user preferences
            writer::with_context($context)->export_user_preference(
                'theme_shiftclass',
                'highcontrast',
                get_user_preferences('theme_shiftclass_highcontrast', null, $userid),
                get_string('privacy:metadata:preference:highcontrast', 'theme_shiftclass')
            );
            
            writer::with_context($context)->export_user_preference(
                'theme_shiftclass',
                'reducedmotion',
                get_user_preferences('theme_shiftclass_reducedmotion', null, $userid),
                get_string('privacy:metadata:preference:reducedmotion', 'theme_shiftclass')
            );
        }
    }
    
    /**
     * Delete all user data for the specified contexts
     *
     * @param approved_contextlist $contextlist The approved contexts
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;
        
        if ($context->contextlevel != CONTEXT_SYSTEM) {
            return;
        }
        
        // We don't delete profiles or assignments as they are system-wide settings
        // We only anonymize the user references
        $DB->set_field('theme_shiftclass_profiles', 'usermodified', 0, ['usermodified' => '> 0']);
        $DB->set_field('theme_shiftclass_course_profiles', 'usermodified', 0, ['usermodified' => '> 0']);
    }
    
    /**
     * Delete user data for the specified user and contexts
     *
     * @param approved_contextlist $contextlist The approved contexts
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;
        
        $userid = $contextlist->get_user()->id;
        
        foreach ($contextlist->get_contexts() as $context) {
            if ($context->contextlevel != CONTEXT_SYSTEM) {
                continue;
            }
            
            // Anonymize user references
            $DB->set_field('theme_shiftclass_profiles', 'usermodified', 0, ['usermodified' => $userid]);
            $DB->set_field('theme_shiftclass_course_profiles', 'usermodified', 0, ['usermodified' => $userid]);
            
            // Delete user preferences
            unset_user_preference('theme_shiftclass_highcontrast', $userid);
            unset_user_preference('theme_shiftclass_reducedmotion', $userid);
        }
    }
    
    /**
     * Delete user data for the specified users in the specified context
     *
     * @param approved_userlist $userlist The approved context and users
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;
        
        $context = $userlist->get_context();
        
        if ($context->contextlevel != CONTEXT_SYSTEM) {
            return;
        }
        
        $userids = $userlist->get_userids();
        
        if (empty($userids)) {
            return;
        }
        
        list($usersql, $userparams) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);
        
        // Anonymize user references
        $DB->set_field_select('theme_shiftclass_profiles', 'usermodified', 0, "usermodified $usersql", $userparams);
        $DB->set_field_select('theme_shiftclass_course_profiles', 'usermodified', 0, "usermodified $usersql", $userparams);
        
        // Delete user preferences
        foreach ($userids as $userid) {
            unset_user_preference('theme_shiftclass_highcontrast', $userid);
            unset_user_preference('theme_shiftclass_reducedmotion', $userid);
        }
    }
}