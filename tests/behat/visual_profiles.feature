# This file is part of Moodle - http://moodle.org/
#
# Moodle is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# Moodle is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
#
# Tests for visual profiles in ShiftClass theme
#
# @package    theme_shiftclass
# @category   test
# @copyright  2025 Rodrigo Padilha Silveira
# @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later

@theme @theme_shiftclass @javascript
Feature: Visual profiles management
  In order to customize course appearance
  As an admin
  I need to create and manage visual profiles

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | admin1   | Admin     | User     | admin1@example.com   |
      | teacher1 | Teacher   | One      | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
      | Course 2 | C2        | 0        |
    And I log in as "admin"
    And I navigate to visual profiles management

  Scenario: Create a new visual profile
    When I click on "Create new profile" "link"
    And I set the following fields to these values:
      | Profile name | Ocean Blue |
    And I set the "primary" color to "#0066CC"
    And I set the "secondary" color to "#0044AA"
    And I set the "background" color to "#E6F2FF"
    And I wait for the preview to update
    Then I should see contrast validation "pass"
    When I press "Save changes"
    Then I should see "Profile created successfully"
    And I should see the visual profile "Ocean Blue"

  Scenario: Edit an existing visual profile
    Given the following visual profiles exist:
      | name        | primarycolor | secondarycolor | backgroundcolor |
      | Test Profile | #FF0000     | #CC0000        | #FFEEEE         |
    When I click on "Edit" "link" in the "Test Profile" "table_row"
    And I set the field "Profile name" to "Updated Profile"
    And I set the "primary" color to "#00AA00"
    And I press "Save changes"
    Then I should see "Profile updated successfully"
    And I should see the visual profile "Updated Profile"
    And I should not see the visual profile "Test Profile"

  Scenario: Delete an unused visual profile
    Given the following visual profiles exist:
      | name           | primarycolor | secondarycolor | backgroundcolor |
      | Delete Me      | #FF0000      | #CC0000        | #FFEEEE         |
    When I click on "Delete" "link" in the "Delete Me" "table_row"
    Then I should see "Are you sure you want to delete the profile"
    When I press "Continue"
    Then I should see "Profile deleted successfully"
    And I should not see the visual profile "Delete Me"

  Scenario: Cannot delete a profile in use
    Given the following visual profiles exist:
      | name        | primarycolor | secondarycolor | backgroundcolor |
      | Used Profile | #FF0000     | #CC0000        | #FFEEEE         |
    And the course "Course 1" uses the visual profile "Used Profile"
    When I go to the visual profiles management page
    Then I should see "Delete" "link" is disabled in the "Used Profile" "table_row"
    And the profile "Used Profile" should be used in 1 course

  Scenario: Preview a visual profile
    Given the following visual profiles exist:
      | name           | primarycolor | secondarycolor | backgroundcolor |
      | Preview This   | #6B4C93      | #483D8B        | #F5F0FF         |
    When I click on the preview button for "Preview This"
    Then I should see "Profile Preview" in the ".modal-title" "css_element"
    And I should see "Course Navigation" in the ".preview-navbar" "css_element"

  Scenario: Install default profiles
    Given I am on the visual profiles management page
    And no visual profiles exist
    When I click on "Install default profiles" "link"
    Then I should see "Default profiles installed successfully"
    And I should see the visual profile "Corporate Blue"
    And I should see the visual profile "Nature Green"
    And I should see the visual profile "Modern Purple"
    And I should see the visual profile "Dynamic Orange"

  Scenario: Validate color contrast
    When I click on "Create new profile" "link"
    And I set the following fields to these values:
      | Profile name | Poor Contrast |
    And I set the "primary" color to "#FFFF00"
    And I set the "secondary" color to "#FFFFCC"
    And I set the "background" color to "#FFFFF0"
    And I wait for the preview to update
    Then I should see contrast validation "fail"

  Scenario: Check profile statistics
    Given the following visual profiles exist:
      | name      | primarycolor | secondarycolor | backgroundcolor |
      | Profile A | #FF0000      | #CC0000        | #FFEEEE         |
      | Profile B | #00FF00      | #00CC00        | #EEFFEE         |
      | Profile C | #0000FF      | #0000CC        | #EEEEFF         |
    And the course "Course 1" uses the visual profile "Profile A"
    And the course "Course 2" uses the visual profile "Profile A"
    When I am on the visual profiles management page
    Then I should see "Total profiles" in the ".stat-label" "css_element"
    And I should see "3" in the ".stat-value" "css_element"
    And the profile "Profile A" should be used in 2 courses
    And the profile "Profile B" should be used in 0 courses

  Scenario: Search for profiles
    Given the following visual profiles exist:
      | name          | primarycolor | secondarycolor | backgroundcolor |
      | Blue Sky      | #0066CC      | #0044AA        | #E6F2FF         |
      | Green Forest  | #00AA00      | #008800        | #E6FFE6         |
      | Red Sunset    | #CC0000      | #AA0000        | #FFE6E6         |
    When I set the field "Search profiles" to "Blue"
    Then I should see the visual profile "Blue Sky"
    And I should not see the visual profile "Green Forest"
    And I should not see the visual profile "Red Sunset"

  Scenario: Export and import profiles
    Given the following visual profiles exist:
      | name        | primarycolor | secondarycolor | backgroundcolor |
      | Export Me 1 | #FF0000      | #CC0000        | #FFEEEE         |
      | Export Me 2 | #00FF00      | #00CC00        | #EEFFEE         |
    When I click on "Export profiles" "link"
    Then I should download a file named "shiftclass_profiles.json"
    When I navigate to visual profiles management
    And I click on "Import profiles" "link"
    And I upload "shiftclass_profiles.json" file to "Profiles file" filemanager
    And I press "Import"
    Then I should see "Profiles imported successfully"