<div <?php post_class(); ?>>
	<?php ct_author_featured_image(); ?>
	<article>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
		</div>
		<div class="post-content">
			<?php the_content(); ?>
			<?php wp_link_pages(array('before' => '<p class="singular-pagination">' . __('Pages:','author'), 'after' => '</p>', ) ); ?>
		</div>
	</article>
	<?php comments_template(); ?>
</div>