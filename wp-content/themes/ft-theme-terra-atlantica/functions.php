<?php

function load_scripts()
{
	wp_enqueue_style ( 'bootstrap-min', get_template_directory_uri()  . '/assets/css/bootstrap.min.css', array (), '5.1.3', 'all' );
	#wp_enqueue_style ( 'bootstrap-datepicker', get_template_directory_uri()  . '/assets/css/bootstrap-datepicker.standalone.min.css', array (), '1.6.4', 'all' );
	wp_enqueue_style('style-css', get_template_directory_uri()  . '/assets/css/style.css', array(), '1.0.0', 'all');
	wp_enqueue_style( 'Montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap', false );
	wp_enqueue_script('popper', get_template_directory_uri()  . '/assets/js/popper.min.js', array(), '1.14.3', true);
	wp_enqueue_script('bootstrap-js', get_template_directory_uri()  . '/assets/js/bootstrap.min.js', array(), '5.1.3', true);
	wp_enqueue_script('script-js', get_template_directory_uri()  . '/assets/js/scripts.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'load_scripts');

function theme_config()
{
    register_nav_menus(
        array(
            'institucional' => __('Institucional', 'Terra Atlantica'),
            'cadastro' => __('Cadastro', 'Terra Atlantica'),
            'atendimento' => __('Atendimento', 'Terra Atlantica'),
        )
    );
    add_theme_support('custom-logo');
    add_theme_support('custom-header');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');

}
add_action('after_setup_theme', 'theme_config', 0);

function config($wp_customize){
	$wp_customize -> add_section('footer', array(
		'title' => __('Rodapé', 'Parque Terra Atlântica'),
		'priority' => 125
	));

	$wp_customize -> add_setting('footer_logo', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'footer_logo', array(
		'label' => __( 'Logo', 'Parque Terra Atlântica' ),
		'section' => 'footer',
		'mime_type' => 'image',
	)));

	$wp_customize -> add_setting('link_instagram');
	$wp_customize->add_control( 'link_instagram', array(
		'label' => __( 'Link instagram', 'Parque Terra Atlântica' ),
		'section' => 'footer',
	));

	$wp_customize -> add_setting('link_facebook');
	$wp_customize->add_control( 'link_facebook', array(
		'label' => __( 'Link facebook', 'Parque Terra Atlântica' ),
		'section' => 'footer',
	));
}
add_action( 'customize_register', 'config');

register_sidebar(
	array(
		'name' => 'Central de atendimento',
		'id' => 'central_atendimento',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<span class="d-none">',
		'after_title' => '</span>'
	)
);

register_sidebar(
	array(
		'name' => 'O Parque',
		'id' => 'o_parque',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<span class="d-none">',
		'after_title' => '</span>'
	)
);


function create_post_type(){

	register_post_type('slides',
		array(
			'labels' => array(
                'name' => __('Slides'),
                'singular_name' => __('Slide'),
            ),
            'supports' => array(
                'title','thumbnail'
            ),
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-images-alt2',
            'rewrite' => array(
				'slug' => 'slides'),
		)
	);

	register_post_type('atracoes',
		array(
			'labels' => array(
                'name' => __('Atrações'),
                'singular_name' => __('Atração'),
            ),
            'supports' => array(
                'title', 'thumbnail', 'excerpt'
            ),
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-games',
            'rewrite' => array(
				'slug' => 'atracoes'),
		)
	);
}
add_action( 'init', 'create_post_type');