<div <?php post_class(); ?>>
	<?php do_action( 'post_before' ); ?>
	<?php ct_author_featured_image(); ?>
	<article>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
			<?php get_template_part( 'content/post-meta' ); ?>
		</div>
		<div class="post-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array(
				'before' => '<p class="singular-pagination">' . esc_html__( 'Pages:', 'author' ),
				'after'  => '</p>',
			) ); ?>
		</div>
		<?php get_template_part( 'content/post-categories' ); ?>
		<?php get_template_part( 'content/post-tags' ); ?>
	</article>
	<?php do_action( 'post_after' ); ?>
	<?php get_template_part( 'content/post-nav' ); ?>
	<?php comments_template(); ?>
</div>