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

use coding_exception;
use html_writer;
use tabobject;
use tabtree;
use custom_menu_item;
use custom_menu;
use block_contents;
use navigation_node;
use action_link;
use stdClass;
use moodle_url;
use preferences_groups;
use action_menu;
use help_icon;
use single_button;
use single_select;
use paging_bar;
use url_select;
use context_course;
use pix_icon;
use theme_config;

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
     * Override to add visual profile CSS variables
     * @return string HTML to output.
     */
    public function standard_head_html() {
        global $CFG, $COURSE, $PAGE;
        
        $output = parent::standard_head_html();
        
        // Add visual profile CSS variables if in a course context
        if ($PAGE->context->contextlevel == CONTEXT_COURSE && $COURSE->id != SITEID) {
            $profilecss = $this->get_visual_profile_css($COURSE->id);
            if (!empty($profilecss)) {
                $output .= html_writer::tag('style', $profilecss, array('type' => 'text/css'));
            }
        }
        
        return $output;
    }
    
    /**
     * Get visual profile CSS for a course
     * Stub for future implementation
     * 
     * @param int $courseid
     * @return string CSS content
     */
    protected function get_visual_profile_css($courseid) {
        global $DB;
        
        // Stub: This will be implemented in Phase 3
        // Will generate CSS variables based on the course's assigned visual profile
        
        return '';
    }
    
    /**
     * Override to add course header image
     * @return string HTML to output.
     */
    public function full_header() {
        global $COURSE, $PAGE;
        
        $header = parent::full_header();
        
        // Add course header image if available and in course context
        if ($PAGE->context->contextlevel == CONTEXT_COURSE && $COURSE->id != SITEID) {
            $courseheader = $this->get_course_header_image($COURSE);
            if (!empty($courseheader)) {
                // Insert course header before the main header content
                $header = $courseheader . $header;
            }
        }
        
        return $header;
    }
    
    /**
     * Get course header image HTML
     * Stub for future implementation
     * 
     * @param stdClass $course
     * @return string HTML content
     */
    protected function get_course_header_image($course) {
        global $CFG, $OUTPUT;
        
        // Stub: This will be implemented in Phase 3
        // Will display course image with gradient overlay and course info
        
        return '';
    }
    
    /**
     * Override to add accessibility improvements
     * @return string HTML to output.
     */
    public function render_from_template($templatename, $context) {
        // Add accessibility data where needed
        if (is_object($context) || is_array($context)) {
            $this->add_accessibility_attributes($context);
        }
        
        return parent::render_from_template($templatename, $context);
    }
    
    /**
     * Add accessibility attributes to template context
     * 
     * @param mixed $context Template context
     */
    protected function add_accessibility_attributes(&$context) {
        // Stub: Will add WCAG 2.1 compliance attributes
        // This includes proper ARIA labels, roles, and other accessibility features
    }
    
    /**
     * Override to support high contrast mode
     * @return string
     */
    public function body_attributes($additionalclasses = array()) {
        global $USER;
        
        $classes = parent::body_attributes($additionalclasses);
        
        // Check for user preference for high contrast mode
        if (!empty($USER->id)) {
            $highcontrast = get_user_preferences('theme_shiftclass_highcontrast', 0);
            if ($highcontrast) {
                $classes = str_replace('class="', 'class="shiftclass-high-contrast ', $classes);
            }
            
            // Check for reduced motion preference
            $reducedmotion = get_user_preferences('theme_shiftclass_reducedmotion', 0);
            if ($reducedmotion) {
                $classes = str_replace('class="', 'class="shiftclass-reduced-motion ', $classes);
            }
        }
        
        return $classes;
    }
}