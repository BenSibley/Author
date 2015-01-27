<div <?php post_class(); ?>>
	<article>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
		</div>
		<div class="post-content">
			<?php the_content(); ?>
		</div>
		<?php get_template_part('content/post-nav-attachment'); ?>
	</article>
	<?php comments_template(); ?>
</div>