jQuery(document).ready(function($){

    positionSidebar();
    mainMinHeight();

    $(window).resize(function(){
        positionSidebar();
        closeMainSidebar();
        mainMinHeight();
    });

    $('.post-content').fitVids();

    // display the primary menu at mobile widths
    $('#toggle-navigation').on('click', openPrimaryMenu);

    // display the dropdown menus
    $('.toggle-dropdown').on('click', openDropdownMenu);

    // push down sidebar when dropdown menu opened
    $('.toggle-dropdown').on('click', adjustSidebarPosition);

    function openPrimaryMenu() {

        var sidebar = $('#main-sidebar');

        // if menu open
        if( sidebar.hasClass('open') ) {

            // remove styling class
            sidebar.removeClass('open');

            // close all ULs by removing increased max-height
            $('#menu-primary-items ul, .menu-unset ul').removeAttr('style');

            // close all ULs and require 2 clicks again when reopened
            $('.menu-item-has-children').each(function(){
                if( $(this).hasClass('open') ) {
                    $(this).removeClass('open');
                }
            });
            $('#main').css('min-height', '');

        } else {
            sidebar.addClass('open');

            var windowWidth = $(window).width();

            // if at width when menu is absolutely positioned
            if( windowWidth > 549 && windowWidth < 950 ) {

                var socialIconsHeight = 0;

                if( $('#site-header').find('.social-media-icons').length ) {
                    socialIconsHeight = $('#site-header').find('.social-media-icons').find('ul').outerHeight();
                }

                // get the selector for the primary menu
                if( $('.menu-unset').length ) {
                    var menu = $('.menu-unset');
                } else {
                    var menu = $('#menu-primary-items');
                }
                var menuHeight = menu.outerHeight();

                var headerHeight = $('#main-sidebar').outerHeight();

                var sidebarPrimaryHeight = $('#sidebar-primary').height();

                $('#main').css('min-height', sidebarPrimaryHeight + headerHeight + socialIconsHeight + menuHeight + 'px' );
            }
        }
    }

    function openDropdownMenu() {

        var menuItem = $(this).parent();

        if( menuItem.hasClass('open') ) {
            menuItem.removeClass('open');
        } else {
            menuItem.addClass('open');
        }
    }

    // absolutely position the sidebar
    function positionSidebar() {

        var windowWidth = $(window).width();

        // if at width when menu is absolutely positioned
        if( windowWidth > 549 && windowWidth < 950 ) {

            var socialIconsHeight = 0;

            if( $('#site-header').find('.social-media-icons').length ) {
                socialIconsHeight = $('#site-header').find('.social-media-icons').find('ul').outerHeight();
            }

            // get the selector for the primary menu
            if( $('.menu-unset').length ) {
                var menu = $('.menu-unset');
            } else {
                var menu = $('#menu-primary-items');
            }
            var menuHeight = menu.outerHeight();
            var headerHeight = $('#main-sidebar').outerHeight();

            $('#menu-primary').css('top', headerHeight + socialIconsHeight + 24 + 'px');

            var sidebarPrimary = $('#sidebar-primary');

            // below the header and menu + 24 for margin
            sidebarPrimary.css('top', headerHeight + socialIconsHeight + menuHeight + 48 + 'px');
        }
        else {
            $('#sidebar-primary, #menu-primary').css('top', '');
        }
    }

    // move sidebar when dropdown menu items opened
    function adjustSidebarPosition() {

        // get the current window width
        var windowWidth = $(window).width();

        // if at width when menu is absolutely positioned
        if( windowWidth > 549 && windowWidth < 950 ) {

            // get the submenu
            var list = $(this).next();

            // set the height variable
            var listHeight = 0;

            // get the height of all the child li elements combined (because ul has max-height: 0)
            list.children().each(function(){
                listHeight = listHeight + $(this).height();
            });

            // get the current top value for the sidebar
            var sidebarTop = $('#sidebar-primary').css('top');

            var mainHeight = $('#main').css('min-height');

            // remove 'px' so addition is possible
            sidebarTop = parseInt(sidebarTop);

            // remove 'px' so addition is possible
            mainHeight = parseInt(mainHeight);

            // get the li containing the toggle button
            var menuItem = $(this).parent();

            // dropdown is being opened (increase sidebar top value)
            if( menuItem.hasClass('open') ) {
                $('#sidebar-primary').css('top', sidebarTop + listHeight + 'px');
                $('#main').css('min-height', mainHeight + listHeight + 'px');
            }
            // dropdown is being closed (decrease sidebar top value)
            else {
                $('#sidebar-primary').css('top', sidebarTop - listHeight + 'px');
                $('#main').css('min-height', mainHeight - listHeight + 'px');
            }
        }
    }

    // if sidebar open and resized over 950px, automatically close it
    function closeMainSidebar() {

        // if no longer at width when menu is absolutely positioned
        if( $(window).width() > 949 && $('#main-sidebar').hasClass('open') ) {
            // run function to close sidebar and all menus
            openPrimaryMenu();
        }
    }

    // keep light gray background all the way to footer
    function mainMinHeight() {

        if( $(window).width() > 949 ) {
            $('#main').css('min-height', $('#overflow-container').height() + 'px');
        } else {
            $('#main').css('min-height', '');
        }
    }

    // Sidebar scrolling.
    function resize() {
        var sidebar       = $('#main-sidebar');

        if ( 950 > $(window).width() ) {
            var top, bottom = false;
            sidebar.removeAttr( 'style' );
        }
    }

    var lastWindowPos = 0;
    var top, bottom = false;
    var topOffset = 0;
    function scroll() {
        var body = $('#overflow-container');
        var windowWidth   = $(window).width();
        var windowHeight  = $(window).height();
        var bodyHeight    = body.height();
        var sidebar       = $('#main-sidebar');
        var sidebarHeight = sidebar.outerHeight();
        var windowPos = $(window).scrollTop();
        var adminbarOffset = $('body').hasClass( 'admin-bar' ) ? $( '#wpadminbar' ).height() : 0;

        if ( 950 > windowWidth ) {
            return;
        }

        // if the sidebar height + admin bar is greater than the window height
        if ( sidebarHeight + adminbarOffset > windowHeight ) {
            // if the window has been scrolled down
            if ( windowPos > lastWindowPos ) {
                if ( top ) {
                    top = false;
                    topOffset = ( sidebar.offset().top > 0 ) ? sidebar.offset().top - adminbarOffset : 0;
                    sidebar.attr( 'style', 'top: ' + topOffset + 'px;' );
                } else if ( ! bottom && windowPos + windowHeight > sidebarHeight + sidebar.offset().top && sidebarHeight + adminbarOffset < bodyHeight ) {
                    bottom = true;
                    sidebar.attr( 'style', 'position: fixed; bottom: 0;' );
                }
                // if sidebar was shorter then menu dropdown made it taller
                else if ( sidebarHeight > windowHeight ) {
                    bottom = false;
                    topOffset = ( sidebar.offset().top > 0 ) ? sidebar.offset().top - adminbarOffset : 0;
                    sidebar.attr( 'style', 'top: ' + topOffset + 'px;' );
                }
            }
            // if the window has been scrolled up
            else if ( windowPos < lastWindowPos ) {
                if ( bottom ) {
                    bottom = false;
                    topOffset = ( sidebar.offset().top > 0 ) ? sidebar.offset().top - adminbarOffset : 0;
                    sidebar.attr( 'style', 'top: ' + topOffset + 'px;' );
                } else if ( ! top && windowPos > 0 && windowPos + adminbarOffset < sidebar.offset().top ) {
                    top = true;
                    sidebar.attr( 'style', 'position: fixed;' );
                }
            }
            // if the window has not been previously scrolled
            else {
                top = bottom = false;
                //topOffset = ( sidebar.offset().top > 0 ) ? sidebar.offset().top - adminbarOffset : 0;
                //sidebar.attr( 'style', 'top: ' + topOffset + 'px;' );
            }
        } else if ( ! top ) {
            top = true;
            sidebar.attr( 'style', 'position: fixed;' );
        }

        lastWindowPos = windowPos;
    }

    function resizeAndScroll() {
        resize();
        scroll();
    }

    var resizeTimer;

    $(window)
        .on( 'scroll', scroll )
        .on( 'resize', function() {
            clearTimeout( resizeTimer );
            resizeTimer = setTimeout( resizeAndScroll, 500 );
        } );
    $('#main-sidebar').on( 'click keydown', 'button', resizeAndScroll );

    resizeAndScroll();

    for ( var i = 1; i < 6; i++ ) {
        setTimeout( resizeAndScroll, 100 * i );
    }
});