jQuery(document).ready(function($){
//  $('.widgets-holder-wrap').on('click', '.sidebar-name', function() {
    $('.postbox-container').on('click', '.sidebar-name', function() {

        var $thisParent = $(this).parent(),
            $thisContent = $thisParent.find('.sidebar-content');

        // Close the other widgets before opening selected widget
        if ( !$thisParent.hasClass('exclude' ) ) {
            $('.sidebar-name').each(function() {

                // Get parent
                var $parent = $(this).parent();

                // Close the widget
                if ( !$parent.hasClass('exclude') && !$parent.hasClass('closed') ) {
                    $parent.find('.sidebar-content').slideUp(200, function() {
                        $parent.addClass('closed');
                    });
                }

            });
        }

        // Open/close the widget
        if ( $thisParent.hasClass('closed') )
            $thisContent.slideDown(200, function() {
                $thisParent.removeClass('closed');
            });
        else
            $thisContent.slideUp(200, function() {
                $thisParent.addClass('closed');
            });

    });
});