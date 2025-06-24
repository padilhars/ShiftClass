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
 * Delete visual profile confirmation page for ShiftClass theme
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

use theme_shiftclass\profiles_manager;

// Page setup
admin_externalpage_setup('theme_shiftclass_profiles');
require_capability('theme/shiftclass:manageprofiles', context_system::instance());

// Parameters
$profileid = required_param('id', PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

// URLs
$manageurl = new moodle_url('/theme/shiftclass/manage_profiles.php');
$deleteurl = new moodle_url('/theme/shiftclass/delete_profile.php', ['id' => $profileid]);

// Page setup
$PAGE->set_url($deleteurl);
$PAGE->set_title(get_string('deleteprofile', 'theme_shiftclass'));
$PAGE->set_heading(get_string('visualprofiles', 'theme_shiftclass'));

// Load profile
$manager = new profiles_manager();
$profile = $manager->get_profile($profileid);

if (!$profile) {
    throw new moodle_exception('error:profilenotfound', 'theme_shiftclass');
}

// Check if profile is in use
$usagecount = $manager->get_profile_usage_count($profileid);
if ($usagecount > 0) {
    // Cannot delete profile in use
    echo $OUTPUT->header();
    echo $OUTPUT->notification(get_string('error:profileinuse', 'theme_shiftclass'), 'error');
    echo html_writer::tag('p', get_string('profileusedincourses', 'theme_shiftclass', $usagecount));
    echo html_writer::link($manageurl, get_string('back'), ['class' => 'btn btn-secondary']);
    echo $OUTPUT->footer();
    exit;
}

// Handle confirmation
if ($confirm && confirm_sesskey()) {
    try {
        $manager->delete_profile($profileid);
        redirect($manageurl, get_string('profiledeleted', 'theme_shiftclass'), null, \core\output\notification::NOTIFY_SUCCESS);
    } catch (moodle_exception $e) {
        redirect($manageurl, $e->getMessage(), null, \core\output\notification::NOTIFY_ERROR);
    }
}

// Display confirmation page
echo $OUTPUT->header();

// Confirmation message
$message = get_string('deleteprofileconfirm', 'theme_shiftclass', format_string($profile->name));

// Profile details to show what will be deleted
$details = html_writer::start_div('alert alert-warning mt-3');
$details .= html_writer::tag('h5', get_string('profiledetails', 'theme_shiftclass'));
$details .= html_writer::start_tag('ul');
$details .= html_writer::tag('li', get_string('profilename', 'theme_shiftclass') . ': ' . format_string($profile->name));
$details .= html_writer::tag('li', get_string('primarycolor', 'theme_shiftclass') . ': ' . $profile->primarycolor);
$details .= html_writer::tag('li', get_string('secondarycolor', 'theme_shiftclass') . ': ' . $profile->secondarycolor);
$details .= html_writer::tag('li', get_string('backgroundcolor', 'theme_shiftclass') . ': ' . $profile->backgroundcolor);
$details .= html_writer::tag('li', get_string('created', 'theme_shiftclass') . ': ' . userdate($profile->timecreated));
$details .= html_writer::end_tag('ul');
$details .= html_writer::end_div();

// Confirm/Cancel buttons
$confirmurl = new moodle_url($deleteurl, ['confirm' => 1, 'sesskey' => sesskey()]);

echo $OUTPUT->confirm($message . $details, $confirmurl, $manageurl);

echo $OUTPUT->footer();