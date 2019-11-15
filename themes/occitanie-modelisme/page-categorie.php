<?php get_header(); ?>

    <div>
        <?php
        if( have_posts() ) : while ( have_posts() ) : the_post();
            get_template_part('content', 'shop', get_post_format());
        endwhile; endif;
        ?>
    </div>

<?php get_footer(); ?>