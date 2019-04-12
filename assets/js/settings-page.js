/**
 * Main script file for
 * the settings page.
 * 
 * @author Willon Nava
 */
jQuery(function($) {

    'use strict';

    $(".tabs").tabslet({
        container: '.tabs-container',
    });

    $("#driftadm-settings-form").css( 'opacity', 1);

    var radioButtons = $(".radio-choice input[type='radio']");

    changeLoginBgType();
    radioButtons.click(changeLoginBgType);

    function changeLoginBgType(e) {

        if (!e) {
            $(".choice-container").hide();

            var checkedChoice = $(".login-bg-choices input:checked").data('choice');
            $(`.${checkedChoice}`).show();

            return;
        }

        var target = e.target;

        var findClass = $(target).data("choice");
        var containers = $(target).parent().parent().parent().find(".choice-container");
        var container = $(`.${findClass}`);

        containers.hide();
        container.show();
    }

    /**
     * Main functionality for the 
     * login page settings section.
     */
    var login_page = {

        init: function() {
            this.colorInput = document.getElementById('login_bg_color_preview');
            this.colorPreview = $('#color-preview');
            this.triggerColorPicker = this.triggerColorPicker.bind( this );
            this.removeImageButton = $(".remove-img");

            // Init functions
            this.triggerColorPicker(this.colorInput, this.colorPreview);
            this.removeImageButton.click(this.removeImage);
        },

        /**
         * Triggers color picker lib
         * @see https://tovic.github.io/color-picker/
         */
        triggerColorPicker: function(field, preview) {
            var colorPicker = new CP(field);
            var colorValue = document.getElementById("login_bg_color").value;
            field.value = colorValue;
            preview.css('background-color', colorValue);

            colorPicker.on('drag', function(color) {
                var colorHex = '#' + color;
                field.value = colorHex;
                document.getElementById("login_bg_color").value = colorHex;
    
                preview.css('background-color', colorHex);
            });
        },
        removeImage: function(e) {
            let image = $(e.target).parent().find('img');
            image.prop('src', '');
            $("#login_logo").val(image.attr('src'));
        }
    }

    login_page.init();
});
