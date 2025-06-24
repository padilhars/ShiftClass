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
 * Installation script for ShiftClass theme
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Post-install script
 * 
 * @return bool
 */
function xmldb_theme_shiftclass_install() {
    global $DB, $CFG;
    
    // Include the profiles manager
    require_once($CFG->dirroot . '/theme/shiftclass/classes/profiles_manager.php');
    
    try {
        // Create instance of profiles manager
        $manager = new \theme_shiftclass\profiles_manager();
        
        // Install default profiles
        $manager->install_default_profiles();
        
        mtrace('ShiftClass theme: Default visual profiles installed successfully.');
        
        // Set default theme settings
        set_config('preset', 'default.scss', 'theme_shiftclass');
        set_config('brandcolor', '#0f6cbf', 'theme_shiftclass');
        
        mtrace('ShiftClass theme: Default settings configured.');
        
        // Clear all caches
        theme_reset_all_caches();
        purge_all_caches();
        
        mtrace('ShiftClass theme: Caches cleared.');
        
        return true;
        
    } catch (Exception $e) {
        mtrace('ShiftClass theme installation error: ' . $e->getMessage());
        debugging($e->getMessage(), DEBUG_DEVELOPER);
        
        // Installation can continue even if default profiles fail
        return true;
    }
}