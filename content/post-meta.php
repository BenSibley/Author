<span class="post-meta">
	<?php
    $show_author = get_theme_mod('display_post_author');
    $show_date = get_theme_mod('display_post_date');
    $author = "<span class='author'><a href='" . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . "'>" . esc_html(get_the_author()) . "</a></span>";
    $date   = "<span class='date'><a href='" . esc_url(get_month_link(get_the_date('Y'), get_the_date('n'))) . "'>" . get_the_date() . "</a></span>";

    if ($show_author != 'no' && $show_date != 'no') {
        printf(_x('Published by %1$s on %2$s', 'This blog post was published by some author on some date', 'author'), $author, $date);
    } elseif ($show_author != 'no') {
        printf(_x('Published by %1$s', 'This blog post was published by X author', 'author'), $author);
    } elseif ($show_date != 'no') {
        printf(_x('Published on %1$s', 'This blog post was published on X date', 'author'), $date);
    }
    ?>
</span>