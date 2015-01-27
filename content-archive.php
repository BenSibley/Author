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
			<?php ct_unlimited_excerpt(); ?>
		</div>
	</article>
</div>