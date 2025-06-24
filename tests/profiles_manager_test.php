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
 * Unit tests for profiles manager
 *
 * @package    theme_shiftclass
 * @category   test
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_shiftclass;

use advanced_testcase;
use stdClass;
use moodle_exception;

/**
 * Test class for profiles_manager
 * 
 * @coversDefaultClass \theme_shiftclass\profiles_manager
 */
class profiles_manager_test extends advanced_testcase {
    
    /** @var profiles_manager */
    private $manager;
    
    /**
     * Set up test
     */
    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest(true);
        $this->manager = new profiles_manager();
    }
    
    /**
     * Test profile creation
     * 
     * @covers ::create_profile
     */
    public function test_create_profile() {
        global $DB;
        
        $data = new stdClass();
        $data->name = 'Test Profile';
        $data->primarycolor = '#FF0000';
        $data->secondarycolor = '#00FF00';
        $data->backgroundcolor = '#0000FF';
        
        $profileid = $this->manager->create_profile($data);
        
        $this->assertIsInt($profileid);
        $this->assertGreaterThan(0, $profileid);
        
        // Verify profile was created
        $profile = $DB->get_record('theme_shiftclass_profiles', ['id' => $profileid]);
        $this->assertNotFalse($profile);
        $this->assertEquals('Test Profile', $profile->name);
        $this->assertEquals('#FF0000', $profile->primarycolor);
        $this->assertEquals('#00FF00', $profile->secondarycolor);
        $this->assertEquals('#0000FF', $profile->backgroundcolor);
    }
    
    /**
     * Test profile creation with invalid data
     * 
     * @covers ::create_profile
     */
    public function test_create_profile_invalid_data() {
        // Test missing name
        $data = new stdClass();
        $data->primarycolor = '#FF0000';
        $data->secondarycolor = '#00FF00';
        $data->backgroundcolor = '#0000FF';
        
        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessage('error:profilenamerequired');
        $this->manager->create_profile($data);
    }
    
    /**
     * Test profile creation with invalid color
     * 
     * @covers ::create_profile
     */
    public function test_create_profile_invalid_color() {
        $data = new stdClass();
        $data->name = 'Test Profile';
        $data->primarycolor = 'invalid';
        $data->secondarycolor = '#00FF00';
        $data->backgroundcolor = '#0000FF';
        
        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessage('error:invalidcolor');
        $this->manager->create_profile($data);
    }
    
    /**
     * Test duplicate profile name
     * 
     * @covers ::create_profile
     */
    public function test_create_profile_duplicate_name() {
        $data = new stdClass();
        $data->name = 'Duplicate Test';
        $data->primarycolor = '#FF0000';
        $data->secondarycolor = '#00FF00';
        $data->backgroundcolor = '#0000FF';
        
        // Create first profile
        $this->manager->create_profile($data);
        
        // Try to create duplicate
        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessage('error:duplicateprofilename');
        $this->manager->create_profile($data);
    }
    
    /**
     * Test profile update
     * 
     * @covers ::update_profile
     */
    public function test_update_profile() {
        // Create profile
        $data = new stdClass();
        $data->name = 'Original Name';
        $data->primarycolor = '#FF0000';
        $data->secondarycolor = '#00FF00';
        $data->backgroundcolor = '#0000FF';
        
        $profileid = $this->manager->create_profile($data);
        
        // Update profile
        $newdata = new stdClass();
        $newdata->name = 'Updated Name';
        $newdata->primarycolor = '#000000';
        $newdata->secondarycolor = '#FFFFFF';
        $newdata->backgroundcolor = '#888888';
        
        $result = $this->manager->update_profile($profileid, $newdata);
        $this->assertTrue($result);
        
        // Verify update
        $profile = $this->manager->get_profile($profileid);
        $this->assertEquals('Updated Name', $profile->name);
        $this->assertEquals('#000000', $profile->primarycolor);
        $this->assertEquals('#FFFFFF', $profile->secondarycolor);
        $this->assertEquals('#888888', $profile->backgroundcolor);
    }
    
    /**
     * Test profile deletion
     * 
     * @covers ::delete_profile
     */
    public function test_delete_profile() {
        // Create profile
        $data = new stdClass();
        $data->name = 'To Delete';
        $data->primarycolor = '#FF0000';
        $data->secondarycolor = '#00FF00';
        $data->backgroundcolor = '#0000FF';
        
        $profileid = $this->manager->create_profile($data);
        
        // Delete profile
        $result = $this->manager->delete_profile($profileid);
        $this->assertTrue($result);
        
        // Verify deletion
        $profile = $this->manager->get_profile($profileid);
        $this->assertFalse($profile);
    }
    
    /**
     * Test delete profile in use
     * 
     * @covers ::delete_profile
     */
    public function test_delete_profile_in_use() {
        global $DB;
        
        // Create profile
        $data = new stdClass();
        $data->name = 'In Use';
        $data->primarycolor = '#FF0000';
        $data->secondarycolor = '#00FF00';
        $data->backgroundcolor = '#0000FF';
        
        $profileid = $this->manager->create_profile($data);
        
        // Create a course and assign profile
        $course = $this->getDataGenerator()->create_course();
        $this->manager->assign_profile_to_course($course->id, $profileid);
        
        // Try to delete
        $this->expectException(moodle_exception::class);
        $this->expectExceptionMessage('error:profileinuse');
        $this->manager->delete_profile($profileid);
    }
    
    /**
     * Test get all profiles
     * 
     * @covers ::get_all_profiles
     */
    public function test_get_all_profiles() {
        // Create multiple profiles
        $names = ['Profile A', 'Profile B', 'Profile C'];
        
        foreach ($names as $name) {
            $data = new stdClass();
            $data->name = $name;
            $data->primarycolor = '#FF0000';
            $data->secondarycolor = '#00FF00';
            $data->backgroundcolor = '#0000FF';
            $this->manager->create_profile($data);
        }
        
        $profiles = $this->manager->get_all_profiles();
        
        $this->assertCount(3, $profiles);
        
        // Verify alphabetical order
        $profilenames = array_values(array_map(function($p) { return $p->name; }, $profiles));
        $this->assertEquals(['Profile A', 'Profile B', 'Profile C'], $profilenames);
    }
    
    /**
     * Test assign profile to course
     * 
     * @covers ::assign_profile_to_course
     */
    public function test_assign_profile_to_course() {
        // Create profile
        $data = new stdClass();
        $data->name = 'Course Profile';
        $data->primarycolor = '#FF0000';
        $data->secondarycolor = '#00FF00';
        $data->backgroundcolor = '#0000FF';
        
        $profileid = $this->manager->create_profile($data);
        
        // Create course
        $course = $this->getDataGenerator()->create_course();
        
        // Assign profile
        $result = $this->manager->assign_profile_to_course($course->id, $profileid);
        $this->assertTrue($result);
        
        // Verify assignment
        $courseprofile = $this->manager->get_course_profile($course->id);
        $this->assertNotFalse($courseprofile);
        $this->assertEquals($profileid, $courseprofile->id);
    }
    
    /**
     * Test remove profile from course
     * 
     * @covers ::assign_profile_to_course
     */
    public function test_remove_profile_from_course() {
        // Create and assign profile
        $data = new stdClass();
        $data->name = 'Course Profile';
        $data->primarycolor = '#FF0000';
        $data->secondarycolor = '#00FF00';
        $data->backgroundcolor = '#0000FF';
        
        $profileid = $this->manager->create_profile($data);
        $course = $this->getDataGenerator()->create_course();
        $this->manager->assign_profile_to_course($course->id, $profileid);
        
        // Remove profile (assign 0)
        $result = $this->manager->assign_profile_to_course($course->id, 0);
        $this->assertTrue($result);
        
        // Verify removal
        $courseprofile = $this->manager->get_course_profile($course->id);
        $this->assertFalse($courseprofile);
    }
    
    /**
     * Test profile usage count
     * 
     * @covers ::get_profile_usage_count
     */
    public function test_get_profile_usage_count() {
        // Create profile
        $data = new stdClass();
        $data->name = 'Popular Profile';
        $data->primarycolor = '#FF0000';
        $data->secondarycolor = '#00FF00';
        $data->backgroundcolor = '#0000FF';
        
        $profileid = $this->manager->create_profile($data);
        
        // Initial count should be 0
        $count = $this->manager->get_profile_usage_count($profileid);
        $this->assertEquals(0, $count);
        
        // Create and assign to multiple courses
        for ($i = 1; $i <= 3; $i++) {
            $course = $this->getDataGenerator()->create_course();
            $this->manager->assign_profile_to_course($course->id, $profileid);
        }
        
        // Count should be 3
        $count = $this->manager->get_profile_usage_count($profileid);
        $this->assertEquals(3, $count);
    }
    
    /**
     * Test contrast ratio calculation
     * 
     * @covers ::calculate_contrast_ratio
     */
    public function test_calculate_contrast_ratio() {
        // Test black on white (should be 21:1)
        $ratio = $this->manager->calculate_contrast_ratio('#000000', '#FFFFFF');
        $this->assertEqualsWithDelta(21, $ratio, 0.1);
        
        // Test white on black (should be 21:1)
        $ratio = $this->manager->calculate_contrast_ratio('#FFFFFF', '#000000');
        $this->assertEqualsWithDelta(21, $ratio, 0.1);
        
        // Test gray on gray (should be 1:1)
        $ratio = $this->manager->calculate_contrast_ratio('#888888', '#888888');
        $this->assertEqualsWithDelta(1, $ratio, 0.1);
    }
    
    /**
     * Test install default profiles
     * 
     * @covers ::install_default_profiles
     */
    public function test_install_default_profiles() {
        $result = $this->manager->install_default_profiles();
        $this->assertTrue($result);
        
        $profiles = $this->manager->get_all_profiles();
        $this->assertCount(4, $profiles);
        
        // Check that default profiles exist
        $names = array_map(function($p) { return $p->name; }, $profiles);
        $this->assertContains(get_string('profile:corporate_blue', 'theme_shiftclass'), $names);
        $this->assertContains(get_string('profile:nature_green', 'theme_shiftclass'), $names);
        $this->assertContains(get_string('profile:modern_purple', 'theme_shiftclass'), $names);
        $this->assertContains(get_string('profile:dynamic_orange', 'theme_shiftclass'), $names);
    }
    
    /**
     * Test cache functionality
     * 
     * @covers ::get_all_profiles
     */
    public function test_cache_functionality() {
        // Create a profile
        $data = new stdClass();
        $data->name = 'Cache Test';
        $data->primarycolor = '#FF0000';
        $data->secondarycolor = '#00FF00';
        $data->backgroundcolor = '#0000FF';
        
        $profileid = $this->manager->create_profile($data);
        
        // Get profiles (should cache)
        $profiles1 = $this->manager->get_all_profiles();
        
        // Get profiles again (should use cache)
        $profiles2 = $this->manager->get_all_profiles();
        
        // Both should be identical
        $this->assertEquals($profiles1, $profiles2);
        
        // Update profile (should clear cache)
        $data->name = 'Cache Test Updated';
        $this->manager->update_profile($profileid, $data);
        
        // Get profiles again (should not use cache)
        $profiles3 = $this->manager->get_all_profiles();
        
        // Name should be updated
        $found = false;
        foreach ($profiles3 as $profile) {
            if ($profile->id == $profileid) {
                $this->assertEquals('Cache Test Updated', $profile->name);
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }
}