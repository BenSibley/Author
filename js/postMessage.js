( function( $ ) {

    var panel = $('html', window.parent.document);
    var body = $('body');
    var inlineStyles = $('#ct-author-style-inline-css');
    var siteTitle = $('#site-title');

    // Site title
    wp.customize( 'blogname', function( value ) {
        value.bind( function( to ) {
            // if there is a logo, don't replace it
            if( siteTitle.find('img').length == 0 ) {
                siteTitle.children('a').text( to );
            }
        } );
    } );
    // Tagline
    wp.customize( 'blogdescription', function( value ) {
        value.bind( function( to ) {
            var tagline = $('.tagline');
            if ( tagline.length == 0 ) {
                $('#title-container').find('.container').append('<p class="tagline"></p>');
            }
            tagline.text( to );
        } );
    } );

    /***** Custom CSS *****/

    // get current Custom CSS
    var customCSS = panel.find('#customize-control-custom_css').find('textarea').val();

    // get the CSS in the inline element
    var allCSS = inlineStyles.text();

    // remove the Custom CSS from the other CSS
    allCSS = allCSS.replace(customCSS, '');

    // update the CSS in the inline element w/o the custom css
    inlineStyles.text(allCSS);

    // add custom CSS to its own style element
    body.append('<style id="style-inline-custom-css" type="text/css">' + customCSS + '</style>');

    var setting = 'custom_css';
    if ( panel.find('#sub-accordion-section-custom_css').length ) {
        setting = 'custom_css[author]';
    }
    // Custom CSS
    wp.customize( setting, function( value ) {
        value.bind( function( to ) {
            $('#style-inline-custom-css').remove();
            if ( to != '' ) {
                to = '<style id="style-inline-custom-css" type="text/css">' + to + '</style>';
                body.append( to );
            }
        } );
    } );


} )( jQuery );