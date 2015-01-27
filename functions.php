<?php

// Load the core theme framework.
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

// theme setup
function ct_unlimited_theme_setup() {
	
    /* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();
    
	// add Hybrid core functionality
    add_theme_support( 'hybrid-core-template-hierarchy' );
    add_theme_support( 'loop-pagination' );
	add_theme_support( 'cleaner-gallery' );

    // add functionality from WordPress core
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );

	// load theme options page
	require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );

	// add inc folder files
	foreach (glob(trailingslashit( get_template_directory() ) . 'inc/*') as $filename)
	{
		include $filename;
	}

	// load text domain
	load_theme_textdomain('unlimited', get_template_directory() . '/languages');

	// register Primary menu
    register_nav_menus(array(
        'primary' => __('Primary', 'unlimited')
    ));
}
add_action( 'after_setup_theme', 'ct_unlimited_theme_setup', 10 );

// turn off cleaner gallery if Jetpack gallery functions being used
function ct_unlimited_remove_cleaner_gallery() {

	if( class_exists( 'Jetpack' ) && ( Jetpack::is_module_active( 'carousel' ) || Jetpack::is_module_active( 'tiled-gallery' ) ) ) {
		remove_theme_support( 'cleaner-gallery' );
	}
}
add_action( 'after_setup_theme', 'ct_unlimited_remove_cleaner_gallery', 11 );

// register widget areas
function ct_unlimited_register_widget_areas(){

    /* register after post content widget area */
    hybrid_register_sidebar( array(
        'name'         => __( 'Primary Sidebar', 'unlimited' ),
        'id'           => 'sidebar',
        'description'  => __( 'Widgets in this area will be shown in the sidebar next to the main post content', 'unlimited' )
    ) );
}
add_action('widgets_init','ct_unlimited_register_widget_areas');

/* added to customize the comments. Same as default except -> added use of gravatar images for comment authors */
function ct_unlimited_customize_comments( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    global $post;
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment">
            <div class="comment-author">
                <?php
                // if is post author
                if( $comment->user_id === $post->post_author ) {
                    ct_unlimited_profile_image_output();
                } else {
                    echo get_avatar( get_comment_author_email(), 48 );
                }
                ?>
                <div>
                    <div class="author-name"><?php comment_author_link(); ?></div>
                    <div class="comment-date"><?php comment_date('n/j/Y'); ?></div>
                    <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'unlimited' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                    <?php edit_comment_link( 'edit' ); ?>
                </div>    
            </div>
            <div class="comment-content">
                <?php if ($comment->comment_approved == '0') : ?>
                    <em><?php _e('Your comment is awaiting moderation.', 'unlimited') ?></em>
                    <br />
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>
        </article>
    </li>
    <?php
}

/* added HTML5 placeholders for each default field and aria-required to required */
function ct_unlimited_update_fields($fields) {

	// get commenter object
    $commenter = wp_get_current_commenter();

	// are name and email required?
    $req = get_option( 'require_name_email' );

	// required or optional label to be added
	if( $req == 1 ) {
		$label = '*';
	} else {
		$label = ' (optional)';
	}

	// adds aria required tag if required
	$aria_req = ( $req ? " aria-required='true'" : '' );

    $fields['author'] =
        '<p class="comment-form-author">
            <label class="screen-reader-text">' . __("Your Name", "unlimited") . '</label>
            <input placeholder="' . __("Your Name", "unlimited") . $label . '" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
        '" size="30" ' . $aria_req . ' />
    	</p>';

    $fields['email'] =
        '<p class="comment-form-email">
            <label class="screen-reader-text">' . __("Your Email", "unlimited") . '</label>
            <input placeholder="' . __("Your Email", "unlimited") . $label . '" id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) .
        '" size="30" ' . $aria_req . ' />
    	</p>';

    $fields['url'] =
        '<p class="comment-form-url">
            <label class="screen-reader-text">' . __("Your Website URL", "unlimited") . '</label>
            <input placeholder="' . __("Your URL", "unlimited") . ' (optional)" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
        '" size="30" />
            </p>';

    return $fields;
}
add_filter('comment_form_default_fields','ct_unlimited_update_fields');

function ct_unlimited_update_comment_field($comment_field) {
	
	$comment_field =
        '<p class="comment-form-comment">
            <label class="screen-reader-text">' . __("Your Comment", "unlimited") . '</label>
            <textarea required placeholder="' . __("Enter Your Comment", "unlimited") . '&#8230;" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
        </p>';
	
	return $comment_field;
}
add_filter('comment_form_field_comment','ct_unlimited_update_comment_field');

// remove allowed tags text after comment form
function ct_unlimited_remove_comments_notes_after($defaults){

    $defaults['comment_notes_after']='';
    return $defaults;
}

add_action('comment_form_defaults', 'ct_unlimited_remove_comments_notes_after');

// excerpt handling
function ct_unlimited_excerpt() {

    // make post variable available
    global $post;

    // make 'read more' setting available
    global $more;

    // check for the more tag
    $ismore = strpos( $post->post_content, '<!--more-->');

    // use the read more link if present
    if($ismore) {
        the_content( __('Read More', 'unlimited') . "<span class='screen-reader-text'>" . get_the_title() . "</span>");
    }
    // otherwise the excerpt is automatic, so output it
    else {
        the_excerpt();
    }
}

// filter the link on excerpts
function ct_unlimited_excerpt_read_more_link($output) {
	global $post;
	return $output . "<p><a class='more-link' href='". get_permalink() ."'>" . __('Read More', 'unlimited') . "<span class='screen-reader-text'>" . get_the_title() . "</span></a></p>";
}

add_filter('the_excerpt', 'ct_unlimited_excerpt_read_more_link');

// switch [...] to ellipsis on automatic excerpt
function ct_unlimited_new_excerpt_more( $more ) {
	return '&#8230;';
}
add_filter('excerpt_more', 'ct_unlimited_new_excerpt_more');

// turns of the automatic scrolling to the read more link 
function ct_unlimited_remove_more_link_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}
add_filter( 'the_content_more_link', 'ct_unlimited_remove_more_link_scroll' );

// Adds navigation through pages in the loop
function ct_unlimited_post_navigation() {
    if ( current_theme_supports( 'loop-pagination' ) ) loop_pagination();
}

// for displaying featured images
function ct_unlimited_featured_image() {

	// get post object
	global $post;
	// default to no featured image
	$has_image = false;

	// if post has an image
	if (has_post_thumbnail( $post->ID ) ) {
		// get the full-size version of the image
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
		// set $image = the url
		$image = $image[0];
		$has_image = true;
	}
	if ($has_image == true) {

		// on posts/pages display the featued image
		if(is_singular()){
			echo "<div class='featured-image' style=\"background-image: url('".$image."')\"></div>";
		}
		// on blog/archives display with a link
		else {
			echo "
                <div class='featured-image' style=\"background-image: url('".$image."')\">
                    <a href='" . get_permalink() ."'>" . get_the_title() . "</a>
                </div>
                ";
		}
	}
}

// puts site title & description in the title tag on front page
function ct_unlimited_add_homepage_title( $title )
{
    if( empty( $title ) && ( is_home() || is_front_page() ) ) {
        return get_bloginfo( 'title' ) . ' | ' . get_bloginfo( 'description' );
    }
    return $title;
}
add_filter( 'wp_title', 'ct_unlimited_add_homepage_title' );

function ct_unlimited_add_editor_styles() {
    add_editor_style( 'styles/custom-editor-style.css' );
}
add_action( 'init', 'ct_unlimited_add_editor_styles' );

// fix for bug with Disqus saying comments are closed
if ( function_exists( 'dsq_options' ) ) {
    remove_filter( 'comments_template', 'dsq_comments_template' );
    add_filter( 'comments_template', 'dsq_comments_template', 99 ); // You can use any priority higher than '10'
}

// list social media sites
function ct_unlimited_social_site_list(){

    $social_sites = array('twitter', 'facebook', 'google-plus', 'flickr', 'pinterest', 'youtube', 'vimeo', 'tumblr', 'dribbble', 'rss', 'linkedin', 'instagram', 'reddit', 'soundcloud', 'spotify', 'vine','yahoo', 'behance', 'codepen', 'delicious', 'stumbleupon', 'deviantart', 'digg', 'git', 'hacker-news', 'steam', 'vk', 'weibo', 'tencent-weibo', 'email' );
    return $social_sites;
}

// associative array of social media sites
function ct_unlimited_social_array(){

	$social_sites = array(
		'twitter' => 'unlimited_twitter_profile',
		'facebook' => 'unlimited_facebook_profile',
		'googleplus' => 'unlimited_googleplus_profile',
		'pinterest' => 'unlimited_pinterest_profile',
		'linkedin' => 'unlimited_linkedin_profile',
		'youtube' => 'unlimited_youtube_profile',
		'vimeo' => 'unlimited_vimeo_profile',
		'tumblr' => 'unlimited_tumblr_profile',
		'instagram' => 'unlimited_instagram_profile',
		'flickr' => 'unlimited_flickr_profile',
		'dribbble' => 'unlimited_dribbble_profile',
		'rss' => 'unlimited_rss_profile',
		'reddit' => 'unlimited_reddit_profile',
		'soundcloud' => 'unlimited_soundcloud_profile',
		'spotify' => 'unlimited_spotify_profile',
		'vine' => 'unlimited_vine_profile',
		'yahoo' => 'unlimited_yahoo_profile',
		'behance' => 'unlimited_behance_profile',
		'codepen' => 'unlimited_codepen_profile',
		'delicious' => 'unlimited_delicious_profile',
		'stumbleupon' => 'unlimited_stumbleupon_profile',
		'deviantart' => 'unlimited_deviantart_profile',
		'digg' => 'unlimited_digg_profile',
		'git' => 'unlimited_git_profile',
		'hacker-news' => 'unlimited_hacker-news_profile',
		'steam' => 'unlimited_steam_profile',
		'vk' => 'unlimited_vk_profile',
		'weibo' => 'unlimited_weibo_profile',
		'tencent-weibo' => 'unlimited_tencent_weibo_profile',
		'email' => 'unlimited_email_profile'
	);
	return $social_sites;
}

// retrieves the attachment ID from the file URL
function ct_unlimited_get_image_id($url) {

    // Split the $url into two parts with the wp-content directory as the separator
    $parsed_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

    // Get the host of the current site and the host of the $url, ignoring www
    $this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
    $file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

    // Return nothing if there aren't any $url parts or if the current host and $url host do not match
    if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
        return;
    }

    // Now we're going to quickly search the DB for any attachment GUID with a partial path match
    // Example: /uploads/2013/05/test-image.jpg
    global $wpdb;

    $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $parsed_url[1] ) );

    // Returns null if no attachment is found
    return $attachment[0];
}

function ct_unlimited_profile_image_output(){

    // use User's profile image, else default to their Gravatar
    if(get_the_author_meta('unlimited_user_profile_image')){

        // get the id based on the image's URL
        $image_id = ct_unlimited_get_image_id(get_the_author_meta('unlimited_user_profile_image'));

        // retrieve the thumbnail size of profile image
        $image_thumb = wp_get_attachment_image($image_id, 'thumbnail');

        // display the image
        echo $image_thumb;

    } else {
        echo get_avatar( get_the_author_meta( 'ID' ), 60 );
    }
}

function ct_unlimited_wp_backwards_compatibility() {

	// not using this function, simply remove it so use of "has_image_size" doesn't break < 3.9
	if( get_bloginfo('version') < 3.9 ) {
		remove_filter( 'image_size_names_choose', 'hybrid_image_size_names_choose' );
	}
}
add_action('init', 'ct_unlimited_wp_backwards_compatibility');

/*
 * Set the date format for new users.
 * Needs to be done this way so that the date defaults to the right format, but can
 * still be changed from the Settings menu
 */
function ct_unlimited_set_date_format() {

	// if the date format has never been set by Unlimited, set it
	if( get_option('ct_unlimited_date_format_origin') != 'updated' ) {
		update_option('date_format', 'F j, Y');

		// add option so never updates date format again. Allows users to change format.
		add_option('ct_unlimited_date_format_origin', 'updated');
	}
}
add_action( 'init', 'ct_unlimited_set_date_format' );