( function( $ ) {

    /*
     * Following functions are for utilizing the postMessage transport setting
     */

    wp.customize( 'logo_upload', function( value ) {
        value.bind( function( newval ) {
            // get the <a> holding the logo/site title
            var logoContainer = $('#site-title').find('a');
            // get the name of the site from the <a>
            var siteTitle = logoContainer.attr('title');
            // if there is an image, add the image markup
            if( newval ) {
                var logo = "<span class='screen-reader-text'>" + siteTitle + "</span><img id='logo' class='logo' src='" + newval + "' alt='" + siteTitle + "' />";
            }
            // otherwise just use the site title
            else {
                var logo = siteTitle;
            }
            // empty the content first
            logoContainer.empty();
            // replace with the new logo markup
            logoContainer.append(logo);
        } );
        // Site title and description.
        wp.customize( 'blogname', function( value ) {
            value.bind( function( to ) {
                $( '.site-title a' ).text( to );
            } );
        } );
        wp.customize( 'blogdescription', function( value ) {
            value.bind( function( to ) {
                $( '.site-description' ).text( to );
            } );
        } );
        // $social_sites = ct_unlimited_social_site_list();
        //$('html', window.parent.document).find('#accordion-section-ct_unlimited_social_media_icons').find('input').each(function(){
        //
        //    var setting = $(this).attr('data-customize-setting-link');
        //
        //    wp.customize( setting, function( value ) {
        //        value.bind( function( to ) {
        //            // if the icon is there, update the href
        //            if( $('.social-media-icons').find('.' + setting) ) {
        //                $('.' + setting).attr('hreft', to);
        //            }
        //            // otherwise add the icon
        //            else {
        //                var icon =
        //                $('.social-media-icons').append();
        //            }
        //        } );
        //    } );
        //});

    } );
} )( jQuery );