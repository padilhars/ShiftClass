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
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_shiftclass\output;

use stdClass;
use context_course;

defined('MOODLE_INTERNAL') || die;

/**
 * Extending the core_renderer interface.
 *
 * @copyright 2025 Rodrigo Padilha Silveira
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package theme_shiftclass
 * @category output
 */
class core_renderer extends \theme_boost\output\core_renderer {

    /**
     * Override to add visual profile CSS variables when needed
     * Currently maintains Boost behavior - will be enhanced in Phase 2
     * 
     * @return string HTML to output.
     */
    public function standard_head_html() {
        global $CFG, $COURSE, $PAGE;
        
        $output = parent::standard_head_html();
        
        // Stub: In Phase 2, this will add dynamic CSS for visual profiles
        // For now, we maintain exact Boost behavior
        if ($PAGE->context->contextlevel == CONTEXT_COURSE && $COURSE->id != SITEID) {
            $profilecss = $this->get_visual_profile_css($COURSE->id);
            if (!empty($profilecss)) {
                $output .= $profilecss;
            }
        }
        
        return $output;
    }
    
    /**
     * Get visual profile CSS for a course
     * Stub for Phase 2 implementation
     * 
     * @param int $courseid
     * @return string CSS content
     */
    protected function get_visual_profile_css($courseid) {
        // Stub: This will be implemented in Phase 2
        // Will generate CSS variables based on the course's assigned visual profile
        return '';
    }
}