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
 * Theme ShiftClass upgrade script
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute theme_shiftclass upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_theme_shiftclass_upgrade($oldversion) {
    global $DB;
    
    $dbman = $DB->get_manager();
    
    // Placeholder for future upgrade steps
    // Each upgrade step should check the old version and apply necessary changes
    
    // Example structure for future upgrades:
    /*
    if ($oldversion < 2025012200) {
        // Define new field to be added
        $table = new xmldb_table('theme_shiftclass_profiles');
        $field = new xmldb_field('newfield', XMLDB_TYPE_CHAR, '100', null, null, null, null, 'backgroundcolor');
        
        // Conditionally launch add field
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        
        // Update savepoint
        upgrade_plugin_savepoint(true, 2025012200, 'theme', 'shiftclass');
    }
    */
    
    return true;
}