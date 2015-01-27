<?php

// add profile image option for contributors roles and higher
function ct_unlimited_user_profile_image_setting( $user ) {

	// get user ID
	$user_id = get_current_user_id();

	// abort if user not contributor or higher
	if ( ! current_user_can( 'edit_posts', $user_id ) ) return false;
	?>
	<table id="profile-image-table" class="form-table">
		<tr>
			<th><label for="unlimited_user_profile_image"><?php _e( 'Profile image', 'unlimited' ); ?></label></th>
			<td>
				<!-- Outputs the image after save -->
				<img id="image-preview" src="<?php echo esc_url( get_the_author_meta( 'unlimited_user_profile_image', $user->ID ) ); ?>" style="width:100px;"><br />
				<!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
				<input type="text" name="unlimited_user_profile_image" id="unlimited_user_profile_image" value="<?php echo esc_url( get_the_author_meta( 'unlimited_user_profile_image', $user->ID ) ); ?>" class="regular-text" />
				<!-- Outputs the save button -->
				<input type='button' id="unlimited-user-profile-upload" class="button-primary" value="<?php _e( 'Upload Image', 'unlimited' ); ?>"/><br />
				<span class="description"><?php _e( 'Upload an image here to use instead of your Gravatar.', 'unlimited' ); ?></span>
			</td>
		</tr>
	</table><!-- end form-table -->
<?php } // additional_user_fields
add_action( 'show_user_profile', 'ct_unlimited_user_profile_image_setting' );
add_action( 'edit_user_profile', 'ct_unlimited_user_profile_image_setting' );

/**
 * Saves additional user fields to the database
 */
function ct_unlimited_save_user_profile_image( $user_id ) {

	// only saves if the current user can edit current user profile
	if ( ! current_user_can( 'edit_user', $user_id ) ) return false;

	update_user_meta( $user_id, 'unlimited_user_profile_image', esc_url_raw( $_POST['unlimited_user_profile_image'] ) );
}

add_action( 'personal_options_update', 'ct_unlimited_save_user_profile_image' );
add_action( 'edit_user_profile_update', 'ct_unlimited_save_user_profile_image' );

// add the social profile boxes to the user screen.
function ct_unlimited_add_social_profile_settings($user) {

	// get social sites
	$social_sites = ct_unlimited_social_array();

	// get current user ID
	$user_id = get_current_user_id();

	// only added for contributors and above
	if ( ! current_user_can( 'edit_posts', $user_id ) ) return false;

	?>
	<table class="form-table">
		<tr>
			<th><h3><?php _e('Social Profiles', 'unlimited'); ?></h3></th>
		</tr>
		<?php
		foreach($social_sites as $key => $social_site) { ?>
			<tr>
				<th>
					<?php if( $key == 'email' ) : ?>
						<label for="<?php echo $key; ?>-profile"><?php echo ucfirst($key); ?> <?php _e('Address:', 'unlimited'); ?></label>
					<?php else : ?>
						<label for="<?php echo $key; ?>-profile"><?php echo ucfirst($key); ?> <?php _e('Profile:', 'unlimited'); ?></label>
					<?php endif; ?>
				</th>
				<td>
					<?php if( $key == 'email' ) : ?>
						<input type='text' id='<?php echo $key; ?>-profile' class='regular-text' name='<?php echo $key; ?>-profile' value='<?php echo is_email(get_the_author_meta($social_site, $user->ID )); ?>' />
					<?php else : ?>
						<input type='url' id='<?php echo $key; ?>-profile' class='regular-text' name='<?php echo $key; ?>-profile' value='<?php echo esc_url(get_the_author_meta($social_site, $user->ID )); ?>' />
					<?php endif; ?>
				</td>
			</tr>
		<?php }	?>
	</table>
<?php
}

add_action( 'show_user_profile', 'ct_unlimited_add_social_profile_settings' );
add_action( 'edit_user_profile', 'ct_unlimited_add_social_profile_settings' );

function ct_unlimited_save_social_profiles($user_id) {

	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

	$social_sites = ct_unlimited_social_array();

	foreach ($social_sites as $key => $social_site) {
		if( $key == 'email' ) {
			// if email, only accept 'mailto' protocol
			if( isset( $_POST["$key-profile"] ) ){
				update_user_meta( $user_id, $social_site, sanitize_email( $_POST["$key-profile"] ) );
			}
		} else {
			if( isset( $_POST["$key-profile"] ) ){
				update_user_meta( $user_id, $social_site, esc_url_raw( $_POST["$key-profile"] ) );
			}
		}
	}
}

add_action( 'personal_options_update', 'ct_unlimited_save_social_profiles' );
add_action( 'edit_user_profile_update', 'ct_unlimited_save_social_profiles' );