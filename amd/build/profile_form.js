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
 * Profile Form JavaScript for ShiftClass theme
 *
 * @module     theme_shiftclass/profile_form
 * @copyright  2025 Rodrigo Padilha Silveira
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/str'], function($, Str) {
    
    var init = function(initialColors) {
        // Aguardar DOM estar pronto
        $(document).ready(function() {
            console.log('Profile form init with colors:', initialColors);
            
            // Sincronizar valores iniciais se estiver editando
            if (initialColors && initialColors.isediting) {
                syncInitialValues(initialColors);
            }
            
            initColorSync();
            updatePreview();
            validateContrast();
        });
    };
    
    var syncInitialValues = function(initialColors) {
        // Sincronizar os color pickers com os valores do formulário
        var colorFields = ['primarycolor', 'secondarycolor', 'backgroundcolor'];
        
        colorFields.forEach(function(fieldName) {
            var textInput = $('input[name="' + fieldName + '"]');
            var picker = $('.color-picker[data-target="' + fieldName + '"]');
            var preview = picker.next('.color-preview');
            
            // Obter valor atual do campo de texto
            var currentValue = textInput.val();
            
            if (currentValue && /^#[0-9A-Fa-f]{6}$/i.test(currentValue)) {
                // Sincronizar color picker com o valor do texto
                picker.val(currentValue.toUpperCase());
                preview.css('background-color', currentValue);
                console.log('Synced ' + fieldName + ' to:', currentValue);
            } else if (initialColors[fieldName]) {
                // Usar valor inicial se campo estiver vazio
                var initialValue = initialColors[fieldName];
                textInput.val(initialValue);
                picker.val(initialValue);
                preview.css('background-color', initialValue);
                console.log('Set initial ' + fieldName + ' to:', initialValue);
            }
        });
    };
    
    var initColorSync = function() {
        $('.color-picker').each(function() {
            var picker = $(this);
            var targetName = picker.data('target');
            var textInput = $('input[name="' + targetName + '"]');
            
            // Sync picker to text input
            picker.on('input', function() {
                textInput.val(picker.val().toUpperCase());
                updatePreview();
                validateContrast();
            });
            
            // Sync text input to picker
            textInput.on('input', function() {
                var value = textInput.val();
                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    picker.val(value);
                    updatePreview();
                    validateContrast();
                }
            });
        });
    };
    
    var updatePreview = function() {
        var preview = document.getElementById('profile-preview');
        if (!preview) {
            console.log('Preview element not found');
            return;
        }
        
        var primary = $('input[name="primarycolor"]').val() || '#0066CC';
        var secondary = $('input[name="secondarycolor"]').val() || '#004499';
        var background = $('input[name="backgroundcolor"]').val() || '#F0F5FF';
        
        preview.style.setProperty('--preview-primary', primary);
        preview.style.setProperty('--preview-secondary', secondary);
        preview.style.setProperty('--preview-background', background);
        
        console.log('Preview updated:', {primary, secondary, background});
    };
    
    var validateContrast = function() {
        var primary = $('input[name="primarycolor"]').val();
        var background = $('input[name="backgroundcolor"]').val();
        var validation = $('#contrast-validation');
        
        if (!validation.length || !primary || !background) {
            return;
        }
        
        // Calculate contrast ratio
        var ratio = calculateContrastRatio(primary, background);
        
        Str.get_strings([
            {key: 'contrastpass', component: 'theme_shiftclass'},
            {key: 'contrastwarning', component: 'theme_shiftclass'},
            {key: 'contrastfail', component: 'theme_shiftclass'}
        ]).done(function(strings) {
            validation.removeClass('alert-success alert-warning alert-danger');
            
            if (ratio >= 4.5) {
                validation.addClass('alert-success');
                validation.html('<i class="fa fa-check-circle"></i> ' + strings[0] + ' (Ratio: ' + ratio.toFixed(2) + ':1)');
            } else if (ratio >= 3) {
                validation.addClass('alert-warning');
                validation.html('<i class="fa fa-exclamation-triangle"></i> ' + strings[1] + ' (Ratio: ' + ratio.toFixed(2) + ':1)');
            } else {
                validation.addClass('alert-danger');
                validation.html('<i class="fa fa-times-circle"></i> ' + strings[2] + ' (Ratio: ' + ratio.toFixed(2) + ':1)');
            }
        }).fail(function() {
            // Fallback if strings fail to load
            validation.removeClass('alert-success alert-warning alert-danger');
            if (ratio >= 4.5) {
                validation.addClass('alert-success');
                validation.html('<i class="fa fa-check-circle"></i> Good contrast (Ratio: ' + ratio.toFixed(2) + ':1)');
            } else if (ratio >= 3) {
                validation.addClass('alert-warning');
                validation.html('<i class="fa fa-exclamation-triangle"></i> Adequate contrast (Ratio: ' + ratio.toFixed(2) + ':1)');
            } else {
                validation.addClass('alert-danger');
                validation.html('<i class="fa fa-times-circle"></i> Poor contrast (Ratio: ' + ratio.toFixed(2) + ':1)');
            }
        });
    };
    
    var calculateContrastRatio = function(color1, color2) {
        // Convert hex to RGB
        var rgb1 = hexToRgb(color1);
        var rgb2 = hexToRgb(color2);
        
        if (!rgb1 || !rgb2) {
            return 1; // Fallback if colors invalid
        }
        
        // Calculate relative luminance
        var l1 = calculateLuminance(rgb1);
        var l2 = calculateLuminance(rgb2);
        
        // Calculate contrast ratio
        var ratio = (l1 > l2) ? (l1 + 0.05) / (l2 + 0.05) : (l2 + 0.05) / (l1 + 0.05);
        
        return ratio;
    };
    
    var hexToRgb = function(hex) {
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    };
    
    var calculateLuminance = function(rgb) {
        var sRGB = [rgb.r / 255, rgb.g / 255, rgb.b / 255];
        var linearRGB = sRGB.map(function(val) {
            return (val <= 0.03928) ? val / 12.92 : Math.pow((val + 0.055) / 1.055, 2.4);
        });
        
        return 0.2126 * linearRGB[0] + 0.7152 * linearRGB[1] + 0.0722 * linearRGB[2];
    };
    
    return {
        init: init
    };
});