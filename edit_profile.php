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
 * Edit visual profile page for ShiftClass theme
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

use theme_shiftclass\profiles_manager;
use theme_shiftclass\form\profile_form;

// Page setup - Layout de uma coluna
require_login();
require_capability('theme/shiftclass:manageprofiles', context_system::instance());

// Parameters
$profileid = optional_param('id', 0, PARAM_INT);

// URLs
$manageurl = new moodle_url('/theme/shiftclass/manage_profiles.php');
$editurl = new moodle_url('/theme/shiftclass/edit_profile.php');

// Page setup
$PAGE->set_url($editurl, ['id' => $profileid]);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin'); // Layout administrativo sem sidebar

// Load profile if editing
$manager = new profiles_manager();
$profile = null;
if ($profileid) {
    $profile = $manager->get_profile($profileid);
    if (!$profile) {
        throw new moodle_exception('error:profilenotfound', 'theme_shiftclass');
    }
    $PAGE->set_title(get_string('editprofile', 'theme_shiftclass'));
    $PAGE->navbar->add(get_string('editprofile', 'theme_shiftclass'));
} else {
    $PAGE->set_title(get_string('createnewprofile', 'theme_shiftclass'));
    $PAGE->navbar->add(get_string('createnewprofile', 'theme_shiftclass'));
}

$PAGE->set_heading(get_string('visualprofiles', 'theme_shiftclass'));

// Remover blocos laterais
$PAGE->blocks->show_only_fake_blocks();

// Add CSS
$PAGE->requires->css('/theme/shiftclass/styles/profiles_admin.css');

// Create form
$customdata = ['profile' => $profile];
$form = new profile_form($editurl->out(false), $customdata);

// Handle form submission
if ($form->is_cancelled()) {
    redirect($manageurl);
    
} else if ($data = $form->get_data()) {
    try {
        // Process file upload
        if (isset($data->defaultheaderimage)) {
            $context = context_system::instance();
            $filearea = 'defaultheaderimage';
            $itemid = $profileid ?: $data->id ?? 0;
            
            // Save files from draft area
            file_save_draft_area_files(
                $data->defaultheaderimage,
                $context->id,
                'theme_shiftclass',
                $filearea,
                $itemid,
                [
                    'subdirs' => 0,
                    'maxbytes' => $CFG->maxbytes,
                    'maxfiles' => 1
                ]
            );
            
            // Get file URL for database storage
            $fs = get_file_storage();
            $files = $fs->get_area_files($context->id, 'theme_shiftclass', $filearea, $itemid, 'filename', false);
            
            if (!empty($files)) {
                $file = reset($files);
                $data->defaultheaderimage = moodle_url::make_pluginfile_url(
                    $context->id,
                    'theme_shiftclass',
                    $filearea,
                    $itemid,
                    '/',
                    $file->get_filename()
                )->out();
            } else {
                $data->defaultheaderimage = null;
            }
        }
        
        if ($profileid) {
            // Update existing profile
            $manager->update_profile($profileid, $data);
            $message = get_string('profileupdated', 'theme_shiftclass');
        } else {
            // Create new profile
            $newid = $manager->create_profile($data);
            $message = get_string('profilecreated', 'theme_shiftclass');
            
            // If new profile and has file, update the itemid
            if (isset($data->defaultheaderimage) && $data->defaultheaderimage) {
                // Move files to correct itemid
                $context = context_system::instance();
                $filearea = 'defaultheaderimage';
                
                file_save_draft_area_files(
                    $data->defaultheaderimage,
                    $context->id,
                    'theme_shiftclass',
                    $filearea,
                    $newid,
                    [
                        'subdirs' => 0,
                        'maxbytes' => $CFG->maxbytes,
                        'maxfiles' => 1
                    ]
                );
            }
        }
        
        // Redirect with success message
        redirect($manageurl, $message, null, \core\output\notification::NOTIFY_SUCCESS);
        
    } catch (moodle_exception $e) {
        // Show error and redisplay form
        echo $OUTPUT->header();
        echo $OUTPUT->notification($e->getMessage(), 'error');
        $form->display();
        echo $OUTPUT->footer();
        exit;
    }
}

// Display page
echo $OUTPUT->header();

// Breadcrumb
echo html_writer::start_div('profile-edit-page');

// Back button
echo html_writer::link($manageurl, 
    html_writer::tag('i', '', ['class' => 'fa fa-arrow-left me-2']) . get_string('backtolist', 'theme_shiftclass'), 
    ['class' => 'btn btn-secondary mb-3']);

// Page heading
if ($profileid) {
    echo html_writer::tag('h2', get_string('editingprofile', 'theme_shiftclass', format_string($profile->name)));
} else {
    echo html_writer::tag('h2', get_string('createnewprofile', 'theme_shiftclass'));
}

// Information box
echo html_writer::start_div('alert alert-info mt-3 mb-4');
echo html_writer::tag('i', '', ['class' => 'fa fa-info-circle me-2']);
echo get_string('profileforminfo', 'theme_shiftclass');
echo html_writer::end_div();

// Display form
$form->display();

echo html_writer::end_div(); // profile-edit-page

// Add inline CSS for form styling
echo '<style>
.color-input-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.color-text-input {
    width: 120px !important;
    font-family: monospace;
}

.color-picker {
    width: 50px;
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    cursor: pointer;
}

.color-preview {
    display: inline-block;
    width: 38px;
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.profile-preview-container {
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 20px;
    background-color: #f8f9fa;
}

.profile-preview {
    border-radius: 0.25rem;
    overflow: hidden;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.preview-navbar {
    height: 50px;
    display: flex;
    align-items: center;
    padding: 0 20px;
    color: white;
    font-weight: 500;
}

.preview-content {
    min-height: 200px;
    padding: 20px;
}

.preview-card {
    background: white;
    padding: 20px;
    border-radius: 0.25rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.preview-card h5 {
    margin-top: 0;
}

.preview-btn-primary,
.preview-btn-secondary {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    margin-right: 0.5rem;
    border: none;
    border-radius: 0.25rem;
    color: white;
    font-size: 0.875rem;
    cursor: pointer;
}
</style>';

echo $OUTPUT->footer();