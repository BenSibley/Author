<?php

//----------------------------------------------------------------------------------
//	Include all required files
//----------------------------------------------------------------------------------
require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/customizer.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/deprecated.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/review.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/scripts.php' );

//----------------------------------------------------------------------------------
//	Include review request
//----------------------------------------------------------------------------------
require_once( trailingslashit( get_template_directory() ) . 'dnh/handler.php' );
new WP_Review_Me( array(
		'days_after' => 14,
		'type'       => 'theme',
		'slug'       => 'author',
		'message'    => __( 'Hey! Sorry to interrupt, but you\'ve been using Author for a little while now. If you\'re happy with this theme, could you take a minute to leave a review? <i>You won\'t see this notice again after closing it.</i>', 'author' )
	)
);

if ( ! function_exists( ( 'ct_author_set_content_width' ) ) ) {
	function ct_author_set_content_width() {
		if ( ! isset( $content_width ) ) {
			$content_width = 622;
		}
	}
}
add_action( 'after_setup_theme', 'ct_author_set_content_width', 0 );

if ( ! function_exists( 'ct_author_theme_setup' ) ) {
	function ct_author_theme_setup() {

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) );
		add_theme_support( 'infinite-scroll', array(
			'container' => 'loop-container',
			'footer'    => 'overflow-container',
			'render'    => 'ct_author_infinite_scroll_render'
		) );

		load_theme_textdomain( 'author', get_template_directory() . '/languages' );

		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'author' )
		) );

		// Add WooCommerce support
		add_theme_support( 'woocommerce' );
		// Add support for WooCommerce image gallery features
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action( 'after_setup_theme', 'ct_author_theme_setup', 10 );

if ( ! function_exists( ( 'ct_author_register_widget_areas' ) ) ) {
	function ct_author_register_widget_areas() {

		// after post content
		register_sidebar( array(
			'name'          => esc_html__( 'Primary Sidebar', 'author' ),
			'id'            => 'primary',
			'description'   => esc_html__( 'Widgets in this area will be shown in the sidebar', 'author' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		) );
	}
}
add_action( 'widgets_init', 'ct_author_register_widget_areas' );

if ( ! function_exists( 'ct_author_customize_comments' ) ) {
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
						'reply_text' => _x( 'Reply', 'verb: reply to this comment', 'author' ),
						'depth'      => $depth,
						'max_depth'  => $args['max_depth']
					) ) ); ?>
					<?php edit_comment_link( _x( 'Edit', 'verb: edit this comment', 'author' ) ); ?>
				</div>
			<?php } ?>
		</article>
		<?php
	}
}

if ( ! function_exists( 'ct_author_update_fields' ) ) {
	function ct_author_update_fields( $fields ) {

		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$label     = $req ? '*' : ' ' . esc_html__( '(optional)', 'author' );
		$aria_req  = $req ? "aria-required='true'" : '';

		$fields['author'] =
			'<p class="comment-form-author">
	            <label for="author">' . _x( "Name", "noun", "author" ) . $label . '</label>
	            <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
			'" size="30" ' . $aria_req . ' />
	        </p>';

		$fields['email'] =
			'<p class="comment-form-email">
	            <label for="email">' . esc_html_x( "Email", "noun", "author" ) . $label . '</label>
	            <input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) .
			'" size="30" ' . $aria_req . ' />
	        </p>';

		$fields['url'] =
			'<p class="comment-form-url">
	            <label for="url">' . esc_html__( "Website", "author" ) . '</label>
	            <input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" size="30" />
	            </p>';

		return $fields;
	}
}
add_filter( 'comment_form_default_fields', 'ct_author_update_fields' );

if ( ! function_exists( 'ct_author_update_comment_field' ) ) {
	function ct_author_update_comment_field( $comment_field ) {

		// don't filter the WooCommerce review form
		if ( function_exists( 'is_woocommerce' ) ) {
			if ( is_woocommerce() ) {
				return $comment_field;
			}
		}
		
		$comment_field =
			'<p class="comment-form-comment">
	            <label for="comment">' . _x( "Comment", "noun", "author" ) . '</label>
	            <textarea required id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
	        </p>';

		return $comment_field;
	}
}
add_filter( 'comment_form_field_comment', 'ct_author_update_comment_field' );

if ( ! function_exists( 'ct_author_remove_comments_notes_after' ) ) {
	function ct_author_remove_comments_notes_after( $defaults ) {
		$defaults['comment_notes_after'] = '';
		return $defaults;
	}
}
add_action( 'comment_form_defaults', 'ct_author_remove_comments_notes_after' );

if ( ! function_exists( 'ct_author_filter_read_more_link' ) ) {
	function ct_author_filter_read_more_link( $custom = false ) {
		global $post;
		$ismore             = strpos( $post->post_content, '<!--more-->' );
		$read_more_text     = get_theme_mod( 'read_more_text' );
		$new_excerpt_length = get_theme_mod( 'excerpt_length' );
		$excerpt_more       = ( $new_excerpt_length === 0 ) ? '' : '&#8230;';
		$output = '';

		// add ellipsis for automatic excerpts
		if ( empty( $ismore ) && $custom !== true ) {
			$output .= $excerpt_more;
		}
		// Because i18n text cannot be stored in a variable
		if ( empty( $read_more_text ) ) {
			$output .= '<div class="more-link-wrapper"><a class="more-link" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Continue reading', 'author' ) . '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a></div>';
		} else {
			$output .= '<div class="more-link-wrapper"><a class="more-link" href="' . esc_url( get_permalink() ) . '">' . esc_html( $read_more_text ) . '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a></div>';
		}
		return $output;
	}
}
add_filter( 'the_content_more_link', 'ct_author_filter_read_more_link' ); // more tags
add_filter( 'excerpt_more', 'ct_author_filter_read_more_link', 10 ); // automatic excerpts

// handle manual excerpts
if ( ! function_exists( 'ct_author_filter_manual_excerpts' ) ) {
	function ct_author_filter_manual_excerpts( $excerpt ) {
		$excerpt_more = '';
		if ( has_excerpt() ) {
			$excerpt_more = ct_author_filter_read_more_link( true );
		}
		return $excerpt . $excerpt_more;
	}
}
add_filter( 'get_the_excerpt', 'ct_author_filter_manual_excerpts' );

if ( ! function_exists( 'ct_author_excerpt' ) ) {
	function ct_author_excerpt() {
		global $post;
		$show_full_post = get_theme_mod( 'full_post' );
		$ismore         = strpos( $post->post_content, '<!--more-->' );

		if ( $show_full_post === 'yes' || $ismore ) {
			the_content();
		} else {
			the_excerpt();
		}
	}
}

if ( ! function_exists( ( 'ct_author_custom_excerpt_length' ) ) ) {
	function ct_author_custom_excerpt_length( $length ) {

		$new_excerpt_length = get_theme_mod( 'excerpt_length' );

		if ( ! empty( $new_excerpt_length ) && $new_excerpt_length != 25 ) {
			return $new_excerpt_length;
		} elseif ( $new_excerpt_length === 0 ) {
			return 0;
		} else {
			return 25;
		}
	}
}
add_filter( 'excerpt_length', 'ct_author_custom_excerpt_length', 99 );

if ( ! function_exists( 'ct_author_remove_more_link_scroll' ) ) {
	function ct_author_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
}
add_filter( 'the_content_more_link', 'ct_author_remove_more_link_scroll' );

// Yoast OG description has "Continue readingTitle of the Post" due to its use of get_the_excerpt(). This fixes that.
function ct_author_update_yoast_og_description( $ogdesc ) {
	$read_more_text = get_theme_mod( 'read_more_text' );
	if ( empty( $read_more_text ) ) {
		$read_more_text = esc_html__( 'Continue reading', 'author' );
	}
	$ogdesc = substr( $ogdesc, 0, strpos( $ogdesc, $read_more_text ) );

	return $ogdesc;
}
add_filter( 'wpseo_opengraph_desc', 'ct_author_update_yoast_og_description' );

if ( ! function_exists( 'ct_author_featured_image' ) ) {
	function ct_author_featured_image() {

		global $post;
		$featured_image = '';

		if ( has_post_thumbnail( $post->ID ) ) {

			if ( is_singular() ) {
				$featured_image = '<div class="featured-image">' . get_the_post_thumbnail( $post->ID, 'full' ) . '</div>';
			} else {
				$featured_image = '<div class="featured-image"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . get_the_post_thumbnail( $post->ID, 'full' ) . '</a></div>';
			}
		}

		$featured_image = apply_filters( 'ct_author_featured_image', $featured_image );

		if ( $featured_image ) {
			echo $featured_image;
		}
	}
}

if ( ! function_exists( 'ct_author_social_array' ) ) {
	function ct_author_social_array() {

		$social_sites = array(
			'twitter'       => 'author_twitter_profile',
			'facebook'      => 'author_facebook_profile',
			'instagram'     => 'author_instagram_profile',
			'linkedin'      => 'author_linkedin_profile',
			'pinterest'     => 'author_pinterest_profile',
			'youtube'       => 'author_youtube_profile',
			'rss'           => 'author_rss_profile',
			'email'         => 'author_email_profile',
			'phone'    			=> 'author_phone_profile',
			'email-form'    => 'author_email_form_profile',
			'amazon'        => 'author_amazon_profile',
			'bandcamp'      => 'author_bandcamp_profile',
			'behance'       => 'author_behance_profile',
			'bitbucket'     => 'author_bitbucket_profile',
			'codepen'       => 'author_codepen_profile',
			'delicious'     => 'author_delicious_profile',
			'deviantart'    => 'author_deviantart_profile',
			'digg'          => 'author_digg_profile',
			'discord'       => 'author_discord_profile',
			'dribbble'      => 'author_dribbble_profile',
			'etsy'          => 'author_etsy_profile',
			'flickr'        => 'author_flickr_profile',
			'foursquare'    => 'author_foursquare_profile',
			'github'        => 'author_github_profile',
			'google-plus'   => 'author_googleplus_profile',
			'google-wallet' => 'author_google-wallet_profile',
			'hacker-news'   => 'author_hacker-news_profile',
			'medium'        => 'author_medium_profile',
			'meetup'        => 'author_mixcloud_profile',
			'mixcloud'      => 'author_meetup_profile',			
			'ok-ru'         => 'author_ok_ru_profile',
			'patreon'				=> 'author_patreon_profile',
			'paypal'        => 'author_paypal_profile',
			'podcast'       => 'author_podcast_profile',
			'qq'            => 'author_qq_profile',
			'quora'         => 'author_quora_profile',
			'ravelry'       => 'author_ravelry_profile',
			'reddit'        => 'author_reddit_profile',
			'skype'         => 'author_skype_profile',
			'slack'         => 'author_slack_profile',
			'slideshare'    => 'author_slideshare_profile',
			'snapchat'      => 'author_snapchat_profile',
			'soundcloud'    => 'author_soundcloud_profile',
			'spotify'       => 'author_spotify_profile',
			'stack-overflow' => 'author_stack_overflow_profile',
			'steam'         => 'author_steam_profile',
			'stumbleupon'   => 'author_stumbleupon_profile',
			'telegram'      => 'author_telegram_profile',
			'tencent-weibo' => 'author_tencent_weibo_profile',
			'tumblr'        => 'author_tumblr_profile',
			'twitch'        => 'author_twitch_profile',
			'vimeo'         => 'author_vimeo_profile',
			'vine'          => 'author_vine_profile',
			'vk'            => 'author_vk_profile',
			'wechat'        => 'author_wechat_profile',
			'weibo'         => 'author_weibo_profile',
			'whatsapp'      => 'author_whatsapp_profile',
			'xing'          => 'author_xing_profile',
			'yahoo'         => 'author_yahoo_profile',
			'yelp'          => 'author_yelp_profile',
			'500px'         => 'author_500px_profile'
		);

		return apply_filters( 'ct_author_social_array_filter', $social_sites );
	}
}

if ( ! function_exists( 'ct_author_social_icons_output' ) ) {
	function ct_author_social_icons_output() {

		$social_sites = ct_author_social_array();
		$square_icons = array(
			'twitter',
			'vimeo',
			'youtube',
			'pinterest',
			'reddit',
			'tumblr',
			'steam',
			'xing',
			'github',
			'google-plus',
			'behance',
			'facebook'
		);

		foreach ( $social_sites as $social_site => $profile ) {

			if ( strlen( get_theme_mod( $social_site ) ) > 0 ) {
				$active_sites[ $social_site ] = $social_site;
			}
		}

		if ( ! empty( $active_sites ) ) {

			echo "<div class='social-media-icons'><ul>";

				foreach ( $active_sites as $key => $active_site ) {

					// get the square or plain class
					if ( in_array( $active_site, $square_icons ) ) {
						$class = 'fab fa-' . $active_site . '-square';
					} elseif ( $active_site == 'rss' ) {
						$class = 'fas fa-rss';
					} elseif ( $active_site == 'email-form' ) {
						$class = 'far fa-envelope';
					} elseif ( $active_site == 'podcast' ) {
						$class = 'fas fa-podcast';
					} elseif ( $active_site == 'ok-ru' ) {
						$class = 'fab fa-odnoklassniki';
					} elseif ( $active_site == 'wechat' ) {
						$class = 'fab fa-weixin';
					} elseif ( $active_site == 'phone' ) {
						$class = 'fas fa-phone';
					} else {
						$class = 'fab fa-' . $active_site;
					}

					if ( $active_site == 'email' ) { ?>
						<li>
							<a class="email" target="_blank"
							   href="mailto:<?php echo antispambot( is_email( get_theme_mod( $active_site ) ) ); ?>">
								<i class="fas fa-envelope"></i>
								<span class="screen-reader-text"><?php echo esc_html_x('email', 'noun', 'author'); ?></span>
							</a>
						</li>
					<?php } elseif ( $active_site == 'skype' ) { ?>
						<li>
							<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
							   href="<?php echo esc_url( get_theme_mod( $active_site ), array( 'http', 'https', 'skype' ) ); ?>">
								<i class="<?php echo esc_attr( $class ); ?>"></i>
								<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
							</a>
						</li>
					<?php } elseif ( $active_site == 'phone' ) { ?>
						<li>
							<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
							   href="<?php echo esc_url( 'tel:' . get_theme_mod( $active_site ), array( 'tel' ) ); ?>">
								<i class="<?php echo esc_attr( $class ); ?>"></i>
								<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
							</a>
						</li>
					<?php } else { ?>
						<li>
							<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
							   href="<?php echo esc_url( get_theme_mod( $active_site ) ); ?>">
								<i class="<?php echo esc_attr( $class ); ?>"></i>
								<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
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
if ( ! function_exists( ( 'ct_author_wp_page_menu' ) ) ) {
	function ct_author_wp_page_menu() {
		wp_page_menu( array(
				"menu_class" => "menu-unset",
				"depth"      => - 1
			)
		);
	}
}

// used in header.php for primary avatar and comments
if ( ! function_exists( ( 'ct_author_output_avatar' ) ) ) {
	function ct_author_output_avatar() {

		$avatar_method = get_theme_mod( 'avatar_method' );
		$avatar        = '';

		if ( $avatar_method == 'gravatar' ) {
			$avatar = get_avatar( get_option( 'admin_email' ) );
			// use regex to grab source from <img /> markup
			$avatar = ct_author_get_avatar_url( $avatar );
		} elseif ( $avatar_method == 'upload' ) {
			$avatar = get_theme_mod( 'avatar' );
		}

		return $avatar;
	}
}

if ( ! function_exists( ( 'ct_author_get_avatar_url' ) ) ) {
	function ct_author_get_avatar_url( $get_avatar ) {
		// WP User Avatar switches the use of quotes
		if ( class_exists( 'WP_User_Avatar' ) ) {
			preg_match( '/src="([^"]*)"/i', $get_avatar, $matches );
		} else {
			preg_match( "/src='([^']*)'/i", $get_avatar, $matches );
		}

		return $matches[1];
	}
}

if ( ! function_exists( ( 'ct_author_nav_dropdown_buttons' ) ) ) {
	function ct_author_nav_dropdown_buttons( $item_output, $item, $depth, $args ) {

		if ( $args->theme_location == 'primary' ) {

			if ( in_array( 'menu-item-has-children', $item->classes ) || in_array( 'page_item_has_children', $item->classes ) ) {
				$item_output = str_replace( $args->link_after . '</a>', $args->link_after . '</a><button class="toggle-dropdown" aria-expanded="false"><span class="screen-reader-text">'. esc_html__("open child menu", "author") .'</span></button>', $item_output );
			}
		}

		return $item_output;
	}
}
add_filter( 'walker_nav_menu_start_el', 'ct_author_nav_dropdown_buttons', 10, 4 );

if ( ! function_exists( ( 'ct_author_custom_css_output' ) ) ) {
	function ct_author_custom_css_output() {

		if ( function_exists( 'wp_get_custom_css' ) ) {
			$custom_css = wp_get_custom_css();
		} else {
			$custom_css = get_theme_mod( 'custom_css' );
		}

		if ( $custom_css ) {
			$custom_css = ct_author_sanitize_css( $custom_css );
			wp_add_inline_style( 'ct-author-style', $custom_css );
			wp_add_inline_style( 'ct-author-style-rtl', $custom_css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_author_custom_css_output', 20 );

if ( ! function_exists( ( 'ct_author_body_class' ) ) ) {
	function ct_author_body_class( $classes ) {

		global $post;

		$full_post = get_theme_mod( 'full_post' );

		if ( $full_post == 'yes' ) {
			$classes[] = 'full-post';
		}

		if ( is_singular() ) {
			$classes[] = 'singular';
			if ( is_singular( 'page' ) ) {
				$classes[] = 'singular-page';
				$classes[] = 'singular-page-' . $post->ID;
			} elseif ( is_singular( 'post' ) ) {
				$classes[] = 'singular-post';
				$classes[] = 'singular-post-' . $post->ID;
			} elseif ( is_singular( 'attachment' ) ) {
				$classes[] = 'singular-attachment';
				$classes[] = 'singular-attachment-' . $post->ID;
			}
		}

		return $classes;
	}
}
add_filter( 'body_class', 'ct_author_body_class' );

if ( ! function_exists( ( 'ct_author_post_class' ) ) ) {
	function ct_author_post_class( $classes ) {
		$classes[] = 'entry';

		return $classes;
	}
}
add_filter( 'post_class', 'ct_author_post_class' );

if ( ! function_exists( ( 'ct_author_reset_customizer_options' ) ) ) {
	function ct_author_reset_customizer_options() {

		if ( empty( $_POST['author_reset_customizer'] ) || 'author_reset_customizer_settings' !== $_POST['author_reset_customizer'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['author_reset_customizer_nonce'], 'author_reset_customizer_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$mods_array = array(
			'avatar_method',
			'avatar',
			'logo_upload',
			'full_post',
			'excerpt_length',
			'read_more_text',
			'comments_display',
			'custom_css'
		);

		$social_sites = ct_author_social_array();

		// add social site settings to mods array
		foreach ( $social_sites as $social_site => $value ) {
			$mods_array[] = $social_site;
		}

		$mods_array = apply_filters( 'ct_author_mods_to_remove', $mods_array );

		foreach ( $mods_array as $theme_mod ) {
			remove_theme_mod( $theme_mod );
		}

		$redirect = admin_url( 'themes.php?page=author-options' );
		$redirect = add_query_arg( 'author_status', 'deleted', $redirect );

		// safely redirect
		wp_safe_redirect( $redirect );
		exit;
	}
}
add_action( 'admin_init', 'ct_author_reset_customizer_options' );

if ( ! function_exists( ( 'ct_author_delete_settings_notice' ) ) ) {
	function ct_author_delete_settings_notice() {

		if ( isset( $_GET['author_status'] ) ) {
			
			if ( $_GET['author_status'] == 'deleted' ) {
				?>
				<div class="updated">
					<p><?php _e( 'Customizer settings deleted.', 'author' ); ?></p>
				</div>
				<?php
			} else if ( $_GET['author_status'] == 'activated' ) {
				?>
				<div class="updated">
					<p><?php printf( esc_html__( '%s successfully activated!', 'author' ), wp_get_theme( get_template() ) ); ?></p>
				</div>
				<?php
			}
		}
	}
}
add_action( 'admin_notices', 'ct_author_delete_settings_notice' );

if ( ! function_exists( ( 'ct_author_sticky_post_marker' ) ) ) {
	function ct_author_sticky_post_marker() {

		if ( is_sticky() && !is_archive() && !is_search() ) {
			echo '<span class="sticky-status">' . esc_html__( "Featured Post", "author" ) . '</span>';
		}
	}
}
add_action( 'archive_post_before', 'ct_author_sticky_post_marker' );

if ( ! function_exists( ( 'ct_author_add_meta_elements' ) ) ) {
	function ct_author_add_meta_elements() {

		$meta_elements = '';

		$meta_elements .= sprintf( '<meta charset="%s" />' . "\n", esc_html( get_bloginfo( 'charset' ) ) );
		$meta_elements .= '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";

		$theme    = wp_get_theme( get_template() );
		$template = sprintf( '<meta name="template" content="%s %s" />' . "\n", esc_attr( $theme->get( 'Name' ) ), esc_attr( $theme->get( 'Version' ) ) );
		$meta_elements .= $template;

		echo $meta_elements;
	}
}
add_action( 'wp_head', 'ct_author_add_meta_elements', 1 );

// Move the WordPress generator to a better priority.
remove_action( 'wp_head', 'wp_generator' );
add_action( 'wp_head', 'wp_generator', 1 );

if ( ! function_exists( ( 'ct_author_infinite_scroll_render' ) ) ) {
	function ct_author_infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'content', 'archive' );
		}
	}
}

if ( ! function_exists( 'ct_author_get_content_template' ) ) {
	function ct_author_get_content_template() {

		if ( is_home() || is_archive() ) {
			get_template_part( 'content-archive', get_post_type() );
		} else {
			get_template_part( 'content', get_post_type() );
		}
	}
}

// allow skype URIs to be used
if ( ! function_exists( ( 'ct_author_allow_skype_protocol' ) ) ) {
	function ct_author_allow_skype_protocol( $protocols ) {
		$protocols[] = 'skype';

		return $protocols;
	}
}
add_filter( 'kses_allowed_protocols' , 'ct_author_allow_skype_protocol' );

// trigger theme switch on link click and send to Appearance menu
function ct_author_welcome_redirect() {

	$welcome_url = add_query_arg(
		array(
			'page'          => 'author-options',
			'author_status' => 'activated'
		),
		admin_url( 'themes.php' )
	);
	wp_safe_redirect( esc_url_raw( $welcome_url ) );
}
add_action( 'after_switch_theme', 'ct_author_welcome_redirect' );

if ( function_exists( 'ct_author_pro_plugin_updater' ) ) {
	remove_action( 'admin_init', 'ct_author_pro_plugin_updater', 0 );
	add_action( 'admin_init', 'ct_author_pro_plugin_updater', 0 );
}

//----------------------------------------------------------------------------------
// Add paragraph tags for author bio displayed in content/archive-header.php.
// the_archive_description includes paragraph tags for tag and category descriptions, but not the author bio. 
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_author_modify_archive_descriptions' ) ) {
	function ct_author_modify_archive_descriptions( $description ) {

		if ( is_author() ) {
			$description = wpautop( $description );
		}
		return $description;
	}
}
add_filter( 'get_the_archive_description', 'ct_author_modify_archive_descriptions' );

function ct_author_scroll_to_top_arrow() {
	$setting = get_theme_mod('scroll_to_top');
	
	if ( $setting == 'yes' ) {
		echo '<button id="scroll-to-top" class="scroll-to-top"><span class="screen-reader-text">'. __('Scroll to the top', 'author') .'</span><i class="fas fa-arrow-up"></i></button>';
	}
}
add_action( 'body_bottom', 'ct_author_scroll_to_top_arrow');