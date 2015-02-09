( function( $ ) {

    /*
     * Functions for utilizing the postMessage transport setting
     */

    // Site title
    wp.customize( 'blogname', function( value ) {
        value.bind( function( to ) {
            // if there is a logo, don't replace it
            if( $('.site-title').find('img').length == 0 ) {
                $( '.site-title a' ).text( to );
            }
        } );
    } );
    // Tagline
    wp.customize( 'blogdescription', function( value ) {
        value.bind( function( to ) {
            $( '.tagline' ).text( to );
        } );
    } );


} )( jQuery );