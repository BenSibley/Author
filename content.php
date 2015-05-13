<div <?php post_class(); ?>>
	<?php hybrid_do_atomic( 'post_before' ); ?>
	<?php ct_author_featured_image(); ?>
	<article>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
			<span class="post-meta">
				<?php
				$author = "<a href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "'>" . get_the_author() . "</a>";
				$date = "<a href='" . get_month_link( get_the_date('Y'), get_the_date('n') ) . "'>" . date_i18n( get_option( 'date_format' ), strtotime( get_the_date('r') ) ) . "</a>";
				?>
				<?php printf( _x('Published by %1$s on %2$s', 'This blog post was published by some author on some date', 'author'), $author, $date ); ?>
			</span>
		</div>
	    <div class="post-content">
	        <?php the_content(); ?>
	        <?php wp_link_pages(array('before' => '<p class="singular-pagination">' . __('Pages:','author'), 'after' => '</p>', ) ); ?>
	    </div>
		<?php get_template_part('content/post-categories'); ?>
		<?php get_template_part('content/post-tags'); ?>
	</article>
	<?php hybrid_do_atomic( 'post_after' ); ?>
	<?php get_template_part('content/post-nav'); ?>
	<?php comments_template(); ?>
</div>