jQuery(document).ready(function($){


    // display the primary menu at mobile widths
    $('#toggle-navigation').on('click', openPrimaryMenu);

    function openPrimaryMenu() {

        var sidebar = $('#main-sidebar');

        // if menu open
        if( sidebar.hasClass('open') ) {

            // remove styling class
            sidebar.removeClass('open');

            // close all ULs by removing increased max-height
            $('#menu-primary, #menu-primary-items ul, .menu-unset ul').removeAttr('style');

            // close all ULs and require 2 clicks again when reopened
            $('.menu-item-has-children').each(function(){
                if( $(this).hasClass('open') ) {
                    $(this).removeClass('open');
                    $(this).addClass('closed');
                }
            });
        } else {

            // add styling class to reveal primary menu
            sidebar.addClass('open');

            // open to show whole menu plus 48px of padding for style
            //$('#menu-primary').css('max-height', menuHeight + 48);
        }
    }

});