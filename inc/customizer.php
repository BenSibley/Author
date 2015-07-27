<?php

/* Add customizer panels, sections, settings, and controls */
add_action( 'customize_register', 'ct_author_add_customizer_content' );

function ct_author_add_customizer_content( $wp_customize ) {

	/***** Reorder default sections *****/

	$wp_customize->get_section('title_tagline')->priority     = 1;
	$wp_customize->get_section('static_front_page')->priority = 5;
	$wp_customize->get_section('static_front_page')->title    = __('Front Page', 'author');
	$wp_customize->get_section('nav')->priority               = 10;
	$wp_customize->get_section('nav')->title                  = __('Menus', 'author');
	
	/***** Add PostMessage Support *****/
	
	// Add postMessage support for site title and description.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	
	/***** Add Custom Controls *****/

	// create url input control
	class ct_author_url_input_control extends WP_Customize_Control {
		// create new type called 'url'
		public $type = 'url';
		// the content to be output in the Customizer
		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input type="url" <?php $this->link(); ?> value="<?php echo esc_url_raw( $this->value() ); ?>" />
			</label>
		<?php
		}
	}

	// number input control
	class ct_author_number_input_control extends WP_Customize_Control {
		public $type = 'number';

		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input type="number" <?php $this->link(); ?> value="<?php echo $this->value(); ?>" />
			</label>
		<?php
		}
	}

	// create textarea control
	class ct_author_textarea_control extends WP_Customize_Control {
		public $type = 'textarea';

		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea rows="8" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
		<?php
		}
	}

	// create multi-checkbox/select control
	class ct_author_multi_checkbox_control extends WP_Customize_Control {
		public $type = 'multi-checkbox';

		public function render_content() {

			if ( empty( $this->choices ) )
				return;
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select id="comment-display-control" <?php $this->link(); ?> multiple="multiple" style="height: 100%;">
					<?php
					foreach ( $this->choices as $value => $label ) {
						$selected = ( in_array( $value, $this->value() ) ) ? selected( 1, 1, false ) : '';
						echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
					}
					?>
				</select>
			</label>
		<?php }
	}

	// create ad controls
	class author_description_color_control extends WP_Customize_Control {

		public function render_content() {
			$link = 'https://www.competethemes.com/author-pro/';
			echo "<p>" . sprintf( __('Activate the <a target="_blank" href="%s">Author Pro Plugin</a> to change your colors.', 'author'), $link ) . "</p>";
		}
	}
	class author_description_header_image_control extends WP_Customize_Control {

		public function render_content() {
			$link = 'https://www.competethemes.com/author-pro/';
			echo "<p>" . sprintf( __('Activate the <a target="_blank" href="%s">Author Pro Plugin</a> for advanced header image functionality.', 'author'), $link ) . "</p>";
		}
	}
	class author_description_background_control extends WP_Customize_Control {

		public function render_content() {
			$link = 'https://www.competethemes.com/author-pro/';
			echo "<p>" . sprintf( __('Activate the <a target="_blank" href="%s">Author Pro Plugin</a> for advanced background image and texture functionality.', 'author'), $link ) . "</p>";
		}
	}
	class author_description_font_control extends WP_Customize_Control {

		public function render_content() {
			$link = 'https://www.competethemes.com/author-pro/';
			echo "<p>" . sprintf( __('Activate the <a target="_blank" href="%s">Author Pro Plugin</a> to change your font.', 'author'), $link ) . "</p>";
		}
	}
	class author_description_display_control_control extends WP_Customize_Control {

		public function render_content() {
			$link = 'https://www.competethemes.com/author-pro/';
			echo "<p>" . sprintf( __('Activate the <a target="_blank" href="%s">Author Pro Plugin</a> to get hide/show controls.', 'author'), $link ) . "</p>";
		}
	}
	class author_description_footer_text_control extends WP_Customize_Control {

		public function render_content() {
			$link = 'https://www.competethemes.com/author-pro/';
			echo "<p>" . sprintf( __('Activate the <a target="_blank" href="%s">Author Pro Plugin</a> to customize the footer text.', 'author'), $link ) . "</p>";
		}
	}

	/***** Avatar *****/

	// section
	$wp_customize->add_section( 'ct_author_avatar', array(
		'title'      => __( 'Avatar', 'author' ),
		'priority'   => 15,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'avatar_method', array(
		'default'           => 'none',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'ct_author_sanitize_avatar_method'
	) );
	// control
	$wp_customize->add_control( 'avatar_method', array(
		'label'    => __( 'Avatar image source', 'author' ),
		'section'  => 'ct_author_avatar',
		'settings' => 'avatar_method',
		'type'     => 'radio',
		'choices'  => array(
			'gravatar'  => __('Gravatar', 'author'),
			'upload'  => __('Upload an image', 'author'),
			'none'  => __('Do not display avatar', 'author')
		)
	) );
	// setting
	$wp_customize->add_setting( 'avatar', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'esc_url_raw'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize, 'avatar', array(
			'label'    => __( 'Upload your avatar', 'author' ),
			'section'  => 'ct_author_avatar',
			'settings' => 'avatar',
		)
	) );

	/***** Logo Upload *****/

	// section
	$wp_customize->add_section( 'ct_author_logo_upload', array(
		'title'       => __( 'Logo', 'author' ),
		'priority'    => 25,
		'capability'  => 'edit_theme_options',
		'description' => __('Use this instead of the avatar if you want a non-rounded logo image.', 'author')
	) );
	// setting
	$wp_customize->add_setting( 'logo_upload', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'esc_url_raw'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize, 'logo_image', array(
			'label'    => __( 'Upload custom logo.', 'author' ),
			'section'  => 'ct_author_logo_upload',
			'settings' => 'logo_upload',
		)
	) );

	/***** Social Media Icons *****/

	// get the social sites array
	$social_sites = ct_author_social_array();

	// set a priority used to order the social sites
	$priority = 5;

	// section
	$wp_customize->add_section( 'ct_author_social_media_icons', array(
		'title'          => __('Social Media Icons', 'author'),
		'priority'       => 35,
	) );

	// create a setting and control for each social site
	foreach( $social_sites as $social_site => $value ) {
		// if email icon
		if( $social_site == 'email' ) {
			// setting
			$wp_customize->add_setting( "$social_site", array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'ct_author_sanitize_email'
			) );
			// control
			$wp_customize->add_control( $social_site, array(
				'label'   => $social_site . ' ' . __('address:', 'author' ),
				'section' => 'ct_author_social_media_icons',
				'priority'=> $priority,
			) );
		} else {
			// setting
			$wp_customize->add_setting( $social_site, array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url_raw'
			) );
			// control
			$wp_customize->add_control( new ct_author_url_input_control(
				$wp_customize, $social_site, array(
					'label'   => $social_site . ' ' . __('url:', 'author' ),
					'section' => 'ct_author_social_media_icons',
					'priority'=> $priority,
				)
			) );
		}
		// increment the priority for next site
		$priority = $priority + 5;
	}

	/***** Blog *****/

	// section
	$wp_customize->add_section( 'author_blog', array(
		'title'      => __( 'Blog', 'author' ),
		'priority'   => 45,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'full_post', array(
		'default'           => 'no',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'ct_author_sanitize_yes_no_settings',
	) );
	// control
	$wp_customize->add_control( 'full_post', array(
		'label'          => __( 'Show full posts on blog?', 'author' ),
		'section'        => 'author_blog',
		'settings'       => 'full_post',
		'type'           => 'radio',
		'choices'        => array(
			'yes'   => __('Yes', 'author'),
			'no'  => __('No', 'author'),
		)
	) );
	// setting
	$wp_customize->add_setting( 'excerpt_length', array(
		'default'           => '25',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new ct_author_number_input_control(
		$wp_customize, 'excerpt_length', array(
			'label'          => __( 'Excerpt length', 'author' ),
			'section'        => 'author_blog',
			'settings'       => 'excerpt_length',
			'type'           => 'number',
		)
	) );

	/***** Comment Display *****/

	// section
	$wp_customize->add_section( 'ct_author_comments_display', array(
		'title'      => __( 'Comment Display', 'author' ),
		'priority'   => 55,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'comments_display', array(
		'default'           => array('post','page','attachment','none'),
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'ct_author_sanitize_comments_setting',
	) );
	// control
	$wp_customize->add_control( new ct_author_multi_checkbox_control(
		$wp_customize, 'comments_display', array(
			'label'          => __( 'Show comments on:', 'author' ),
			'section'        => 'ct_author_comments_display',
			'settings'       => 'comments_display',
			'type'           => 'multi-checkbox',
			'choices'        => array(
				'post'   => __('Posts', 'author'),
				'page'  => __('Pages', 'author'),
				'attachment'  => __('Attachments', 'author'),
				'none'  => __('Do not show', 'author')
			)
		)
	) );

	/***** Custom CSS *****/

	// section
	$wp_customize->add_section( 'author_custom_css', array(
		'title'      => __( 'Custom CSS', 'author' ),
		'priority'   => 65,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'custom_css', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	) );
	// control
	$wp_customize->add_control( new ct_author_textarea_control(
		$wp_customize, 'custom_css', array(
			'label'          => __( 'Add Custom CSS Here:', 'author' ),
			'section'        => 'author_custom_css',
			'settings'       => 'custom_css',
		)
	) );


	/*
	 * PRO only sections
	 */

	/***** Header Image *****/

	// section
	$wp_customize->add_section( 'author_header_image', array(
		'title'      => __( 'Header Image', 'author' ),
		'priority'   => 35,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'header_image_ad', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new author_description_header_image_control(
		$wp_customize, 'header_image_ad', array(
			'section'        => 'author_header_image',
			'settings'       => 'header_image_ad'
		)
	) );

	/***** Colors *****/

	// section
	$wp_customize->add_section( 'author_colors', array(
		'title'      => __( 'Colors', 'author' ),
		'priority'   => 50,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'colors_ad', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new author_description_color_control(
		$wp_customize, 'colors_ad', array(
			'section'        => 'author_colors',
			'settings'       => 'colors_ad'
		)
	) );

	/***** Background *****/

	// section
	$wp_customize->add_section( 'author_background', array(
		'title'      => __( 'Background', 'author' ),
		'priority'   => 55,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'background_ad', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new author_description_background_control(
		$wp_customize, 'background_ad', array(
			'section'        => 'author_background',
			'settings'       => 'background_ad'
		)
	) );

	/***** Fonts *****/

	// section
	$wp_customize->add_section( 'author_font', array(
		'title'      => __( 'Font', 'author' ),
		'priority'   => 40,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'font_ad', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new author_description_font_control(
		$wp_customize, 'font_ad', array(
			'section'        => 'author_font',
			'settings'       => 'font_ad'
		)
	) );

	/***** Display Control *****/

	// section
	$wp_customize->add_section( 'author_display_control', array(
		'title'      => __( 'Display Controls', 'author' ),
		'priority'   => 70,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'display_control_ad', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new author_description_display_control_control(
		$wp_customize, 'display_control_ad', array(
			'section'        => 'author_display_control',
			'settings'       => 'display_control_ad'
		)
	) );

	/***** Footer Text *****/

	// section
	$wp_customize->add_section( 'author_footer_text', array(
		'title'      => __( 'Footer Text', 'author' ),
		'priority'   => 85,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'footer_text_ad', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new author_description_footer_text_control(
		$wp_customize, 'footer_text_ad', array(
			'section'        => 'author_footer_text',
			'settings'       => 'footer_text_ad'
		)
	) );
}

/***** Custom Sanitization Functions *****/

/*
 * Sanitize settings with show/hide as options
 * Used in: search bar
 */
function ct_author_sanitize_all_show_hide_settings($input){
	// create array of valid values
	$valid = array(
		'show' => __('Show', 'author'),
		'hide' => __('Hide', 'author')
	);
	// if returned data is in array use it, else return nothing
	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

/*
 * sanitize email address
 * Used in: Social Media Icons
 */
function ct_author_sanitize_email( $input ) {

	return sanitize_email( $input );
}

// sanitize comment display multi-check
function ct_author_sanitize_comments_setting($input){

	// valid data
	$valid = array(
		'post'   => __('Posts', 'author'),
		'page'  => __('Pages', 'author'),
		'attachment'  => __('Attachments', 'author'),
		'none'  => __('Do not show', 'author')
	);

	// loop through array
	foreach( $input as $selection ) {

		// if it's in the valid data, return it
		if ( array_key_exists( $selection, $valid ) ) {
			return $input;
		} else {
			return '';
		}
	}
}

function ct_author_sanitize_avatar_method($input) {

	// valid data
	$valid = array(
		'gravatar'  => __('Gravatar', 'author'),
		'upload'  => __('Upload an image', 'author'),
		'none'  => __('Do not display avatar', 'author')
	);

	// if returned data is in array use it, else return nothing
	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

// sanitize yes/no settings
function ct_author_sanitize_yes_no_settings($input){

	$valid = array(
		'yes'   => __('Yes', 'author'),
		'no'  => __('No', 'author'),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

function ct_author_customize_preview_js() {

	$content = "<script>jQuery('#customize-info').prepend('<div class=\"upgrades-ad\"><a href=\"https://www.competethemes.com/author-pro/\" target=\"_blank\">View the Author Pro Plugin <span>&rarr;</span></a></div>')</script>";
	echo apply_filters('ct_author_customizer_ad', $content);
}
add_action('customize_controls_print_footer_scripts', 'ct_author_customize_preview_js');