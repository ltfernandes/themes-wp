<?php

function load_scripts()
{
	wp_enqueue_style ( 'bootstrap-min', get_template_directory_uri()  . '/assets/css/bootstrap.min.css', array (), '5.2.0', 'all' );
	wp_enqueue_style('style-css', get_template_directory_uri()  . '/assets/css/style.css', array(), '1.0.0', 'all');
    wp_enqueue_script('bootstrap-bundle-js', get_template_directory_uri()  . '/assets/js/bootstrap.bundle.min.js', array(), '5.2.0', true);
	wp_enqueue_script('script-js', get_template_directory_uri()  . '/assets/js/scripts.js', array('frameticket-mktplace-scripts'), '1.0.0', false);
}
add_action('wp_enqueue_scripts', 'load_scripts');

function theme_config()
{
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');

}
add_action('after_setup_theme', 'theme_config', 0);

//Criando os Banners
function create_post_type()
{
    register_post_type('Banners',
        array(
            'labels' => array(
                'name' => __('Banners'),
                'singular_name' => __('Banners'),
            ),
            'supports' => array(
                'title', 'thumbnail',
            ),
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-images-alt2',
            'rewrite' => array('slug' => 'banners'),
        )
    );
}

// Cria os menus de rodape e cabeçalho
function create_nav_menu()
{
    register_nav_menu('menu-header','Menu cabeçalho');
    register_nav_menu('menu-header-mobile','Menu cabeçalho mobile');
    register_nav_menu('menu-footer','Menu rodapé');
    register_nav_menu('menu-social','Menu Midias Sociais');
}

function custom_init()
{
    create_post_type();
    create_nav_menu();
}
add_action('init', 'custom_init');
define('DISALLOW_FILE_EDIT', true);