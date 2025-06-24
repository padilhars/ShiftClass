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
 * Behat step definitions for ShiftClass theme
 *
 * @package    theme_shiftclass
 * @category   test
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

use Behat\Gherkin\Node\TableNode;
use theme_shiftclass\profiles_manager;

/**
 * ShiftClass theme behat steps
 */
class behat_theme_shiftclass extends behat_base {
    
    /**
     * Creates visual profiles for testing
     *
     * @Given /^the following visual profiles exist:$/
     * @param TableNode $table
     */
    public function the_following_visual_profiles_exist(TableNode $table) {
        global $DB;
        
        $manager = new profiles_manager();
        
        foreach ($table->getHash() as $data) {
            $profile = new stdClass();
            $profile->name = $data['name'];
            $profile->primarycolor = $data['primarycolor'];
            $profile->secondarycolor = $data['secondarycolor'];
            $profile->backgroundcolor = $data['backgroundcolor'];
            
            try {
                $manager->create_profile($profile);
            } catch (Exception $e) {
                // Profile might already exist
            }
        }
    }
    
    /**
     * Assigns a visual profile to a course
     *
     * @Given /^the course "([^"]*)" uses the visual profile "([^"]*)"$/
     * @param string $coursename
     * @param string $profilename
     */
    public function the_course_uses_the_visual_profile($coursename, $profilename) {
        global $DB;
        
        $course = $DB->get_record('course', ['fullname' => $coursename], '*', MUST_EXIST);
        $profile = $DB->get_record('theme_shiftclass_profiles', ['name' => $profilename], '*', MUST_EXIST);
        
        $manager = new profiles_manager();
        $manager->assign_profile_to_course($course->id, $profile->id);
    }
    
    /**
     * Checks if a visual profile exists
     *
     * @Then /^I should see the visual profile "([^"]*)"$/
     * @param string $profilename
     */
    public function i_should_see_the_visual_profile($profilename) {
        $this->execute('behat_general::assert_element_contains_text', [$profilename, '.profiles-table', 'css_element']);
    }
    
    /**
     * Checks if a visual profile does not exist
     *
     * @Then /^I should not see the visual profile "([^"]*)"$/
     * @param string $profilename
     */
    public function i_should_not_see_the_visual_profile($profilename) {
        $this->execute('behat_general::assert_element_not_contains_text', [$profilename, '.profiles-table', 'css_element']);
    }
    
    /**
     * Fills in color fields with color picker support
     *
     * @When /^I set the "([^"]*)" color to "([^"]*)"$/
     * @param string $colortype
     * @param string $colorvalue
     */
    public function i_set_the_color_to($colortype, $colorvalue) {
        $fieldmap = [
            'primary' => 'primarycolor',
            'secondary' => 'secondarycolor',
            'background' => 'backgroundcolor'
        ];
        
        $fieldname = $fieldmap[strtolower($colortype)] ?? $colortype;
        
        $this->execute('behat_forms::i_set_the_field_to', [$fieldname, $colorvalue]);
    }
    
    /**
     * Checks color preview
     *
     * @Then /^I should see the color "([^"]*)" in the preview$/
     * @param string $color
     */
    public function i_should_see_the_color_in_the_preview($color) {
        $this->execute('behat_general::assert_element_contains_text', [$color, '.color-samples', 'css_element']);
    }
    
    /**
     * Waits for the preview to update
     *
     * @When /^I wait for the preview to update$/
     */
    public function i_wait_for_the_preview_to_update() {
        $this->getSession()->wait(1000);
    }
    
    /**
     * Clicks on the profile preview button
     *
     * @When /^I click on the preview button for "([^"]*)"$/
     * @param string $profilename
     */
    public function i_click_on_the_preview_button_for($profilename) {
        $xpath = "//tr[contains(., '$profilename')]//a[contains(@class, 'profile-preview-btn')]";
        $this->execute('behat_general::i_click_on', [$xpath, 'xpath_element']);
    }
    
    /**
     * Checks if contrast validation shows a specific message
     *
     * @Then /^I should see contrast validation "(pass|warning|fail)"$/
     * @param string $status
     */
    public function i_should_see_contrast_validation($status) {
        $class = '';
        switch ($status) {
            case 'pass':
                $class = 'alert-success';
                break;
            case 'warning':
                $class = 'alert-warning';
                break;
            case 'fail':
                $class = 'alert-danger';
                break;
        }
        
        $this->execute('behat_general::should_exist', ["#contrast-validation.$class", 'css_element']);
    }
    
    /**
     * Checks profile usage count
     *
     * @Then /^the profile "([^"]*)" should be used in (\d+) courses?$/
     * @param string $profilename
     * @param int $count
     */
    public function the_profile_should_be_used_in_courses($profilename, $count) {
        $text = $count == 0 ? 'Not used' : "Used in $count course(s)";
        $xpath = "//tr[contains(., '$profilename')]//*[contains(text(), '$text')]";
        $this->execute('behat_general::should_exist', [$xpath, 'xpath_element']);
    }
    
    /**
     * Navigate to visual profiles management
     *
     * @Given /^I navigate to visual profiles management$/
     */
    public function i_navigate_to_visual_profiles_management() {
        $this->execute('behat_navigation::i_navigate_to_in_site_administration', 
            ['Appearance > ShiftClass > Visual Profiles']);
        $this->execute('behat_general::i_click_on', ['Manage Visual Profiles', 'link']);
    }
}