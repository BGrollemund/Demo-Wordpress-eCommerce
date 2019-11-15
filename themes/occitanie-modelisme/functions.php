<?php

function modelisme_theme_name_script()
{
    wp_register_style('font_style', 'https://fonts.googleapis.com/css?family=Open+Sans:400,700, array(), true)', array(), true);
    wp_enqueue_style('font_style');
    wp_register_style('main_style', get_template_directory_uri().'/style.css', array(), true);
    wp_enqueue_style('main_style');
    wp_register_style('icon_style', get_template_directory_uri().'/css/fontawesome/css/all.css', array(), true);
    wp_enqueue_style('icon_style');
    wp_register_style('blog_style', get_template_directory_uri().'/css/blog.css', array(), true);
    wp_enqueue_style('blog_style');
}

add_action('wp_enqueue_scripts', 'modelisme_theme_name_script');


function register_menu()
{
    register_nav_menus(
        array(
            'main-menu' => __('Menu principal')
        )
    );

    register_nav_menus(
        array(
            'footer-menu' => __('Menu pied de page')
        )
    );
}

add_action('init', 'register_menu');

class modelisme_walker extends Walker_Nav_Menu
{
    function start_el(&$output, $item, $depth=0, $args=array(), $id = 0) {

        $title = $item->title;
        $permalink = $item->url;

        $output .= '<li class="nav-item">';
        $output .= '<a href="' . $permalink . '">';
        $output .= $title;
        $output .= '</a>';
    }
}


function modelisme_widgets_init()
{
    if ( function_exists('register_sidebar') ) {

        register_sidebar(array(
            'name' => __('Top Main', 'main widget area'),
            'description' => __('Widget en haut de page', 'main widget area'),
            'id' => 'top-main',
            'before_widget' => '<div class="main-icon">'.
                '<span class="fa-stack fa-2x"><i class="fas fa-circle fa-stack-2x"></i><i class="fas fa-flag-checkered fa-stack-1x fa-inverse"></i>'.
                '</span></div><div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="title-widget">',
            'after_title' => '</h4>',
        ));

        register_sidebar(array(
            'name' => __('Main', 'main widget area 2'),
            'description' => __('Widget en haut de page 2', 'main widget area 2'),
            'id' => 'top-main-2',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="title-widget">',
            'after_title' => '</h4>',
        ));

        register_sidebar(array(
            'name' => __('Boutique', 'shop'),
            'description' => __('Boutique', 'shop'),
            'id' => 'shop',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="title-widget">',
            'after_title' => '</h4>',
        ));

    }
}

add_action( 'widgets_init', 'modelisme_widgets_init' );

if ( ! function_exists( 'modelisme_setup' ) ) {
    function modelisme_setup() {
        global $content_width;
        if ( ! isset( $content_width ) ) {
            $content_width = 1250;
        }

        add_theme_support( 'automatic-feed-links' );

        add_theme_support( 'post-thumbnails' );

        $args = array(
            'default-image'      => get_template_directory_uri() . 'img/default-image.jpg',
            'default-text-color' => '000',
            'width'              => '100%',
            'height'             => 250,
            'flex-width'         => true,
            'flex-height'        => true,
        );
        add_theme_support( 'custom-header', $args );
    }
}

add_action( 'after_setup_theme', 'modelisme_setup' );