<?php
/* Category header */
if( is_category() ){ ?>
	<div class='archive-header'>
		<i class="fa fa-folder-open"></i>
		<h2>
			<?php _e('Category archive for:', 'author'); ?>
			<?php single_cat_title(); ?>
		</h2>
		<?php if ( category_description() ) echo category_description(); ?>
	</div>
<?php
}
/* Tag header */
elseif( is_tag() ){ ?>
	<div class='archive-header'>
		<i class="fa fa-tag"></i>
		<h2>
			<?php _e('Tag archive for:', 'author'); ?>
			<?php single_tag_title(); ?>
		</h2>
		<?php if ( tag_description() ) echo tag_description(); ?>
	</div>
<?php
}
/* Author header */
elseif( is_author() ){ ?>
	<div class='archive-header'>
		<i class="fa fa-user"></i>
		<h2>
			<?php _e('Author archive for:', 'author'); ?>
			<?php the_author_meta( 'display_name' ); ?>
		</h2>
		<?php if ( get_the_author_meta( 'description' ) ) echo '<p>' . get_the_author_meta( 'description' ) . '</p>'; ?>
	</div>
<?php
}
/* Date header */
elseif( is_date() ){ ?>
	<div class='archive-header'>
		<i class="fa fa-calendar"></i>
		<h2>
			<?php _e('Date archive for:', 'author'); ?>
			<?php single_month_title(' '); ?>
		</h2>
	</div>
<?php
}