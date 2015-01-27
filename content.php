<div <?php post_class(); ?>>
	<article>
	    <?php ct_unlimited_featured_image(); ?>
	    <div class="post-meta">
	        <?php get_template_part('content/post-meta'); ?>
	    </div>
	    <div class='post-header'>
	        <h1 class='post-title'><?php the_title(); ?></h1>
			<?php get_template_part('content/post-categories'); ?>
	    </div>
	    <div class="post-content">
	        <?php the_content(); ?>
	        <?php wp_link_pages(array('before' => '<p class="singular-pagination">' . __('Pages:','unlimited'), 'after' => '</p>', ) ); ?>
	    </div>
		<?php get_template_part('content/post-nav'); ?>
		<?php get_template_part('content/post-author'); ?>
	    <?php get_template_part('content/post-tags'); ?>
	</article>
	<?php comments_template(); ?>
</div>