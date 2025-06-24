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
 * Language file for theme_shiftclass (English)
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Plugin name and description
$string['pluginname'] = 'ShiftClass';
$string['choosereadme'] = 'ShiftClass - Educating is adapting. And your theme knows that. A modern, customizable theme with visual profiles for courses.';

// Settings
$string['configtitle'] = 'ShiftClass';
$string['generalsettings'] = 'General settings';
$string['advancedsettings'] = 'Advanced settings';

// General settings
$string['preset'] = 'Theme preset';
$string['preset_desc'] = 'Pick a preset to broadly change the look of the theme.';
$string['presetdefault'] = 'Default';
$string['presetplain'] = 'Plain';
$string['brandcolor'] = 'Brand colour';
$string['brandcolor_desc'] = 'The main colour used throughout the theme.';

// Advanced settings
$string['rawscsspre'] = 'Raw initial SCSS';
$string['rawscsspre_desc'] = 'In this field you can provide initialising SCSS code, it will be injected before everything else. Most of the time you will use this setting to define variables.';
$string['rawscss'] = 'Raw SCSS';
$string['rawscss_desc'] = 'Use this field to provide SCSS or CSS code which will be injected at the end of the style sheet.';

// Visual Profiles
$string['visualprofiles'] = 'Visual Profiles';
$string['visualprofilesinfo'] = 'About Visual Profiles';
$string['visualprofilesinfo_desc'] = 'Visual profiles allow you to create custom color schemes that can be applied to individual courses. Each profile consists of a name and three colors that define the course appearance.';
$string['manageprofiles'] = 'Manage Visual Profiles';
$string['manageprofiles_desc'] = 'Visual profile management will be available in the next phase of development.';

// Profile-related strings (for future phases)
$string['profilename'] = 'Profile name';
$string['profilename_help'] = 'Enter a unique name for this visual profile.';
$string['primarycolor'] = 'Primary color';
$string['primarycolor_help'] = 'Main color used for navigation bar and primary elements.';
$string['secondarycolor'] = 'Secondary color';
$string['secondarycolor_help'] = 'Color used for buttons, links, and highlights.';
$string['backgroundcolor'] = 'Background color';
$string['backgroundcolor_help'] = 'Color used for the page background.';

// Course settings (for future phases)
$string['coursevisualprofile'] = 'Visual profile';
$string['coursevisualprofile_help'] = 'Select a visual profile to customize the appearance of this course.';
$string['novisualprofile'] = 'No visual profile';

// Capabilities
$string['shiftclass:manageprofiles'] = 'Manage visual profiles';

// Privacy
$string['privacy:metadata'] = 'The ShiftClass theme does not store any personal data.';

// Accessibility
$string['region-side-pre'] = 'Left sidebar';

// Profile management
$string['createnewprofile'] = 'Create new profile';
$string['editprofile'] = 'Edit profile';
$string['deleteprofile'] = 'Delete profile';
$string['editingprofile'] = 'Editing profile: {$a}';
$string['existingprofiles'] = 'Existing profiles';
$string['installdefaultprofiles'] = 'Install default profiles';
$string['defaultprofilesinstalled'] = 'Default profiles installed successfully';
$string['colors'] = 'Colors';
$string['usage'] = 'Usage';
$string['lastmodified'] = 'Last modified';
$string['actions'] = 'Actions';
$string['preview'] = 'Preview';
$string['usedinxcourses'] = 'Used in {$a} course(s)';
$string['notused'] = 'Not used';
$string['statistics'] = 'Statistics';
$string['totalprofiles'] = 'Total profiles';
$string['totalcoursesusingprofiles'] = 'Courses using profiles';
$string['profilecreated'] = 'Profile created successfully';
$string['profileupdated'] = 'Profile updated successfully';
$string['profiledeleted'] = 'Profile deleted successfully';
$string['deleteprofileconfirm'] = 'Are you sure you want to delete the profile "{$a}"?';
$string['profiledetails'] = 'Profile details';
$string['created'] = 'Created';
$string['backtolist'] = 'Back to list';
$string['profileforminfo'] = 'Create a visual profile with custom colors. The preview will update as you make changes.';
$string['profileusedincourses'] = 'This profile is being used by {$a} course(s) and cannot be deleted.';
$string['cannotdelete_inuse'] = 'Cannot delete - profile is in use';
$string['profilepreview'] = 'Profile Preview';
$string['noprofilescreated'] = 'No visual profiles have been created yet. Click "Create new profile" to get started.';

// Profile form
$string['defaultheaderimage'] = 'Default header image';
$string['defaultheaderimage_help'] = 'Upload a default header image for courses using this profile.';
$string['samplenavbar'] = 'Course Navigation';
$string['samplecontent'] = 'Course Content';
$string['sampletext'] = 'This is how your course will look with this visual profile applied.';
$string['primarybutton'] = 'Primary Action';
$string['secondarybutton'] = 'Secondary Action';
$string['contrastvalidation'] = 'Checking color contrast for accessibility (WCAG 2.1)';
$string['contrastpass'] = 'Excellent contrast! Meets WCAG AA standards.';
$string['contrastwarning'] = 'Contrast could be better. Meets WCAG AA for large text only.';
$string['contrastfail'] = 'Poor contrast. Consider using different colors for better accessibility.';

// Default profiles
$string['profile:corporate_blue'] = 'Corporate Blue';
$string['profile:nature_green'] = 'Nature Green';
$string['profile:modern_purple'] = 'Modern Purple';
$string['profile:dynamic_orange'] = 'Dynamic Orange';

// Error messages
$string['error:profilenotfound'] = 'Visual profile not found';
$string['error:invalidcolor'] = 'Invalid color format. Please use hexadecimal format (#RRGGBB)';
$string['error:duplicateprofilename'] = 'A profile with this name already exists';
$string['error:profilenamerequired'] = 'Profile name is required';
$string['error:profilenametoolong'] = 'Profile name must not exceed 50 characters';
$string['error:primarycolorrequired'] = 'Primary color is required';
$string['error:secondarycolorrequired'] = 'Secondary color is required';
$string['error:backgroundcolorrequired'] = 'Background color is required';
$string['error:profileinuse'] = 'This profile is in use and cannot be deleted';

// Events
$string['event:profilecreated'] = 'Visual profile created';
$string['event:profileupdated'] = 'Visual profile updated';
$string['event:profiledeleted'] = 'Visual profile deleted';

// Privacy
$string['privacy:metadata:profiles'] = 'Information about visual profiles created or modified by users';
$string['privacy:metadata:profiles:usermodified'] = 'The ID of the user who created or last modified the profile';
$string['privacy:metadata:profiles:timecreated'] = 'The time when the profile was created';
$string['privacy:metadata:profiles:timemodified'] = 'The time when the profile was last modified';
$string['privacy:metadata:courseprofiles'] = 'Information about visual profiles assigned to courses';
$string['privacy:metadata:courseprofiles:usermodified'] = 'The ID of the user who assigned the profile to the course';
$string['privacy:metadata:courseprofiles:timecreated'] = 'The time when the profile was assigned';
$string['privacy:metadata:courseprofiles:timemodified'] = 'The time when the assignment was last modified';
$string['privacy:metadata:preference:highcontrast'] = 'User preference for high contrast mode';
$string['privacy:metadata:preference:reducedmotion'] = 'User preference for reduced motion';