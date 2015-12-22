<?php

/*
 * Functions added here to prevent breaking child themes and older versions of Author Pro
 */

// Simplified version that simply calls do_action instead
if ( ! function_exists( 'hybrid_do_atomic' ) ) {
	function hybrid_do_atomic( $hook ) {
		do_action( $hook );
	}
}

if ( ! function_exists( 'hybrid_register_sidebar' ) ) {

	function hybrid_register_sidebar( $args ) {

		/* Set up some default sidebar arguments. */
		$defaults = array(
			'id'            => '',
			'name'          => '',
			'description'   => '',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		);

		/* Allow developers to filter the default sidebar arguments. */
		$defaults = apply_filters( 'hybrid_sidebar_defaults', $defaults );

		/* Parse the arguments. */
		$args = wp_parse_args( $args, $defaults );

		/* Allow developers to filter the sidebar arguments. */
		$args = apply_filters( 'hybrid_sidebar_args', $args );

		/* Register the sidebar. */

		return register_sidebar( $args );
	}
}