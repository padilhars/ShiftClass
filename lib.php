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
 * Theme ShiftClass lib functions.
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Returns the main SCSS content for the theme.
 *
 * @param theme_config $theme The theme config object.
 * @return string The main SCSS content.
 */
function theme_shiftclass_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_shiftclass', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        // Use default preset.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    // Include core.scss
    $scss .= file_get_contents($CFG->dirroot . '/theme/shiftclass/scss/core.scss');

    // Include custom.scss
    $scss .= file_get_contents($CFG->dirroot . '/theme/shiftclass/scss/custom.scss');

    return $scss;
}

/**
 * Get pre SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string Pre SCSS content.
 */
function theme_shiftclass_get_pre_scss($theme) {
    global $CFG;

    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'brandcolor' => ['primary'],
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // Prepend pre.scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    // Include pre.scss file
    $prescss = file_get_contents($CFG->dirroot . '/theme/shiftclass/scss/pre.scss');
    if ($prescss) {
        $scss .= $prescss;
    }

    return $scss;
}

/**
 * Get extra SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string Extra SCSS content.
 */
function theme_shiftclass_get_extra_scss($theme) {
    global $CFG;
    
    $scss = '';
    
    // Include post.scss file
    $postscss = file_get_contents($CFG->dirroot . '/theme/shiftclass/scss/post.scss');
    if ($postscss) {
        $scss .= $postscss;
    }
    
    // Add any custom SCSS from theme settings
    if (!empty($theme->settings->scss)) {
        $scss .= $theme->settings->scss;
    }
    
    return $scss;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course Course object
 * @param stdClass $cm Course module object
 * @param context $context Context
 * @param string $filearea File area
 * @param array $args Arguments
 * @param bool $forcedownload Force download
 * @param array $options Options
 * @return bool
 */
function theme_shiftclass_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM && ($filearea === 'logo' || $filearea === 'backgroundimage')) {
        $theme = theme_config::load('shiftclass');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}

/**
 * Get icon mapping for font-awesome.
 */
function theme_shiftclass_get_fontawesome_icon_map() {
    return [
        'core:i/profile' => 'fa-user',
        'core:i/course' => 'fa-graduation-cap',
        'core:i/section' => 'fa-folder-o',
        'core:i/edit' => 'fa-pencil',
        'core:i/settings' => 'fa-cog',
        'core:i/grades' => 'fa-table',
        'core:i/report' => 'fa-area-chart',
    ];
}

/**
 * Initialize page requirements for visual profiles
 * This is a stub for future implementation
 *
 * @param moodle_page $page The page object
 */
function theme_shiftclass_init_profile($page) {
    global $DB, $COURSE;
    
    // Stub: In future phases, this will load and apply visual profiles
    // based on course settings
    
    return true;
}

/**
 * Get visual profile for a course
 * This is a stub for future implementation
 *
 * @param int $courseid The course ID
 * @return stdClass|false Profile object or false if not found
 */
function theme_shiftclass_get_course_profile($courseid) {
    global $DB;
    
    // Stub: Will be implemented in Phase 2
    // This will retrieve the visual profile assigned to a course
    
    return false;
}

/**
 * Apply visual profile to page
 * This is a stub for future implementation
 *
 * @param stdClass $profile The profile object
 * @param moodle_page $page The page object
 */
function theme_shiftclass_apply_profile($profile, $page) {
    // Stub: Will be implemented in Phase 3
    // This will apply CSS variables and other profile settings
    
    return true;
}

/**
 * Observer for course deletion event
 * Cleans up profile associations when a course is deleted
 *
 * @param \core\event\course_deleted $event
 */
function theme_shiftclass_course_deleted_observer(\core\event\course_deleted $event) {
    global $DB;
    
    // Remove any profile associations for the deleted course
    $courseid = $event->objectid;
    $DB->delete_records('theme_shiftclass_course_profiles', array('courseid' => $courseid));
    
    // Clear theme caches
    theme_reset_all_caches();
}

/**
 * Observer for course viewed event
 * Applies visual profile when a course is accessed
 *
 * @param \core\event\course_viewed $event
 */
function theme_shiftclass_course_viewed_observer(\core\event\course_viewed $event) {
    global $PAGE;
    
    // Only apply in course context
    if ($PAGE->context->contextlevel != CONTEXT_COURSE) {
        return;
    }
    
    $courseid = $event->courseid;
    
    // Initialize profile for the course
    theme_shiftclass_init_profile($PAGE);
}