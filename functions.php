<?php

/*
 * Prefix: ct_author = Compete Themes Author
 */

// set the content width
if ( ! isset( $content_width ) ) {
	$content_width = 622;
}

// theme setup
if( !function_exists('ct_author_theme_setup' ) ) {
	function ct_author_theme_setup() {

		// add functionality from WordPress core
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
		) );

		// adds support for Jetpack infinite scroll feature
		add_theme_support( 'infinite-scroll', array(
			'container' => 'loop-container',
			'footer'    => 'overflow-container',
			'render'    => 'ct_author_infinite_scroll_render'
		) );

		// load theme options page
		require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );

		// add inc folder files
		foreach ( glob( trailingslashit( get_template_directory() ) . 'inc/*' ) as $filename ) {
			include $filename;
		}

		// load text domain
		load_theme_textdomain( 'author', get_template_directory() . '/languages' );

		// register Primary menu
		register_nav_menus( array(
			'primary' => __( 'Primary', 'author' )
		) );
	}
}
add_action( 'after_setup_theme', 'ct_author_theme_setup', 10 );

// register widget areas
function ct_author_register_widget_areas(){

    /* register after post content widget area */
    register_sidebar( array(
        'name'         => __( 'Primary Sidebar', 'author' ),
        'id'           => 'primary',
        'description'  => __( 'Widgets in this area will be shown in the sidebar', 'author' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>'
    ) );
}
add_action('widgets_init','ct_author_register_widget_areas');

/* added to customize the comments. Same as default except -> added use of gravatar images for comment authors */
if( !function_exists('ct_author_customize_comments' ) ) {
	function ct_author_customize_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		$comment_type       = $comment->comment_type;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-author">
				<?php
				// if not a pingback
				if ( $comment_type !== 'pingback' ) {
					// if site admin and avatar uploaded
					if ( $comment->comment_author_email === get_option( 'admin_email' ) && get_theme_mod( 'avatar_method' ) == 'upload' ) {
						echo '<img alt="' . get_comment_author() . '" class="avatar avatar-48 photo" src="' . esc_url( ct_author_output_avatar() ) . '" height="48" width="48" />';
					} else {
						echo get_avatar( get_comment_author_email(), 48, '', get_comment_author() );
					}
				}
				?>
				<span class="author-name"><?php comment_author_link(); ?></span>
			</div>
			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'author' ) ?></em>
					<br/>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
			<?php
			// if not a pingback
			if ( $comment_type !== 'pingback' ) { ?>
				<div class="comment-footer">
					<span class="comment-date"><?php comment_date(); ?></span>
					<?php comment_reply_link( array_merge( $args, array(
						'reply_text' => __( 'Reply', 'author' ),
						'depth'      => $depth,
						'max_depth'  => $args['max_depth']
					) ) ); ?>
					<?php edit_comment_link( __('Edit', 'author') ); ?>
				</div>
			<?php } ?>
		</article>
	<?php
	}
}

// adjustments to default comment form inputs
if( ! function_exists( 'ct_author_update_fields' ) ) {
    function ct_author_update_fields( $fields ) {

        // get commenter object
        $commenter = wp_get_current_commenter();

        // are name and email required?
        $req = get_option( 'require_name_email' );

        // required or optional label to be added
        if ( $req == 1 ) {
            $label = '*';
        } else {
            $label = ' ' . __("optional", "author");
        }

        // adds aria required tag if required
        $aria_req = ( $req ? " aria-required='true'" : '' );

        $fields['author'] =
            '<p class="comment-form-author">
	            <label for="author">' . __( "Name", "author" ) . $label . '</label>
	            <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
            '" size="30" ' . $aria_req . ' />
	        </p>';

        $fields['email'] =
            '<p class="comment-form-email">
	            <label for="email">' . __( "Email", "author" ) . $label . '</label>
	            <input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) .
            '" size="30" ' . $aria_req . ' />
	        </p>';

        $fields['url'] =
            '<p class="comment-form-url">
	            <label for="url">' . __( "Website", "author" ) . '</label>
	            <input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
            '" size="30" />
	            </p>';

        return $fields;
    }
}
add_filter('comment_form_default_fields','ct_author_update_fields');

if( ! function_exists( 'ct_author_update_comment_field' ) ) {
    function ct_author_update_comment_field( $comment_field ) {

        $comment_field =
            '<p class="comment-form-comment">
	            <label for="comment">' . __( "Comment", "author" ) . '</label>
	            <textarea required id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
	        </p>';

        return $comment_field;
    }
}
add_filter('comment_form_field_comment','ct_author_update_comment_field');

// remove allowed tags text after comment form
if( !function_exists('ct_author_remove_comments_notes_after' ) ) {
	function ct_author_remove_comments_notes_after( $defaults ) {

		$defaults['comment_notes_after'] = '';

		return $defaults;
	}
}
add_action('comment_form_defaults', 'ct_author_remove_comments_notes_after');

// excerpt handling
if( ! function_exists( 'ct_author_excerpt' ) ) {
    function ct_author_excerpt() {

        // make post variable available
        global $post;

        // check for the more tag
        $ismore = strpos( $post->post_content, '<!--more-->' );

        // get the show full post setting
        $show_full_post = get_theme_mod( 'full_post' );

	    // get user Read More link text
	    $read_more_text = get_theme_mod( 'read_more_text' );

	    // use i18n'ed text if empty
	    if ( empty( $read_more_text ) )
		    $read_more_text = __( 'Continue reading', 'author' );

	    // if show full post is on and not on a search results page
        if ( ( $show_full_post == 'yes' ) && ! is_search() ) {

	        // use the read more link if present
	        if ( $ismore ) {
		        the_content( wp_kses_post( $read_more_text ) . " <span class='screen-reader-text'>" . get_the_title() . "</span>" );
	        } else {
		        the_content();
	        }
        }
        // use the read more link if present
        elseif ( $ismore ) {
            the_content( wp_kses_post( $read_more_text ) . " <span class='screen-reader-text'>" . get_the_title() . "</span>" );
        } // otherwise the excerpt is automatic, so output it
        else {
            the_excerpt();
        }
    }
}

// filter the link on excerpts
if( !function_exists('ct_author_excerpt_read_more_link' ) ) {
	function ct_author_excerpt_read_more_link( $output ) {

		global $post;

		// get user Read More link text
		$read_more_text = get_theme_mod( 'read_more_text' );

		// use i18n'ed text if empty
		if ( empty( $read_more_text ) )
			$read_more_text = __( 'Continue reading', 'author' );

		return $output . "<p><a class='more-link' href='" . get_permalink() . "'>" . wp_kses_post( $read_more_text ) . " <span class='screen-reader-text'>" . get_the_title() . "</span></a></p>";
	}
}
add_filter('the_excerpt', 'ct_author_excerpt_read_more_link');

// change the length of the excerpts
function ct_author_custom_excerpt_length( $length ) {

    $new_excerpt_length = get_theme_mod('excerpt_length');

    // if there is a new length set and it's not 15, change it
    if( ! empty( $new_excerpt_length ) && $new_excerpt_length != 25 ){
        return $new_excerpt_length;
    }
    // allow 0 to be an option if user wants to remove the excerpt entirely
    elseif( $new_excerpt_length === 0 ) {
	    return 0;
    }
	else {
        return 25;
    }
}
add_filter( 'excerpt_length', 'ct_author_custom_excerpt_length', 99 );

// switch [...] to ellipsis on automatic excerpt
if( !function_exists('ct_author_new_excerpt_more' ) ) {
	function ct_author_new_excerpt_more( $more ) {

		// get user set excerpt length
		$new_excerpt_length = get_theme_mod('excerpt_length');

		// don't return trailing ellipsis if user removed excerpt
		if( $new_excerpt_length === 0 ) {
			return '';
		} else {
			return '&#8230;';
		}
	}
}
add_filter('excerpt_more', 'ct_author_new_excerpt_more');

// turns of the automatic scrolling to the read more link
if( !function_exists('ct_author_remove_more_link_scroll' ) ) {
	function ct_author_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );

		return $link;
	}
}
add_filter( 'the_content_more_link', 'ct_author_remove_more_link_scroll' );

// for displaying featured images
if( !function_exists('ct_author_featured_image' ) ) {
	function ct_author_featured_image() {

		// get post object
		global $post;

		// establish featured image var
		$featured_image = '';

		// if post has an image
		if ( has_post_thumbnail( $post->ID ) ) {

			if ( is_singular() ) {
				$featured_image = '<div class="featured-image">' . get_the_post_thumbnail( $post->ID, 'full' ) . '</div>';
			} else {
				$featured_image = '<div class="featured-image"><a href="' . get_permalink() . '">' . get_the_title() . get_the_post_thumbnail( $post->ID, 'full' ) . '</a></div>';
			}
		}

		// allow videos to be added
		$featured_image = apply_filters( 'ct_author_featured_image', $featured_image );

		if( $featured_image ) {
			echo $featured_image;
		}
	}
}

// associative array of social media sites
if( !function_exists( 'ct_author_social_array' ) ) {
	function ct_author_social_array() {

		$social_sites = array(
			'twitter'       => 'author_twitter_profile',
			'facebook'      => 'author_facebook_profile',
			'google-plus'   => 'author_googleplus_profile',
			'pinterest'     => 'author_pinterest_profile',
			'linkedin'      => 'author_linkedin_profile',
			'youtube'       => 'author_youtube_profile',
			'vimeo'         => 'author_vimeo_profile',
			'tumblr'        => 'author_tumblr_profile',
			'instagram'     => 'author_instagram_profile',
			'flickr'        => 'author_flickr_profile',
			'dribbble'      => 'author_dribbble_profile',
			'rss'           => 'author_rss_profile',
			'reddit'        => 'author_reddit_profile',
			'soundcloud'    => 'author_soundcloud_profile',
			'spotify'       => 'author_spotify_profile',
			'vine'          => 'author_vine_profile',
			'yahoo'         => 'author_yahoo_profile',
			'behance'       => 'author_behance_profile',
			'codepen'       => 'author_codepen_profile',
			'delicious'     => 'author_delicious_profile',
			'stumbleupon'   => 'author_stumbleupon_profile',
			'deviantart'    => 'author_deviantart_profile',
			'digg'          => 'author_digg_profile',
			'github'        => 'author_github_profile',
			'hacker-news'   => 'author_hacker-news_profile',
			'foursquare'    => 'author_foursquare_profile',
			'slack'         => 'author_slack_profile',
			'slideshare'    => 'author_slideshare_profile',
			'skype'         => 'author_skype_profile',
			'whatsapp'      => 'author_whatsapp_profile',
			'qq'            => 'author_qq_profile',
			'wechat'        => 'author_wechat_profile',
			'xing'          => 'author_xing_profile',
			'500px'         => 'author_500px_profile',
			'paypal'        => 'author_paypal_profile',
			'steam'         => 'author_steam_profile',
			'vk'            => 'author_vk_profile',
			'weibo'         => 'author_weibo_profile',
			'tencent-weibo' => 'author_tencent_weibo_profile',
			'email'         => 'author_email_profile'
		);

		return apply_filters( 'ct_author_social_array_filter', $social_sites );
	}
}

// output social icons
if( ! function_exists('ct_author_social_icons_output') ) {
    function ct_author_social_icons_output() {

        // get social sites array
        $social_sites = ct_author_social_array();

	    // icons that should use a special square icon
	    $square_icons = array('linkedin', 'twitter', 'vimeo', 'youtube', 'pinterest', 'rss', 'reddit', 'tumblr', 'steam', 'xing', 'github', 'google-plus', 'behance', 'facebook');

        // store the site name and url
        foreach ( $social_sites as $social_site => $profile ) {

            if ( strlen( get_theme_mod( $social_site ) ) > 0 ) {
                $active_sites[$social_site] = $social_site;
            }
        }

        // for each active social site, add it as a list item
        if ( ! empty( $active_sites ) ) {

            echo "<div class='social-media-icons'><ul>";

            foreach ( $active_sites as $key => $active_site ) {

	            // get the square or plain class
	            if ( in_array( $active_site, $square_icons ) ) {
		            $class = 'fa fa-' . $active_site . '-square';
	            } else {
		            $class = 'fa fa-' . $active_site;
	            }

                if ( $active_site == 'email' ) {
                    ?>
                    <li>
                        <a class="email" target="_blank" href="mailto:<?php echo antispambot( is_email( get_theme_mod( $active_site ) ) ); ?>">
                            <i class="fa fa-envelope" title="<?php esc_attr( _e('email', 'author') ); ?>"></i>
                        </a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a class="<?php echo esc_attr( $active_site ); ?>" target="_blank" href="<?php echo esc_url( get_theme_mod( $active_site ) ); ?>">
                            <i class="<?php echo esc_attr( $class ); ?>" title="<?php echo esc_attr( $active_site ); ?>"></i>
                        </a>
                    </li>
                <?php
                }
            }
            echo "</ul></div>";
        }
    }
}

/*
 * WP will apply the ".menu-primary-items" class & id to the containing <div> instead of <ul>
 * making styling difficult and confusing. Using this wrapper to add a unique class to make styling easier.
 */
function ct_author_wp_page_menu() {
    wp_page_menu(array(
            "menu_class" => "menu-unset",
            "depth"      => -1
        )
    );
}

// used in header.php for primary avatar and comments
function ct_author_output_avatar() {

    // get method for displaying avatar
    $avatar_method = get_theme_mod('avatar_method');

    // if neither gravatar, nor upload used
    $avatar = '';

    // if using gravatar
    if( $avatar_method == 'gravatar' ){
        // get the avatar from the admin email
        $avatar = get_avatar( get_option('admin_email'));
        // use regex to grab source from <img /> markup
        $avatar = ct_author_get_avatar_url($avatar);
    }
    // if using an upload
    elseif( $avatar_method == 'upload') {
        // get the uploaded image
        $avatar = get_theme_mod('avatar');
    }
    return $avatar;
}

function ct_author_get_avatar_url($get_avatar){
	// WP User Avatar switches the use of quotes
	if ( class_exists( 'WP_User_Avatar' ) ) {
		preg_match('/src="([^"]*)"/i', $get_avatar, $matches);
	} else {
		preg_match("/src='([^']*)'/i", $get_avatar, $matches);
	}
    return $matches[1];
}

function ct_author_nav_dropdown_buttons( $item_output, $item, $depth, $args ) {

    if ( 'primary' == $args->theme_location) {

        if( in_array('menu-item-has-children', $item->classes ) || in_array('page_item_has_children', $item->classes ) ) {
            $item_output = str_replace( $args->link_after . '</a>', $args->link_after . '</a><button class="toggle-dropdown" aria-expanded="false"><span class="screen-reader-text">open child menu</span></button>', $item_output );
        }
    }

    return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'ct_author_nav_dropdown_buttons', 10, 4 );

// custom css output
function ct_author_custom_css_output(){

    $custom_css = get_theme_mod('custom_css');

    /* output custom css */
    if( $custom_css ) {
	    $custom_css = wp_filter_nohtml_kses( $custom_css );
        wp_add_inline_style( 'ct-author-style', $custom_css );
        wp_add_inline_style( 'ct-author-style-rtl', $custom_css );
    }
}
add_action('wp_enqueue_scripts', 'ct_author_custom_css_output', 20);

function ct_author_body_class( $classes ) {

	global $post;

    /* get full post setting */
    $full_post = get_theme_mod('full_post');

    /* if full post setting on */
    if( $full_post == 'yes' ) {
        $classes[] = 'full-post';
    }

	if ( is_singular() ) {
		$classes[] = 'singular';
		if ( is_singular('page') ) {
			$classes[] = 'singular-page';
			$classes[] = 'singular-page-' . $post->ID;
		} elseif ( is_singular('post') ) {
			$classes[] = 'singular-post';
			$classes[] = 'singular-post-' . $post->ID;
		} elseif ( is_singular('attachment') ) {
			$classes[] = 'singular-attachment';
			$classes[] = 'singular-attachment-' . $post->ID;
		}
	}

    return $classes;
}
add_filter( 'body_class', 'ct_author_body_class' );

function ct_author_post_class( $classes ) {

	$classes[] = 'entry';

	return $classes;
}
add_filter( 'post_class', 'ct_author_post_class' );

function ct_author_reset_customizer_options() {

    // validate name and value
    if( empty( $_POST['author_reset_customizer'] ) || 'author_reset_customizer_settings' !== $_POST['author_reset_customizer'] )
        return;

    // validate nonce
    if( ! wp_verify_nonce( $_POST['author_reset_customizer_nonce'], 'author_reset_customizer_nonce' ) )
        return;

    // validate user permissions
    if( ! current_user_can( 'manage_options' ) )
        return;

    // delete customizer mods
    remove_theme_mods();

    $redirect = admin_url( 'themes.php?page=author-options' );
    $redirect = add_query_arg( 'author_status', 'deleted', $redirect);

    // safely redirect
    wp_safe_redirect( $redirect ); exit;
}
add_action( 'admin_init', 'ct_author_reset_customizer_options' );

function ct_author_delete_settings_notice() {

    if ( isset( $_GET['author_status'] ) ) {
        ?>
        <div class="updated">
            <p><?php _e( 'Customizer settings deleted', 'author' ); ?>.</p>
        </div>
    <?php
    }
}
add_action( 'admin_notices', 'ct_author_delete_settings_notice' );

if ( ! function_exists( '_wp_render_title_tag' ) ) :
    function ct_author_add_title_tag() {
        ?>
        <title><?php wp_title(); ?></title>
    <?php
    }
    add_action( 'wp_head', 'ct_author_add_title_tag' );
endif;

function ct_author_sticky_post_marker() {

    if( is_sticky() && !is_archive() ) {
        echo '<span class="sticky-status">' . __("Featured Post", "author") . '</span>';
    }
}
add_action( 'archive_post_before', 'ct_author_sticky_post_marker' );

function ct_author_loop_pagination(){

	// don't output if Jetpack infinite scroll is being used
	if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) )
		return;

	global $wp_query;

	// If there's not more than one page, return nothing.
	if ( 1 >= $wp_query->max_num_pages ) {
		return;
	}

	/* Set up some default arguments for the paginate_links() function. */
	$defaults = array(
		'base'         => add_query_arg( 'paged', '%#%' ),
		'format'       => '',
		'mid_size'     => 1
	);

	$loop_pagination = '<nav class="pagination loop-pagination">';
	$loop_pagination .= paginate_links( $defaults );
	$loop_pagination .= '</nav>';

	return $loop_pagination;
}

// Adds useful meta tags
function ct_author_add_meta_elements() {

	$meta_elements = '';

	/* Charset */
	$meta_elements .= sprintf( '<meta charset="%s" />' . "\n", get_bloginfo( 'charset' ) );

	/* Viewport */
	$meta_elements .= '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";

	/* Theme name and current version */
	$theme    = wp_get_theme( get_template() );
	$template = sprintf( '<meta name="template" content="%s %s" />' . "\n", esc_attr( $theme->get( 'Name' ) ), esc_attr( $theme->get( 'Version' ) ) );
	$meta_elements .= $template;

	echo $meta_elements;
}
add_action( 'wp_head', 'ct_author_add_meta_elements', 1 );

/* Move the WordPress generator to a better priority. */
remove_action( 'wp_head', 'wp_generator' );
add_action( 'wp_head', 'wp_generator', 1 );

function ct_author_infinite_scroll_render(){
	while( have_posts() ) {
		the_post();
		get_template_part( 'content', 'archive' );
	}
}