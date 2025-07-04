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
 * Theme ShiftClass settings.
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    // Boost provides a nice settings page which splits settings onto separate tabs.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingshiftclass', get_string('configtitle', 'theme_shiftclass'));

    // General settings tab
    $page = new admin_settingpage('theme_shiftclass_general', get_string('generalsettings', 'theme_shiftclass'));

    // Preset setting
    $name = 'theme_shiftclass/preset';
    $title = get_string('preset', 'theme_shiftclass');
    $description = get_string('preset_desc', 'theme_shiftclass');
    $default = 'default.scss';
    $choices = [
        'default.scss' => get_string('presetdefault', 'theme_shiftclass'),
        'plain.scss' => get_string('presetplain', 'theme_shiftclass'),
    ];
    $setting = new admin_setting_configthemepreset($name, $title, $description, $default, $choices, 'shiftclass');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Colors section heading
    $name = 'theme_shiftclass/colorsheading';
    $title = get_string('colorsheading', 'theme_shiftclass');
    $description = get_string('colorsheading_desc', 'theme_shiftclass');
    $setting = new admin_setting_heading($name, $title, $description);
    $page->add($setting);

    // Primary color setting (Brand color)
    $name = 'theme_shiftclass/brandcolor';
    $title = get_string('brandcolor', 'theme_shiftclass');
    $description = get_string('brandcolor_desc', 'theme_shiftclass');
    $default = '#0f6cbf';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Secondary color setting
    $name = 'theme_shiftclass/secondarycolor';
    $title = get_string('secondarycolor', 'theme_shiftclass');
    $description = get_string('secondarycolor_desc', 'theme_shiftclass');
    $default = '#6c757d';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Accent color setting
    $name = 'theme_shiftclass/accentcolor';
    $title = get_string('accentcolor', 'theme_shiftclass');
    $description = get_string('accentcolor_desc', 'theme_shiftclass');
    $default = '#28a745';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Background color setting
    $name = 'theme_shiftclass/backgroundcolor';
    $title = get_string('backgroundcolor', 'theme_shiftclass');
    $description = get_string('backgroundcolor_desc', 'theme_shiftclass');
    $default = '#f8f9fa';
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Must add the page after defining all the settings!
    $settings->add($page);

    // Advanced settings tab
    $page = new admin_settingpage('theme_shiftclass_advanced', get_string('advancedsettings', 'theme_shiftclass'));

    // Raw SCSS to include before the content
    $setting = new admin_setting_scsscode('theme_shiftclass/scsspre',
        get_string('rawscsspre', 'theme_shiftclass'), get_string('rawscsspre_desc', 'theme_shiftclass'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content
    $setting = new admin_setting_scsscode('theme_shiftclass/scss', get_string('rawscss', 'theme_shiftclass'),
        get_string('rawscss_desc', 'theme_shiftclass'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    // Visual Profiles tab (stub for Phase 2)
    $page = new admin_settingpage('theme_shiftclass_profiles', get_string('visualprofiles', 'theme_shiftclass'));
    
    // Information about visual profiles
    $setting = new admin_setting_heading('theme_shiftclass/profilesinfo', 
        get_string('visualprofilesinfo', 'theme_shiftclass'),
        get_string('visualprofilesinfo_desc', 'theme_shiftclass'));
    $page->add($setting);
    
    // Placeholder for profile management link (to be implemented in Phase 2)
    $setting = new admin_setting_heading('theme_shiftclass/manageprofiles', 
        get_string('manageprofiles', 'theme_shiftclass'),
        get_string('manageprofiles_desc', 'theme_shiftclass'));
    $page->add($setting);
    
    $settings->add($page);
}