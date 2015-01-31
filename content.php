<div <?php post_class(); ?>>
	<?php ct_author_featured_image(); ?>
	<article>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
			<span>Published by <?php the_author_posts_link(); ?> on <?php echo date_i18n( get_option( 'date_format' ), strtotime( get_the_date( 'n/j/Y' ) ) ); ?></span>
		</div>
	    <div class="post-content">
	        <?php the_content(); ?>
	        <?php wp_link_pages(array('before' => '<p class="singular-pagination">' . __('Pages:','author'), 'after' => '</p>', ) ); ?>
	    </div>
		<?php get_template_part('content/post-categories'); ?>
		<?php get_template_part('content/post-tags'); ?>
	</article>
	<?php get_template_part('content/post-nav'); ?>
	<?php comments_template(); ?>
</div>