/*global jQuery */
/*jshint browser:true */
/*!
 * FitVids 1.1
 *
 * Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
 * Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
 * Released under the WTFPL license - http://sam.zoy.org/wtfpl/
 *
 */

;(function( $ ){

    'use strict';

    $.fn.fitVids = function( options ) {
        var settings = {
            customSelector: null,
            ignore: null
        };

        if(!document.getElementById('fit-vids-style')) {
            // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
            var head = document.head || document.getElementsByTagName('head')[0];
            var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
            var div = document.createElement("div");
            div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
            head.appendChild(div.childNodes[1]);
        }

        if ( options ) {
            $.extend( settings, options );
        }

        return this.each(function(){
            var selectors = [
                'iframe[src*="player.vimeo.com"]',
                'iframe[src*="youtube.com"]',
                'iframe[src*="youtube-nocookie.com"]',
                'iframe[src*="kickstarter.com"][src*="video.html"]',
                'object',
                'embed'
            ];

            if (settings.customSelector) {
                selectors.push(settings.customSelector);
            }

            var ignoreList = '.fitvidsignore';

            if(settings.ignore) {
                ignoreList = ignoreList + ', ' + settings.ignore;
            }

            var $allVideos = $(this).find(selectors.join(','));
            $allVideos = $allVideos.not('object object'); // SwfObj conflict patch
            $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

            $allVideos.each(function(){
                var $this = $(this);
                if($this.parents(ignoreList).length > 0) {
                    return; // Disable FitVids on this video.
                }
                if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
                if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
                {
                    $this.attr('height', 9);
                    $this.attr('width', 16);
                }
                var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
                    width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
                    aspectRatio = height / width;
                if(!$this.attr('id')){
                    var videoID = 'fitvid' + Math.floor(Math.random()*999999);
                    $this.attr('id', videoID);
                }
                $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+'%');
                $this.removeAttr('height').removeAttr('width');
            });
        });
    };
// Works with either jQuery or Zepto
})( window.jQuery || window.Zepto );
jQuery(document).ready(function($){

    /* Set variables */

    var toggleDropdown = $('.toggle-dropdown');
    var sidebar = $('#main-sidebar');
    var siteHeader = $('#site-header');
    var main = $('#main');
    var sidebarPrimary = $('#sidebar-primary');

    // get the selector for the primary menu
    var menu = $('.menu-unset').length ? $('.menu-unset') : $('#menu-primary-items');

    // for scrolling function
    var lastWindowPos = 0;
    var top, bottom, short = false;
    var topOffset = 0;
    var resizeTimer;

    /* Call functions */

    positionSidebar();
    setMainMinHeight();

    $(window).resize(function(){
        positionSidebar();
        closeMainSidebar();
        setMainMinHeight();
    });

    // add fitVids to videos in posts
    $('.post-content').fitVids();

    // display the primary menu at mobile widths
    $('#toggle-navigation').on('click', openPrimaryMenu);

    // display the dropdown menus
    toggleDropdown.on('click', openDropdownMenu);

    // extend sidebar height when dropdown clicked
    toggleDropdown.on('click', adjustSidebarHeight);

    function openPrimaryMenu() {

        // if menu open
        if( sidebar.hasClass('open') ) {

            // trigger event
            sidebar.trigger('close');

            // remove styling class
            sidebar.removeClass('open');

            // update screen reader text and aria-expanded
            $(this).children('span').text(objectL10n.openPrimaryMenu);
            $(this).attr('aria-expanded', 'false');

            // close all ULs by removing increased max-height
            $('#menu-primary-items ul, .menu-unset ul').removeAttr('style');

            // close all ULs and require 2 clicks again when reopened
            $('.menu-item-has-children').each(function(){
                if( $(this).hasClass('open') ) {
                    $(this).removeClass('open');
                }
            });

            // set minimum height for main
            setMainMinHeight();

            // return sidebar to initial top position
            positionSidebar();

            // if menu is closed, unbind auto close function
            $(window).unbind('scroll', autoCloseMenu);

        } else {
            sidebar.addClass('open');

            // trigger event
            sidebar.trigger('open');

            // update screen reader text and aria-expanded
            $(this).children('span').text(objectL10n.closePrimaryMenu);
            $(this).attr('aria-expanded', 'true');

            var windowWidth = $(window).width();

            // if at width when menu is absolutely positioned
            if( windowWidth > 549 && windowWidth < 950 ) {

                var socialIconsHeight = 0;

                if( siteHeader.find('.social-media-icons').length ) {
                    socialIconsHeight = siteHeader.find('.social-media-icons').find('ul').outerHeight();
                }

                var menuHeight = menu.outerHeight();

                var headerHeight = sidebar.outerHeight();

                var sidebarPrimaryHeight = sidebarPrimary.height();

                main.css('min-height', sidebarPrimaryHeight + headerHeight + socialIconsHeight + menuHeight + 'px' );

                // close menu automatically if scrolled past
                $(window).scroll(autoCloseMenu);
            }
        }
    }

    function openDropdownMenu() {

        var menuItem = $(this).parent();

        if( menuItem.hasClass('open') ) {
            menuItem.removeClass('open');
            $(this).children('span').text(objectL10n.openChildMenu);
            $(this).attr('aria-expanded', 'false');
        } else {
            menuItem.addClass('open');
            $(this).children('span').text(objectL10n.closeChildMenu);
            $(this).attr('aria-expanded', 'true');
            short = false; // return to false to be measured again (may not be shorter than window now)
        }
    }

    // absolutely position the sidebar
    function positionSidebar() {

        var windowWidth = $(window).width();

        // if at width when menu is absolutely positioned
        if( windowWidth > 549 && windowWidth < 950 ) {

            var socialIconsHeight = 0;

            if( siteHeader.find('.social-media-icons').length ) {
                socialIconsHeight = siteHeader.find('.social-media-icons').find('ul').outerHeight();
            }

            var menuHeight = menu.outerHeight();
            var headerHeight = sidebar.outerHeight();

            $('#menu-primary').css('top', headerHeight + socialIconsHeight + 24 + 'px');

            // below the header and menu + 24 for margin
            sidebarPrimary.css('top', headerHeight + socialIconsHeight + menuHeight + 48 + 'px');
        }
        else {
            $('#sidebar-primary, #menu-primary').css('top', '');
        }
    }

    // move sidebar when dropdown menu items opened
    function adjustSidebarHeight() {

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
            var sidebarTop = sidebarPrimary.css('top');

            var mainHeight = main.css('min-height');

            // remove 'px' so addition is possible
            sidebarTop = parseInt(sidebarTop);

            // remove 'px' so addition is possible
            mainHeight = parseInt(mainHeight);

            // get the li containing the toggle button
            var menuItem = $(this).parent();

            // dropdown is being opened (increase sidebar top value)
            if( menuItem.hasClass('open') ) {
                sidebarPrimary.css('top', sidebarTop + listHeight + 'px');
                main.css('min-height', mainHeight + listHeight + 'px');
            }
            // dropdown is being closed (decrease sidebar top value)
            else {
                sidebarPrimary.css('top', sidebarTop - listHeight + 'px');
                main.css('min-height', mainHeight - listHeight + 'px');
            }
        }
    }

    // if sidebar open and resized over 950px, automatically close it
    function closeMainSidebar() {

        // if no longer at width when menu is absolutely positioned
        if( $(window).width() > 949 && sidebar.hasClass('open') ) {
            // run function to close sidebar and all menus
            openPrimaryMenu();
        }
    }

    // keep light gray background all the way to footer
    function setMainMinHeight() {
        // refresh
        main.css('min-height', '');
        main.css('min-height', $('#overflow-container').height() + 'px');
    }

    // Sidebar scrolling.
    function resize() {

        if ( 950 > $(window).width() ) {
            var top, bottom = false;
            sidebar.removeAttr( 'style' );
        }
    }

    function scroll() {
        var body = $('#overflow-container');
        var windowWidth   = $(window).width();
        var windowHeight  = $(window).height();
        var bodyHeight    = body.height();
        var sidebarHeight = sidebar.outerHeight();
        var windowPos = $(window).scrollTop();
        var adminbarOffset = $('body').hasClass( 'admin-bar' ) ? $( '#wpadminbar' ).height() : 0;

        if ( 950 > windowWidth ) {
            return;
        }

        // if the sidebar height + admin bar is greater than the window height
        if ( ( sidebarHeight + adminbarOffset > windowHeight ) && short != true ) {
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
                else if ( ( sidebarHeight + adminbarOffset > windowHeight ) && ! bottom  ) {
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
            }
        } else if ( ! top ) {
            top = true;
            short = true;
            sidebar.attr( 'style', 'position: fixed;' );
        }

        lastWindowPos = windowPos;
    }

    $(window)
        .on( 'scroll', scroll )
        .on( 'resize', function() {
            clearTimeout( resizeTimer );
            resizeTimer = setTimeout( resizeAndScroll, 500 );
        } );
    sidebar.on( 'click keydown', 'button', resizeAndScroll );

    function resizeAndScroll() {
        resize();
        scroll();
    }
    resizeAndScroll();

    for ( var i = 1; i < 6; i++ ) {
        setTimeout( resizeAndScroll, 100 * i );
    }

    function autoCloseMenu() {

        // get position of the bottom of the sidebar
        var sidebarPrimaryBottom = sidebarPrimary.offset().top + sidebarPrimary.height();

        // window distance from top
        var topDistance = $(window).scrollTop();

        // if visitor scrolled 50px past bottom of sidebar, close menu
        if (topDistance > sidebarPrimaryBottom + 50) {
            openPrimaryMenu();
        }
    }

    // if sidebar height is less than window height, needs help to keep from flickering
    function sidebarMinHeight() {

    }
});

/* fix for skip-to-content link bug in Chrome & IE9 */
window.addEventListener("hashchange", function(event) {

    var element = document.getElementById(location.hash.substring(1));

    if (element) {

        if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
            element.tabIndex = -1;
        }

        element.focus();
    }

}, false);