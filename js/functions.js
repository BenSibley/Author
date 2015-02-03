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
                }
            });
        } else {
            sidebar.addClass('open');
        }
    }

    // display the dropdown menus
    $('.toggle-dropdown').on('click', openDropdownMenu);

    function openDropdownMenu() {

        var menuItem = $(this).parent();

        if( menuItem.hasClass('open') ) {
            menuItem.removeClass('open');
        } else {
            menuItem.addClass('open');
        }
    }

    function positionSidebar() {

        var windowWidth = $(window).width();

        // if at width when menu is absolutely positioned
        if( windowWidth > 549 && windowWidth < 950 ) {

            // get the selector for the primary menu
            if( $('.menu-unset').length ) {
                var menu = $('.menu-unset');
            } else {
                var menu = $('#menu-primary-items');
            }
            var menuHeight = menu.outerHeight();
            var headerHeight = $('#main-sidebar').outerHeight();

            // below the header and menu + 24 for margin
            $('#sidebar-primary').css('top', headerHeight + menuHeight + 24 + 'px');
        }
        else {
            $('#sidebar-primary').css('top', '');
        }
    }
    positionSidebar();

    $(window).resize(function(){
        positionSidebar();
    });

});