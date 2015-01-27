<div class="post-author">
	<?php ct_unlimited_profile_image_output(); ?>
	<h3><?php echo get_the_author(); ?></h3>
	<?php ct_unlimited_social_icons_output('author') ?>
	<p><?php the_author_meta('description'); ?></p>
	<a href="<?php echo get_author_posts_url( get_the_author_meta('ID') ); ?>">View more posts</a>
</div>