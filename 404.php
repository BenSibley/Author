<?php get_header(); ?>
	<div class="entry">
		<article>
			<div class='post-header'>
				<h1 class='post-title'><?php _e( '404: Page Not Found', 'author' ); ?></h1>
			</div>
			<div class="post-content">
				<?php _e( 'Looks like nothing was found on this url.  Double-check that the url is correct or try the search form below to find what you were looking for.', 'author' ); ?>
			</div>
			<?php get_search_form(); ?>
		</article>
	</div>
<?php get_footer(); ?>