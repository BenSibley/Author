<?php

/*
 * Functions added here to prevent breaking child themes and older versions of Author Pro
 */

// Simplified version that simply calls do_action instead
if ( !function_exists('hybrid_do_atomic') ) {
	function hybrid_do_atomic($hook) {
		do_action($hook);
	}
}