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
 * Visual Profiles Manager JavaScript for ShiftClass theme
 *
 * @module     theme_shiftclass/profiles_manager
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/notification', 'core/str', 'core/modal_factory', 'core/modal_events', 'core/templates'], 
function($, Ajax, Notification, Str, ModalFactory, ModalEvents, Templates) {

    /**
     * Initialize the profiles manager
     */
    var init = function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Preview button handler
        $('.profile-preview-btn').on('click', function(e) {
            e.preventDefault();
            showProfilePreview($(this));
        });
        
        // Delete button handler
        $('.profile-delete-btn').on('click', function(e) {
            e.preventDefault();
            confirmDeleteProfile($(this));
        });
        
        // Live preview on edit page
        if ($('#profile-preview').length) {
            initializeLivePreview();
        }
        
        // AJAX profile operations
        initializeAjaxOperations();
    };
    
    /**
     * Show profile preview in modal
     * @param {jQuery} button Preview button
     */
    var showProfilePreview = function(button) {
        var profileId = button.data('profileid');
        var primary = button.data('primary');
        var secondary = button.data('secondary');
        var background = button.data('background');
        
        // Create preview HTML
        var previewHtml = `
            <div class="profile-preview-modal">
                <div class="preview-navbar" style="background-color: ${primary};">
                    <span class="preview-brand">Course Navigation</span>
                </div>
                <div class="preview-content" style="background-color: ${background};">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="preview-card">
                                    <h3>Course Content</h3>
                                    <p>This is how your course will look with this visual profile applied.</p>
                                    <button class="preview-btn-primary" style="background-color: ${primary};">
                                        Primary Action
                                    </button>
                                    <button class="preview-btn-secondary" style="background-color: ${secondary};">
                                        Secondary Action
                                    </button>
                                </div>
                                
                                <div class="preview-card mt-3">
                                    <h4>Activity Example</h4>
                                    <div class="activity-item">
                                        <i class="fa fa-file-text-o" style="color: ${primary};"></i>
                                        <span>Assignment: Introduction Essay</span>
                                    </div>
                                    <div class="activity-item">
                                        <i class="fa fa-comments-o" style="color: ${primary};"></i>
                                        <span>Forum: General Discussion</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="preview-card">
                                    <h5>Course Progress</h5>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 65%; background-color: ${primary};">
                                            65%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#profile-preview-content').html(previewHtml);
        $('#profilePreviewModal').modal('show');
    };
    
    /**
     * Confirm profile deletion
     * @param {jQuery} button Delete button
     */
    var confirmDeleteProfile = function(button) {
        var profileName = button.data('profilename');
        var deleteUrl = button.attr('href');
        
        Str.get_strings([
            {key: 'deleteprofile', component: 'theme_shiftclass'},
            {key: 'deleteprofileconfirm', component: 'theme_shiftclass', param: profileName},
            {key: 'delete', component: 'core'},
            {key: 'cancel', component: 'core'}
        ]).done(function(strings) {
            ModalFactory.create({
                type: ModalFactory.types.SAVE_CANCEL,
                title: strings[0],
                body: strings[1],
                buttons: {
                    save: strings[2],
                    cancel: strings[3]
                }
            }).done(function(modal) {
                modal.setSaveButtonText(strings[2]);
                
                // Handle save event
                modal.getRoot().on(ModalEvents.save, function() {
                    window.location.href = deleteUrl;
                });
                
                modal.show();
            });
        });
    };
    
    /**
     * Initialize live preview on edit page
     */
    var initializeLivePreview = function() {
        var previewContainer = $('#profile-preview');
        
        // Color input handlers
        $('.color-text-input').on('input', function() {
            var value = $(this).val();
            var name = $(this).attr('name');
            var picker = $('input[data-target="' + name + '"]');
            var preview = picker.next('.color-preview');
            
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                picker.val(value);
                preview.css('background-color', value);
                updateLivePreview();
                validateContrast();
            }
        });
        
        // Color picker handlers
        $('.color-picker').on('input', function() {
            var targetName = $(this).data('target');
            var textInput = $('input[name="' + targetName + '"]');
            var preview = $(this).next('.color-preview');
            var value = $(this).val();
            
            textInput.val(value.toUpperCase());
            preview.css('background-color', value);
            updateLivePreview();
            validateContrast();
        });
        
        // Initialize preview
        updateLivePreview();
        validateContrast();
    };
    
    /**
     * Update live preview
     */
    var updateLivePreview = function() {
        var preview = document.getElementById('profile-preview');
        if (!preview) return;
        
        var primary = $('input[name="primarycolor"]').val();
        var secondary = $('input[name="secondarycolor"]').val();
        var background = $('input[name="backgroundcolor"]').val();
        
        preview.style.setProperty('--preview-primary', primary);
        preview.style.setProperty('--preview-secondary', secondary);
        preview.style.setProperty('--preview-background', background);
    };
    
    /**
     * Validate color contrast for WCAG compliance
     */
    var validateContrast = function() {
        var primary = $('input[name="primarycolor"]').val();
        var background = $('input[name="backgroundcolor"]').val();
        var validation = $('#contrast-validation');
        
        if (!validation.length || !primary || !background) return;
        
        // Calculate contrast ratio
        var ratio = calculateContrastRatio(primary, background);
        
        Str.get_strings([
            {key: 'contrastpass', component: 'theme_shiftclass'},
            {key: 'contrastwarning', component: 'theme_shiftclass'},
            {key: 'contrastfail', component: 'theme_shiftclass'}
        ]).done(function(strings) {
            if (ratio >= 4.5) {
                validation.removeClass('alert-warning alert-danger').addClass('alert-success');
                validation.html('<i class="fa fa-check-circle"></i> ' + strings[0] + ' (Ratio: ' + ratio.toFixed(2) + ':1)');
            } else if (ratio >= 3) {
                validation.removeClass('alert-success alert-danger').addClass('alert-warning');
                validation.html('<i class="fa fa-exclamation-triangle"></i> ' + strings[1] + ' (Ratio: ' + ratio.toFixed(2) + ':1)');
            } else {
                validation.removeClass('alert-success alert-warning').addClass('alert-danger');
                validation.html('<i class="fa fa-times-circle"></i> ' + strings[2] + ' (Ratio: ' + ratio.toFixed(2) + ':1)');
            }
        });
    };
    
    /**
     * Calculate contrast ratio between two colors
     * @param {string} color1 Hex color
     * @param {string} color2 Hex color
     * @return {number} Contrast ratio
     */
    var calculateContrastRatio = function(color1, color2) {
        // Convert hex to RGB
        var rgb1 = hexToRgb(color1);
        var rgb2 = hexToRgb(color2);
        
        // Calculate relative luminance
        var l1 = calculateLuminance(rgb1);
        var l2 = calculateLuminance(rgb2);
        
        // Calculate contrast ratio
        var ratio = (l1 > l2) ? (l1 + 0.05) / (l2 + 0.05) : (l2 + 0.05) / (l1 + 0.05);
        
        return ratio;
    };
    
    /**
     * Convert hex color to RGB
     * @param {string} hex Hex color
     * @return {object} RGB values
     */
    var hexToRgb = function(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    };
    
    /**
     * Calculate relative luminance
     * @param {object} rgb RGB values
     * @return {number} Luminance
     */
    var calculateLuminance = function(rgb) {
        var sRGB = [rgb.r / 255, rgb.g / 255, rgb.b / 255];
        var linearRGB = sRGB.map(function(val) {
            return (val <= 0.03928) ? val / 12.92 : Math.pow((val + 0.055) / 1.055, 2.4);
        });
        
        return 0.2126 * linearRGB[0] + 0.7152 * linearRGB[1] + 0.0722 * linearRGB[2];
    };
    
    /**
     * Initialize AJAX operations
     */
    var initializeAjaxOperations = function() {
        // Quick create profile
        $('#quick-create-profile').on('submit', function(e) {
            e.preventDefault();
            
            var formData = $(this).serialize();
            
            Ajax.call([{
                methodname: 'theme_shiftclass_create_profile',
                args: {
                    name: $('#profile-name').val(),
                    primarycolor: $('#primary-color').val(),
                    secondarycolor: $('#secondary-color').val(),
                    backgroundcolor: $('#background-color').val()
                },
                done: function(response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        Notification.alert('Error', response.message, 'OK');
                    }
                },
                fail: Notification.exception
            }]);
        });
        
        // Export profiles
        $('#export-profiles').on('click', function(e) {
            e.preventDefault();
            
            Ajax.call([{
                methodname: 'theme_shiftclass_export_profiles',
                args: {},
                done: function(response) {
                    // Create download link
                    var blob = new Blob([JSON.stringify(response.profiles, null, 2)], {type: 'application/json'});
                    var url = window.URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = 'shiftclass_profiles_' + new Date().toISOString().slice(0, 10) + '.json';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                },
                fail: Notification.exception
            }]);
        });
        
        // Import profiles
        $('#import-profiles').on('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;
            
            var reader = new FileReader();
            reader.onload = function(e) {
                try {
                    var profiles = JSON.parse(e.target.result);
                    
                    Ajax.call([{
                        methodname: 'theme_shiftclass_import_profiles',
                        args: {profiles: profiles},
                        done: function(response) {
                            if (response.success) {
                                Notification.addNotification({
                                    message: response.message,
                                    type: 'success'
                                });
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                Notification.alert('Error', response.message, 'OK');
                            }
                        },
                        fail: Notification.exception
                    }]);
                } catch (error) {
                    Notification.alert('Error', 'Invalid file format', 'OK');
                }
            };
            reader.readAsText(file);
        });
    };
    
    return {
        init: init
    };
});