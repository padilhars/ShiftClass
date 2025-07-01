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

// Colors section
$string['colorsheading'] = 'Color Settings';
$string['colorsheading_desc'] = 'Customize the theme colors. These colors will be used throughout the site and can be overridden by individual course visual profiles.';

$string['brandcolor'] = 'Primary color';
$string['brandcolor_desc'] = 'The primary color used for navigation, buttons, and main elements throughout the theme.';

$string['secondarycolor'] = 'Secondary color';
$string['secondarycolor_desc'] = 'The secondary color used for supporting elements, hover states, and complementary design features.';

$string['accentcolor'] = 'Accent color';
$string['accentcolor_desc'] = 'The accent color used for highlights, success messages, and call-to-action elements.';

$string['backgroundcolor'] = 'Background color';
$string['backgroundcolor_desc'] = 'The main background color for pages. Choose a light color for better readability.';

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
$string['secondarycolor_profile'] = 'Secondary color';
$string['secondarycolor_profile_help'] = 'Color used for buttons, links, and highlights.';
$string['backgroundcolor_profile'] = 'Background color';
$string['backgroundcolor_profile_help'] = 'Color used for the page background.';

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

// Error messages
$string['error:profilenotfound'] = 'Visual profile not found';
$string['error:invalidcolor'] = 'Invalid color format. Please use hexadecimal format (#RRGGBB)';
$string['error:duplicateprofilename'] = 'A profile with this name already exists';

// Color preview and helpers
$string['colorpreview'] = 'Color preview';
$string['resetcolors'] = 'Reset to default colors';
$string['colorscheme'] = 'Color scheme';
$string['customcolors'] = 'Custom colors';
$string['colortips'] = 'Color tips';
$string['colortips_desc'] = 'Choose colors that provide good contrast for readability. The primary color should be bold enough for navigation elements, while the background should be light and subtle.';