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
                if(!$this.attr('name')){
                    var videoName = 'fitvid' + $.fn.fitVids._count;
                    $this.attr('name', videoName);
                    $.fn.fitVids._count++;
                }
                $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+'%');
                $this.removeAttr('height').removeAttr('width');
            });
        });
    };

    // Internal counter for unique video names.
    $.fn.fitVids._count = 0;

// Works with either jQuery or Zepto
})( window.jQuery || window.Zepto );
jQuery(document).ready(function($){

    /* Set variables */

    var toggleDropdown = $('.toggle-dropdown');
    var sidebar = $('#main-sidebar');
    var siteHeader = $('#site-header');
    var main = $('#main');
    var sidebarPrimary = $('#sidebar-primary');
    var overflowContainer = $('#overflow-container');
    var loopContainer = $('#loop-container');
    var headerImage = $('#header-image');
    var menu = $('.menu-unset').length ? $('.menu-unset') : $('#menu-primary-items');

    // for scrolling function
    var lastWindowPos = 0;
    var top, bottom, short = false;
    var topOffset = 0;
    var resizeTimer;
    var windowWidth   = window.innerWidth;
    var windowHeight  = $(window).height();
    var adminbarOffset = $('body').hasClass( 'admin-bar' ) ? $( '#wpadminbar' ).height() : 0;
    var bodyHeight    = overflowContainer.height();
    var sidebarHeight = sidebar.outerHeight();

    /* Call functions */

    positionSidebar();
    objectFitAdjustment();

    $('#toggle-navigation').on('click', openPrimaryMenu);
    toggleDropdown.on('click', openDropdownMenu);
    toggleDropdown.on('click', adjustSidebarHeight);

    $(window).bind("load", function() {
        setMainMinHeight();
    });

    $(window).on( 'resize', function(){
        positionSidebar();
        closeMainSidebar();
        setMainMinHeight();
        objectFitAdjustment();
    });

    // Jetpack infinite scroll event that reloads posts.
    $( document.body ).on( 'post-load', function () {

        $.when(moveInfinitePosts()).then(function(){
            objectFitAdjustment();
        });
    } );

    $('.post-content').fitVids({
        customSelector: 'iframe[src*="dailymotion.com"], iframe[src*="slideshare.net"], iframe[src*="animoto.com"], iframe[src*="blip.tv"], iframe[src*="funnyordie.com"], iframe[src*="hulu.com"], iframe[src*="ted.com"], iframe[src*="wordpress.tv"]'
    });

    function openPrimaryMenu() {

        if( sidebar.hasClass('open') ) {

            sidebar.trigger('close');
            sidebar.removeClass('open');

            // update screen reader text and aria-expanded
            $(this).children('span').text(ct_author_objectL10n.openPrimaryMenu);
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
            sidebar.trigger('open');

            // update screen reader text and aria-expanded
            $(this).children('span').text(ct_author_objectL10n.closePrimaryMenu);
            $(this).attr('aria-expanded', 'true');

            var windowWidth = window.innerWidth;

            // if at width when menu is absolutely positioned
            if( windowWidth > 549 && windowWidth < 950 ) {

                var socialIconsHeight = 0;

                if( siteHeader.find('.social-media-icons').length ) {
                    socialIconsHeight = siteHeader.find('.social-media-icons').find('ul').outerHeight();
                }

                var menuHeight           = menu.outerHeight();
                var headerHeight         = sidebar.outerHeight();
                var sidebarPrimaryHeight = sidebarPrimary.height();

                var minHeight = sidebarPrimaryHeight + headerHeight + socialIconsHeight + menuHeight;

                if ( minHeight > window.innerHeight ) {
                    main.css('min-height', minHeight + 'px' );
                }

                // close menu automatically if scrolled past
                $(window).scroll(autoCloseMenu);
            }
        }
    }

    function openDropdownMenu() {

        var menuItem = $(this).parent();

        if( menuItem.hasClass('open') ) {
            menuItem.removeClass('open');
            $(this).children('span').text(ct_author_objectL10n.openChildMenu);
            $(this).attr('aria-expanded', 'false');
        } else {
            menuItem.addClass('open');
            $(this).children('span').text(ct_author_objectL10n.closeChildMenu);
            $(this).attr('aria-expanded', 'true');
            short = false; // return to false to be measured again (may not be shorter than window now)
        }
        setMainMinHeight();
    }

    // open the menu to display the current page if inside a dropdown menu
    $( '.current-menu-ancestor').addClass('open');

    // absolutely position the sidebar
    function positionSidebar() {

        var windowWidth = window.innerWidth;

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

        var windowWidth = window.innerWidth;

        // if at width when menu is absolutely positioned
        if( windowWidth > 549 && windowWidth < 950 ) {

            // get the submenu
            var list       = $(this).next();
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
        if( window.innerWidth > 949 && sidebar.hasClass('open') ) {
            // run function to close sidebar and all menus
            openPrimaryMenu();
        }
    }

    // increase main height when needed so fixed sidebar can be scrollable
    function setMainMinHeight() {
        // refresh
        main.css('min-height', '');
        // height is equal to overflow container's height
        var height = overflowContainer.height();
        // if header image, subtract its height b/c its in
        // .overflow-container, but not in .main
        if ( headerImage.length > 0 ) {
            // header image technically uses padding-bottom not height, so use 'a' element
            height = height - headerImage.children('a').height();
        }
        // add the new minimum height
        if ( height > window.innerHeight ) {
            main.css('min-height', height + 'px');
        }
    }

    // Sidebar scrolling.
    function resize() {

        windowWidth   = window.innerWidth;
        windowHeight  = $(window).height();
        adminbarOffset = $('body').hasClass( 'admin-bar' ) ? $( '#wpadminbar' ).height() : 0;
        bodyHeight    = overflowContainer.height();
        sidebarHeight = sidebar.outerHeight();

        if ( window.innerWidth < 950 ) {
            var top, bottom = false;
            sidebar.removeAttr( 'style' );
        }
    }

    function scroll() {

        if ( 950 > windowWidth ) {
            return;
        }

        var windowPos = $(window).scrollTop();

        // if the sidebar height + admin bar is greater than the window height
        if ( ( sidebarHeight + adminbarOffset > windowHeight ) && short != true ) {
            // if the window has been scrolled down
            if ( windowPos > lastWindowPos ) {
                if ( top ) {
                    top = false;
                    topOffset = ( sidebar.offset().top > 0 ) ? sidebar.offset().top - adminbarOffset : 0;
                    sidebar.attr( 'style', 'top: ' + topOffset + 'px;' );
                } else if ( ! bottom && windowPos + windowHeight >= sidebarHeight + sidebar.offset().top && sidebarHeight + adminbarOffset <= bodyHeight ) {
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
                } else if ( ! top && windowPos >= 0 && windowPos + adminbarOffset <= sidebar.offset().top ) {
                    top = true;
                    sidebar.attr( 'style', 'position: fixed;' );
                }
            }
            // if the window has not been previously scrolled
            else {
                top = bottom = false;
            }
        }
        // sidebar is shorter than window
        else {
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

    // mimic cover positioning without using cover
    function objectFitAdjustment() {

        // if the object-fit property is not supported
        if( !('object-fit' in document.body.style) ) {

            $('.featured-image').each(function () {

                if ( !$(this).parent('.entry').hasClass('ratio-natural') ) {

                    var image = $(this).children('img').add($(this).children('a').children('img'));

                    // don't process images twice (relevant when using infinite scroll)
                    if (image.hasClass('no-object-fit')) return;

                    image.addClass('no-object-fit');

                    // if the image is not wide enough to fill the space
                    if (image.outerWidth() < $(this).outerWidth()) {

                        image.css({
                            'width': '100%',
                            'min-width': '100%',
                            'max-width': '100%',
                            'height': 'auto',
                            'min-height': '100%',
                            'max-height': 'none'
                        });
                    }
                    // if the image is not tall enough to fill the space
                    if (image.outerHeight() < $(this).outerHeight()) {

                        image.css({
                            'height': '100%',
                            'min-height': '100%',
                            'max-height': '100%',
                            'width': 'auto',
                            'min-width': '100%',
                            'max-width': 'none'
                        });
                    }
                }
            });
        }
    }

    function moveInfinitePosts(){
        // move any posts in infinite wrap to loop-container
        $('.infinite-wrap').children('.entry').detach().appendTo( loopContainer );
        $('.infinite-wrap, .infinite-loader').remove();
    }

    // ===== Scroll to Top ==== //

    if ( $('#scroll-to-top').length !== 0 ) {
        $(window).on( 'scroll', function() {
            if ($(this).scrollTop() >= 200) {        // If page is scrolled more than 50px
                $('#scroll-to-top').addClass('visible');    // Fade in the arrow
            } else {
                $('#scroll-to-top').removeClass('visible');   // Else fade out the arrow
            }
        });
        $('#scroll-to-top').click(function(e) {      // When arrow is clicked
            $('body,html').animate({
                scrollTop : 0                       // Scroll to top of body
            }, 800);
        });
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