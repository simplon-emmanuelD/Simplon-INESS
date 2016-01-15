/*global console,ajaxurl,$,jQuery*/

/**
 *
 */
jQuery(function ($) {
    "use strict";

    if ($('#codemirror').length) {
        var codeMirror = CodeMirror.fromTextArea(document.getElementById('codemirror'), {
            tabMode: 'indent',
            lineNumbers: true,
            lineWrapping: true,
            onChange: function(cm) {
                cm.save();
            }
        });
    }

    $('.mega-custom_styling > h4').on('click', function() {
        setTimeout( function() {
            $('.mega-custom_styling').find('.CodeMirror').each(function(key, value) {
                value.CodeMirror.refresh();
            });
        }, 160);
    });

    $(".mm_colorpicker").spectrum({
        preferredFormat: "rgb",
        showInput: true,
        showAlpha: true,
        clickoutFiresChange: true,
        change: function(color) {
            if (color.getAlpha() === 0) {
                $(this).siblings('div.chosen-color').html('transparent');
            } else {
                $(this).siblings('div.chosen-color').html(color.toRgbString());
            }
        }
    });

    $(".confirm").on("click", function() {
        return confirm(megamenu_settings.confirm);
    });

    $('#theme_selector').bind('change', function () {
        var url = $(this).val();
        if (url) {
            window.location = url;
        }
        return false;
    });

    $('.mega-location-header').on("click", function(e) {
        if (e.target.nodeName.toLowerCase() != 'a') {
            $(this).parent().toggleClass('mega-closed').toggleClass('mega-open');
            $(this).siblings('.mega-inner').slideToggle();
        }
    });

    $('.icon_dropdown').on("change", function() {
        var icon = $("option:selected", $(this)).attr('data-class');
        // clear and add selected dashicon class
        $(this).next('.selected_icon').removeClass().addClass(icon).addClass('selected_icon');
    });

    $('select#mega_css').on("change", function() {
        var select = $(this);
        var selected = $(this).val();
        select.next().children().hide();
        select.next().children('.' + selected).show();
    });

    $('form.theme_editor label[data-validation]').each(function() {
        var label = $(this);
        var validation = label.attr('data-validation');
        var error_message = label.siblings( '.mega-validation-message-' + label.attr('class') );
        var input = $('input', label);

        input.on('blur', function() {

            var value = $(this).val();

            if ( ( validation == 'int' && Math.floor(value) != value )
              || ( validation == 'px' && ! ( value.substr(value.length - 2) == 'px' || value.substr(value.length - 2) == 'em' || value.substr(value.length - 2) == 'pt' || value.substr(value.length - 3) == 'rem' || value.substr(value.length - 1) == '%' ) && value != 0 )
              || ( validation == 'float' && ! $.isNumeric(value) ) ) {
                label.addClass('mega-error');
                error_message.show();
            } else {
                label.removeClass('mega-error');
                label.siblings( '.mega-validation-message-' + label.attr('class') ).hide();
            }

        });

    });

});