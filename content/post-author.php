<div class="post-author">
	<?php ct_unlimited_profile_image_output(); ?>
	<h3><?php echo get_the_author(); ?></h3>
	<ul class="social-icons"></ul>
	<p><?php the_author_meta('description'); ?></p>
	<a href="<?php echo get_author_posts_url( get_the_author_meta('ID') ); ?>">View more posts</a>
</div>