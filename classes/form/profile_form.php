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
 * Profile form for ShiftClass theme
 *
 * @package    theme_shiftclass
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_shiftclass\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

use moodleform;
use html_writer;

/**
 * Form for creating and editing visual profiles
 */
class profile_form extends moodleform {
    
    /**
     * Define the form elements
     */
    protected function definition() {
        global $CFG;
        
        $mform = $this->_form;
        $profile = $this->_customdata['profile'] ?? null;
        $isediting = !empty($profile);
        
        // Hidden field for profile ID
        if ($isediting) {
            $mform->addElement('hidden', 'id', $profile->id);
            $mform->setType('id', PARAM_INT);
        }
        
        // Profile name
        $mform->addElement('text', 'name', get_string('profilename', 'theme_shiftclass'), [
            'size' => 50,
            'maxlength' => 50
        ]);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', get_string('required'), 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 50), 'maxlength', 50, 'client');
        $mform->addHelpButton('name', 'profilename', 'theme_shiftclass');
        
        // Color fields with integrated color picker and text input
        $this->add_color_field($mform, 'primarycolor', get_string('primarycolor', 'theme_shiftclass'), '#0066CC');
        $this->add_color_field($mform, 'secondarycolor', get_string('secondarycolor', 'theme_shiftclass'), '#004499');
        $this->add_color_field($mform, 'backgroundcolor', get_string('backgroundcolor', 'theme_shiftclass'), '#F0F5FF');
        
        // Default header image
        $mform->addElement('filepicker', 'defaultheaderimage', get_string('defaultheaderimage', 'theme_shiftclass'), null, [
            'accepted_types' => ['.jpg', '.jpeg', '.png', '.gif'],
            'maxbytes' => $CFG->maxbytes
        ]);
        $mform->addHelpButton('defaultheaderimage', 'defaultheaderimage', 'theme_shiftclass');
        
        // Live preview section
        $mform->addElement('html', '<div class="mb-3 row">');
        $mform->addElement('html', '<div class="col-md-3">' . get_string('preview', 'theme_shiftclass') . '</div>');
        $mform->addElement('html', '<div class="col-md-9">');
        $mform->addElement('html', '<div id="profile-preview" class="profile-preview-container">');
        $mform->addElement('html', $this->get_preview_html());
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');
        
        // WCAG contrast validation info
        $mform->addElement('html', '<div class="mb-3 row">');
        $mform->addElement('html', '<div class="col-md-3"></div>');
        $mform->addElement('html', '<div class="col-md-9">');
        $mform->addElement('html', '<div id="contrast-validation" class="alert alert-info">');
        $mform->addElement('html', '<i class="fa fa-info-circle"></i> ' . get_string('contrastvalidation', 'theme_shiftclass'));
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');
        
        // Action buttons
        $this->add_action_buttons();
        
        // Set default values if editing
        if ($isediting) {
            $this->set_data($profile);
        }
        
        // Add JavaScript for live preview
        $this->add_javascript();
    }
    
    /**
     * Add a color field with integrated picker and text input
     * 
     * @param MoodleQuickForm $mform Form instance
     * @param string $name Field name
     * @param string $label Field label
     * @param string $default Default color
     */
    private function add_color_field($mform, $name, $label, $default) {
        $mform->addElement('html', '<div class="mb-3 row fitem">');
        $mform->addElement('html', '<div class="col-md-3">' . $label . '</div>');
        $mform->addElement('html', '<div class="col-md-9">');
        $mform->addElement('html', '<div class="color-input-group">');
        
        // Text input
        $mform->addElement('text', $name, '', [
            'size' => 7,
            'maxlength' => 7,
            'class' => 'color-text-input',
            'pattern' => '^#[0-9A-Fa-f]{6}$',
            'placeholder' => '#RRGGBB'
        ]);
        $mform->setType($name, PARAM_TEXT);
        $mform->setDefault($name, $default);
        $mform->addRule($name, get_string('required'), 'required', null, 'client');
        $mform->addRule($name, get_string('error:invalidcolor', 'theme_shiftclass'), 'regex', '/^#[0-9A-Fa-f]{6}$/', 'client');
        
        // Color picker
        $mform->addElement('html', '<input type="color" class="color-picker" data-target="' . $name . '" value="' . $default . '">');
        
        // Color preview
        $mform->addElement('html', '<span class="color-preview" style="background-color: ' . $default . '"></span>');
        
        $mform->addElement('html', '</div>');
        
        // Help button
        $mform->addHelpButton($name, $name, 'theme_shiftclass');
        
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');
    }
    
    /**
     * Get preview HTML
     * 
     * @return string HTML for preview
     */
    private function get_preview_html() {
        $html = '
        <div class="profile-preview">
            <div class="preview-navbar" style="background-color: var(--preview-primary);">
                <span class="preview-brand">' . get_string('samplenavbar', 'theme_shiftclass') . '</span>
            </div>
            <div class="preview-content" style="background-color: var(--preview-background);">
                <div class="preview-card">
                    <h5>' . get_string('samplecontent', 'theme_shiftclass') . '</h5>
                    <p>' . get_string('sampletext', 'theme_shiftclass') . '</p>
                    <button class="preview-btn-primary" style="background-color: var(--preview-primary);">
                        ' . get_string('primarybutton', 'theme_shiftclass') . '
                    </button>
                    <button class="preview-btn-secondary" style="background-color: var(--preview-secondary);">
                        ' . get_string('secondarybutton', 'theme_shiftclass') . '
                    </button>
                </div>
            </div>
        </div>';
        
        return $html;
    }
    
    /**
     * Add JavaScript for live preview and color synchronization
     */
    private function add_javascript() {
        global $PAGE;
        $PAGE->requires->js_call_amd('theme_shiftclass/profile_form', 'init');
    }
    
    /**
     * Validation
     * 
     * @param array $data Form data
     * @param array $files Form files
     * @return array Validation errors
     */
    public function validation($data, $files) {
        global $DB;
        
        $errors = parent::validation($data, $files);
        
        // Validate unique name
        $sql = "SELECT id FROM {theme_shiftclass_profiles} WHERE name = :name";
        $params = ['name' => $data['name']];
        
        if (!empty($data['id'])) {
            $sql .= " AND id != :id";
            $params['id'] = $data['id'];
        }
        
        if ($DB->record_exists_sql($sql, $params)) {
            $errors['name'] = get_string('error:duplicateprofilename', 'theme_shiftclass');
        }
        
        // Validate colors
        $colorfields = ['primarycolor', 'secondarycolor', 'backgroundcolor'];
        foreach ($colorfields as $field) {
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $data[$field])) {
                $errors[$field] = get_string('error:invalidcolor', 'theme_shiftclass');
            }
        }
        
        return $errors;
    }
}