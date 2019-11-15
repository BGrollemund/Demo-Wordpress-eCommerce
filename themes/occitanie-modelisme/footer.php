<footer>
    <div class="nav-footer">
        <nav>
            <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer-menu',
                    'container' => 'div',
                    'container_id' => '',
                    'menu_id' => 'footer-menu',
                    'walker' => new modelisme_walker()
                ));
            ?>
        </nav>

        <div>
            <p class="contact-title">Nous contacter</p>
            <p><span class="txt-bold">Occitanie Modélisme</span><br>15, avenue de la Mer<br>66000 Perpignan</p>
            <p>Téléphone : 06.25.23.21.24</p>
            <p>Email : <a href="mailto:occitanie-modelisme@gmail.com">occitanie-modelisme@gmail.com</a></p>
        </div>

        <div id="logo" class="logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                <img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
            </a>
        </div>
    </div>

    <div class="copyright">
        &copy;2019 - ERN
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>