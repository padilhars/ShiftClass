define(['jquery', 'core/str'], function($, Str) {
    
    var init = function() {
        // Aguardar DOM estar pronto
        $(document).ready(function() {
            initColorSync();
            updatePreview();
            validateContrast();
        });
    };
    
    var initColorSync = function() {
        // Initialize color synchronization
        $('.color-picker').each(function() {
            var picker = $(this);
            var targetName = picker.data('target');
            var textInput = $('input[name="' + targetName + '"]');
            var preview = picker.next('.color-preview');
            
            // Sync picker to text input
            picker.on('input', function() {
                textInput.val(picker.val().toUpperCase());
                preview.css('background-color', picker.val());
                updatePreview();
                validateContrast();
            });
            
            // Sync text input to picker
            textInput.on('input', function() {
                var value = textInput.val();
                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    picker.val(value);
                    preview.css('background-color', value);
                    updatePreview();
                    validateContrast();
                }
            });
        });
    };
    
    var updatePreview = function() {
        var preview = $('#profile-preview');
        if (!preview.length) return;
        
        var primary = $('input[name="primarycolor"]').val();
        var secondary = $('input[name="secondarycolor"]').val();
        var background = $('input[name="backgroundcolor"]').val();
        
        preview.css('--preview-primary', primary);
        preview.css('--preview-secondary', secondary);
        preview.css('--preview-background', background);
    };
    
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
                validation.html('<i class="fa fa-check-circle"></i> ' + strings[0]);
            } else if (ratio >= 3) {
                validation.removeClass('alert-success alert-danger').addClass('alert-warning');
                validation.html('<i class="fa fa-exclamation-triangle"></i> ' + strings[1]);
            } else {
                validation.removeClass('alert-success alert-warning').addClass('alert-danger');
                validation.html('<i class="fa fa-times-circle"></i> ' + strings[2]);
            }
        });
    };
    
    var calculateContrastRatio = function(color1, color2) {
        // Implementação simplificada
        return 4.5; // Placeholder
    };
    
    return {
        init: init
    };
});