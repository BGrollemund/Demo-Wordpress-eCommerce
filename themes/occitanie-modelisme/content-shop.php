<div class="container-page">

    <hr>
    <div class="container container-shop">
        <?php if ( is_active_sidebar( 'shop' ) ) : ?>

            <?php dynamic_sidebar( 'shop' ); ?>

        <?php endif; ?>
    </div>
    <hr>

    <div>
        <?php the_content(); ?>
    </div>

</div>