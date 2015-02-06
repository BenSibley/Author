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

    positionSidebar();
    mainMinHeight();

    $(window).resize(function(){
        positionSidebar();
        closeMainSidebar();
        mainMinHeight();
    });

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
});