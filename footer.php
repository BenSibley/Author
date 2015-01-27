</section> <!-- .main -->

<?php get_sidebar( 'primary' ); ?>

<footer class="site-footer" role="contentinfo">
    <h4><a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo('title'); ?></a> <?php bloginfo('description'); ?></h4>
    <div class="design-credit">
        <span>
            <?php
                $site_url = 'https://www.competethemes.com/author/';
                $footer_text = sprintf( __( '<a target="_blank" href="%s">Author WordPress Theme</a> by Compete Themes.', 'author' ), esc_url( $site_url ) );
                echo $footer_text;
            ?>
        </span>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>