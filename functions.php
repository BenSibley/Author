<?php

require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );
foreach ( glob( trailingslashit( get_template_directory() ) . 'inc/*' ) as $filename ) {
	include $filename;
}

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
			'primary' => __( 'Primary', 'author' )
		) );
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
						'reply_text' => __( 'Reply', 'author' ),
						'depth'      => $depth,
						'max_depth'  => $args['max_depth']
					) ) ); ?>
					<?php edit_comment_link( __( 'Edit', 'author' ) ); ?>
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
		$label     = $req ? '*' : ' ' . __( '(optional)', 'author' );
		$aria_req  = $req ? "aria-required='true'" : '';

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
add_filter( 'comment_form_default_fields', 'ct_author_update_fields' );

if ( ! function_exists( 'ct_author_update_comment_field' ) ) {
	function ct_author_update_comment_field( $comment_field ) {

		$comment_field =
			'<p class="comment-form-comment">
	            <label for="comment">' . __( "Comment", "author" ) . '</label>
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

if ( ! function_exists( 'ct_author_excerpt' ) ) {
	function ct_author_excerpt() {

		global $post;
		$show_full_post = get_theme_mod( 'full_post' );
		$read_more_text = get_theme_mod( 'read_more_text' );
		$ismore         = strpos( $post->post_content, '<!--more-->' );

		if ( ( $show_full_post == 'yes' ) && ! is_search() ) {
			if ( $ismore ) {
				// Has to be written this way because i18n text CANNOT be stored in a variable
				if ( ! empty( $read_more_text ) ) {
					the_content( esc_html( $read_more_text ) . " <span class='screen-reader-text'>" . esc_html( get_the_title() ) . "</span>" );
				} else {
					the_content( __( 'Continue reading', 'author' ) . " <span class='screen-reader-text'>" . esc_html( get_the_title() ) . "</span>" );
				}
			} else {
				the_content();
			}
		} elseif ( $ismore ) {
			if ( ! empty( $read_more_text ) ) {
				the_content( esc_html( $read_more_text ) . " <span class='screen-reader-text'>" . esc_html( get_the_title() ) . "</span>" );
			} else {
				the_content( __( 'Continue reading', 'author' ) . " <span class='screen-reader-text'>" . esc_html( get_the_title() ) . "</span>" );
			}
		} else {
			the_excerpt();
		}
	}
}

if ( ! function_exists( 'ct_author_excerpt_read_more_link' ) ) {
	function ct_author_excerpt_read_more_link( $output ) {

		global $post;
		$read_more_text = get_theme_mod( 'read_more_text' );

		if ( ! empty( $read_more_text ) ) {
			return $output . "<p><a class='more-link' href='" . esc_url( get_permalink() ) . "'>" . esc_html( $read_more_text ) . " <span class='screen-reader-text'>" . esc_html( get_the_title() ) . "</span></a></p>";
		} else {
			return $output . "<p><a class='more-link' href='" . esc_url( get_permalink() ) . "'>" . __( 'Continue reading', 'author' ) . " <span class='screen-reader-text'>" . esc_html( get_the_title() ) . "</span></a></p>";
		}
	}
}
add_filter( 'the_excerpt', 'ct_author_excerpt_read_more_link' );

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

if ( ! function_exists( 'ct_author_new_excerpt_more' ) ) {
	function ct_author_new_excerpt_more( $more ) {

		$new_excerpt_length = get_theme_mod( 'excerpt_length' );
		$excerpt_more       = ( $new_excerpt_length === 0 ) ? '' : '&#8230;';

		return $excerpt_more;
	}
}
add_filter( 'excerpt_more', 'ct_author_new_excerpt_more' );

if ( ! function_exists( 'ct_author_remove_more_link_scroll' ) ) {
	function ct_author_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
}
add_filter( 'the_content_more_link', 'ct_author_remove_more_link_scroll' );

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
			'email'         => 'author_email_profile',
			'email-form'    => 'author_email_form_profile'
		);

		return apply_filters( 'ct_author_social_array_filter', $social_sites );
	}
}

if ( ! function_exists( 'ct_author_social_icons_output' ) ) {
	function ct_author_social_icons_output() {

		$social_sites = ct_author_social_array();
		$square_icons = array(
			'linkedin',
			'twitter',
			'vimeo',
			'youtube',
			'pinterest',
			'rss',
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
						$class = 'fa fa-' . $active_site . '-square';
					} else {
						$class = 'fa fa-' . $active_site;
					}
					if ( $active_site == 'email-form' ) {
						$class = 'fa fa-envelope-o';
					}

					if ( $active_site == 'email' ) { ?>
						<li>
							<a class="email" target="_blank"
							   href="mailto:<?php echo antispambot( is_email( get_theme_mod( $active_site ) ) ); ?>">
								<i class="fa fa-envelope" title="<?php esc_attr_e( 'email', 'author' ); ?>"></i>
								<span class="screen-reader-text"><?php esc_html_e('email', 'author'); ?></span>
							</a>
						</li>
					<?php } elseif ( $active_site == 'skype' ) { ?>
						<li>
							<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
							   href="<?php echo esc_url( get_theme_mod( $active_site ), array( 'http', 'https', 'skype' ) ); ?>">
								<i class="<?php echo esc_attr( $class ); ?>"
								   title="<?php echo esc_attr( $active_site ); ?>"></i>
								<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
							</a>
						</li>
					<?php } else { ?>
						<li>
							<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
							   href="<?php echo esc_url( get_theme_mod( $active_site ) ); ?>">
								<i class="<?php echo esc_attr( $class ); ?>"
								   title="<?php echo esc_attr( $active_site ); ?>"></i>
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
				$item_output = str_replace( $args->link_after . '</a>', $args->link_after . '</a><button class="toggle-dropdown" aria-expanded="false"><span class="screen-reader-text">open child menu</span></button>', $item_output );
			}
		}

		return $item_output;
	}
}
add_filter( 'walker_nav_menu_start_el', 'ct_author_nav_dropdown_buttons', 10, 4 );

if ( ! function_exists( ( 'ct_author_custom_css_output' ) ) ) {
	function ct_author_custom_css_output() {

		$custom_css = get_theme_mod( 'custom_css' );

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
			?>
			<div class="updated">
				<p><?php _e( 'Customizer settings deleted', 'author' ); ?>.</p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'ct_author_delete_settings_notice' );

if ( ! function_exists( ( 'ct_author_sticky_post_marker' ) ) ) {
	function ct_author_sticky_post_marker() {

		if ( is_sticky() && ! is_archive() ) {
			echo '<span class="sticky-status">' . __( "Featured Post", "author" ) . '</span>';
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

		/* Blog */
		if ( is_home() ) {
			get_template_part( 'content', 'archive' );
		} /* Post */
		elseif ( is_singular( 'post' ) ) {
			get_template_part( 'content' );
		} /* Page */
		elseif ( is_page() ) {
			get_template_part( 'content', 'page' );
		} /* Attachment */
		elseif ( is_attachment() ) {
			get_template_part( 'content', 'attachment' );
		} /* Archive */
		elseif ( is_archive() ) {
			get_template_part( 'content', 'archive' );
		} /* Custom Post Type */
		else {
			get_template_part( 'content' );
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
			'page' => 'author-options'
		),
		admin_url( 'themes.php' )
	);
	wp_redirect( esc_url( $welcome_url ) );
}
add_action( 'after_switch_theme', 'ct_author_welcome_redirect' );