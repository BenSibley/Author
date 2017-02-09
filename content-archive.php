<div <?php post_class(); ?>>
	<?php do_action( 'archive_post_before' ); ?>
	<?php ct_author_featured_image(); ?>
	<article>
		<div class='post-header'>
			<h2 class='post-title'>
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
			</h2>
			<?php get_template_part( 'content/post-meta' ); ?>
		</div>
		<div class="post-content">
			<?php ct_author_excerpt(); ?>
			<?php get_template_part( 'content/comments-link' ); ?>
		</div>
	</article>
	<?php do_action( 'archive_post_after' ); ?>
</div>