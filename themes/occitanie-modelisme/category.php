<?php get_header(); ?>

    <div>

        <div class="container-page">

            <?php
            if( have_posts() ) : while ( have_posts() ) : the_post();
                get_template_part('content', 'category', get_post_format());
            endwhile; endif;

            ?>

        </div>

    </div>

<?php get_footer(); ?>