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
 * Visual Profiles management page for ShiftClass theme
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

use theme_shiftclass\profiles_manager;
use theme_shiftclass\output\profiles_list;

// Page setup
// Page setup - Layout de uma coluna
require_login();
require_capability('theme/shiftclass:manageprofiles', context_system::instance());

$PAGE->set_url('/theme/shiftclass/manage_profiles.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin'); // Layout administrativo sem sidebar
$PAGE->set_title(get_string('manageprofiles', 'theme_shiftclass'));
$PAGE->set_heading(get_string('visualprofiles', 'theme_shiftclass'));

// Remover blocos laterais
$PAGE->blocks->show_only_fake_blocks();

// Add CSS
$PAGE->requires->css('/theme/shiftclass/styles/profiles_admin.css');


// Add JavaScript AMD module
$PAGE->requires->js_call_amd('theme_shiftclass/profiles_manager', 'init');

// Process actions
$action = optional_param('action', '', PARAM_ALPHA);
$profileid = optional_param('profileid', 0, PARAM_INT);

$manager = new profiles_manager();
$notification = '';
$notificationtype = 'info';

// Handle actions
if ($action && confirm_sesskey()) {
    try {
        switch ($action) {
            case 'delete':
                if ($profileid && $manager->delete_profile($profileid)) {
                    $notification = get_string('profiledeleted', 'theme_shiftclass');
                    $notificationtype = 'success';
                    redirect(new moodle_url('/theme/shiftclass/manage_profiles.php'), $notification, null, \core\output\notification::NOTIFY_SUCCESS);
                }
                break;
                
            case 'installdefaults':
                if ($manager->install_default_profiles()) {
                    $notification = get_string('defaultprofilesinstalled', 'theme_shiftclass');
                    $notificationtype = 'success';
                }
                break;
        }
    } catch (moodle_exception $e) {
        $notification = $e->getMessage();
        $notificationtype = 'error';
    }
}

// Get all profiles
$profiles = $manager->get_all_profiles();

// Start output
echo $OUTPUT->header();

// Show notification if any
if ($notification) {
    echo $OUTPUT->notification($notification, $notificationtype);
}

// Page content
echo html_writer::start_div('theme-shiftclass-profiles-page');

// Header section
echo html_writer::start_div('profiles-header mb-4');
echo html_writer::tag('p', get_string('visualprofilesinfo_desc', 'theme_shiftclass'), ['class' => 'text-muted']);

// Action buttons
echo html_writer::start_div('profiles-actions mt-3');

// Create new profile button
$createurl = new moodle_url('/theme/shiftclass/edit_profile.php');
echo html_writer::link($createurl, 
    html_writer::tag('i', '', ['class' => 'fa fa-plus me-2']) . get_string('createnewprofile', 'theme_shiftclass'), 
    ['class' => 'btn btn-primary me-2']);

// Install default profiles button (if no profiles exist)
if (empty($profiles)) {
    $installurl = new moodle_url('/theme/shiftclass/manage_profiles.php', [
        'action' => 'installdefaults',
        'sesskey' => sesskey()
    ]);
    echo html_writer::link($installurl, 
        html_writer::tag('i', '', ['class' => 'fa fa-download me-2']) . get_string('installdefaultprofiles', 'theme_shiftclass'), 
        ['class' => 'btn btn-secondary']);
}

echo html_writer::end_div(); // profiles-actions
echo html_writer::end_div(); // profiles-header

// Profiles list
if (!empty($profiles)) {
    echo html_writer::start_div('profiles-list-container');
    echo html_writer::tag('h3', get_string('existingprofiles', 'theme_shiftclass'), ['class' => 'mb-3']);
    
    // Table
    $table = new html_table();
    $table->head = [
        get_string('profilename', 'theme_shiftclass'),
        get_string('colors', 'theme_shiftclass'),
        get_string('usage', 'theme_shiftclass'),
        get_string('lastmodified', 'theme_shiftclass'),
        get_string('actions', 'theme_shiftclass')
    ];
    $table->attributes['class'] = 'table table-striped profiles-table';
    $table->colclasses = ['name', 'colors', 'usage', 'modified', 'actions'];
    
    foreach ($profiles as $profile) {
        $row = [];
        
        // Name
        $row[] = html_writer::tag('strong', format_string($profile->name));
        
        // Colors
        $colors = html_writer::start_div('color-samples');
        $colors .= html_writer::tag('span', '', [
            'class' => 'color-sample',
            'style' => 'background-color: ' . $profile->primarycolor,
            'title' => get_string('primarycolor', 'theme_shiftclass') . ': ' . $profile->primarycolor,
            'data-toggle' => 'tooltip'
        ]);
        $colors .= html_writer::tag('span', '', [
            'class' => 'color-sample',
            'style' => 'background-color: ' . $profile->secondarycolor,
            'title' => get_string('secondarycolor', 'theme_shiftclass') . ': ' . $profile->secondarycolor,
            'data-toggle' => 'tooltip'
        ]);
        $colors .= html_writer::tag('span', '', [
            'class' => 'color-sample',
            'style' => 'background-color: ' . $profile->backgroundcolor,
            'title' => get_string('backgroundcolor', 'theme_shiftclass') . ': ' . $profile->backgroundcolor,
            'data-toggle' => 'tooltip'
        ]);
        $colors .= html_writer::end_div();
        $row[] = $colors;
        
        // Usage count
        $usagecount = $manager->get_profile_usage_count($profile->id);
        if ($usagecount > 0) {
            $row[] = html_writer::tag('span', 
                get_string('usedinxcourses', 'theme_shiftclass', $usagecount), 
                ['class' => 'badge badge-info']);
        } else {
            $row[] = html_writer::tag('span', 
                get_string('notused', 'theme_shiftclass'), 
                ['class' => 'text-muted']);
        }
        
        // Last modified
        $row[] = userdate($profile->timemodified, get_string('strftimedatetime', 'langconfig'));
        
        // Actions
        $actions = '';
        
        // Preview button
        $actions .= html_writer::link('#', 
            html_writer::tag('i', '', ['class' => 'fa fa-eye']), 
            [
                'class' => 'btn btn-sm btn-secondary me-1 profile-preview-btn',
                'title' => get_string('preview', 'theme_shiftclass'),
                'data-toggle' => 'tooltip',
                'data-profileid' => $profile->id,
                'data-primary' => $profile->primarycolor,
                'data-secondary' => $profile->secondarycolor,
                'data-background' => $profile->backgroundcolor
            ]);
        
        // Edit button
        $editurl = new moodle_url('/theme/shiftclass/edit_profile.php', ['id' => $profile->id]);
        $actions .= html_writer::link($editurl, 
            html_writer::tag('i', '', ['class' => 'fa fa-edit']), 
            [
                'class' => 'btn btn-sm btn-primary me-1',
                'title' => get_string('edit'),
                'data-toggle' => 'tooltip'
            ]);
        
        // Delete button
        if ($usagecount == 0) {
            $deleteurl = new moodle_url('/theme/shiftclass/delete_profile.php', [
                'id' => $profile->id,
                'sesskey' => sesskey()
            ]);
            $actions .= html_writer::link($deleteurl, 
                html_writer::tag('i', '', ['class' => 'fa fa-trash']), 
                [
                    'class' => 'btn btn-sm btn-danger profile-delete-btn',
                    'title' => get_string('delete'),
                    'data-toggle' => 'tooltip',
                    'data-profilename' => $profile->name
                ]);
        } else {
            $actions .= html_writer::tag('button', 
                html_writer::tag('i', '', ['class' => 'fa fa-trash']), 
                [
                    'class' => 'btn btn-sm btn-danger',
                    'title' => get_string('cannotdelete_inuse', 'theme_shiftclass'),
                    'data-toggle' => 'tooltip',
                    'disabled' => 'disabled'
                ]);
        }
        
        $row[] = $actions;
        
        $table->data[] = $row;
    }
    
    echo html_writer::table($table);
    echo html_writer::end_div(); // profiles-list-container
    
} else {
    // No profiles message
    echo html_writer::start_div('alert alert-info');
    echo html_writer::tag('i', '', ['class' => 'fa fa-info-circle me-2']);
    echo get_string('noprofilescreated', 'theme_shiftclass');
    echo html_writer::end_div();
}

// Statistics section
if (!empty($profiles)) {
    echo html_writer::start_div('profiles-statistics mt-5');
    echo html_writer::tag('h3', get_string('statistics', 'theme_shiftclass'), ['class' => 'mb-3']);
    
    $stats = [];
    $stats[] = [
        'label' => get_string('totalprofiles', 'theme_shiftclass'),
        'value' => count($profiles),
        'icon' => 'fa-palette'
    ];
    
    $totalusage = 0;
    foreach ($profiles as $profile) {
        $totalusage += $manager->get_profile_usage_count($profile->id);
    }
    
    $stats[] = [
        'label' => get_string('totalcoursesusingprofiles', 'theme_shiftclass'),
        'value' => $totalusage,
        'icon' => 'fa-graduation-cap'
    ];
    
    echo html_writer::start_div('row');
    foreach ($stats as $stat) {
        echo html_writer::start_div('col-md-3 mb-3');
        echo html_writer::start_div('stat-card');
        echo html_writer::tag('i', '', ['class' => 'fa ' . $stat['icon'] . ' stat-icon']);
        echo html_writer::tag('div', $stat['value'], ['class' => 'stat-value']);
        echo html_writer::tag('div', $stat['label'], ['class' => 'stat-label']);
        echo html_writer::end_div();
        echo html_writer::end_div();
    }
    echo html_writer::end_div(); // row
    
    echo html_writer::end_div(); // profiles-statistics
}

echo html_writer::end_div(); // theme-shiftclass-profiles-page

// Preview modal
echo '
<div class="modal fade" id="profilePreviewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">' . get_string('profilepreview', 'theme_shiftclass') . '</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="profile-preview-content"></div>
            </div>
        </div>
    </div>
</div>';

// Footer
echo $OUTPUT->footer();