</main> <!-- .main -->

<footer class="site-footer" role="contentinfo">
    <div class="design-credit">
        <span>
            <?php
                $site_url = 'https://www.competethemes.com/author/';
                $footer_text = sprintf( __( '<a target="_blank" href="%s">Author WordPress Theme</a> by Compete Themes', 'author' ), esc_url( $site_url ) );
                echo $footer_text;
            ?>
        </span>
    </div>
</footer>

</div><!-- .overflow-container -->

<?php wp_footer(); ?>
</body>
</html>