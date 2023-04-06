<?php

require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

//Scripts e folhas de estilos
function load_scripts()
{
	$vs = "6.0.0";
	wp_enqueue_style('fonts-css', 'https://fonts.google.com/specimen/Kumbh+Sans?query=Kumbh&sidebar.open=true&selection.family=Kumbh+Sans:wght@300;700', array(), '4.0.0', 'all');
	wp_enqueue_style('fonts-awesome', 'https://kit.fontawesome.com/5ab42857c0.js', array(), '4.0.0', true);
	wp_enqueue_style('style-css', get_template_directory_uri()  . '/assets/css/style.css', array(), $vs, 'all');
}
add_action('wp_enqueue_scripts', 'load_scripts');

//Header and Footer routes
function theme_config()
{
	register_nav_menus(
		array(
			'topo' => __('Topo', 'Frameticket'),
			'institucional' => __('Institucional', 'Frameticket'),
			'cadastro' => __('Cadastro', 'Frameticket'),
			'atendimento' => __('Atendimento', 'Frameticket'),
		)
	);


	add_theme_support('custom-logo');
	add_theme_support('post-thumbnails');
	add_theme_support('title-tag');
}
add_action('after_setup_theme', 'theme_config', 0);



function config($wp_customize)
{
	$wp_customize->add_section('footer', array(
		'title' => __('Rodapé', 'Frameticket'),
		'priority' => 125
	));

	$wp_customize->add_setting('footer_logo', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'footer_logo', array(
		'label' => __('Logo', 'Frameticket'),
		'section' => 'footer',
		'mime_type' => 'image',
	)));


	$wp_customize->add_setting('footer_background_color', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_background_color', array(
		'label' => __('Background do rodapé', 'Frameticket'),
		'section' => 'footer',
	)));

	$wp_customize->add_setting('footer_font_color', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_font_color', array(
		'label' => __('Fonte do rodapé', 'Frameticket'),
		'section' => 'footer',
	)));

	$wp_customize->add_setting('footer_link_color', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_link_color', array(
		'label' => __('Links do rodapé', 'Frameticket'),
		'section' => 'footer',
	)));

	$wp_customize->add_setting('footer_link_hover_color', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_link_hover_color', array(
		'label' => __('Link do rodapé (hover)', 'Frameticket'),
		'section' => 'footer',
	)));

	$wp_customize->add_section('header', array(
		'title' => __('Cabeçalho', 'Frameticket'),
		'priority' => 20
	));

	$wp_customize->add_setting('header_background_color', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_background_color', array(
		'label' => __('Background do cabeçalho', 'Frameticket'),
		'section' => 'header',
	)));

	$wp_customize->add_setting('header_with_search', array(
		'transport' => 'refresh',
		'default' => 'S',
	));

	$wp_customize->add_control('header_with_search', array(
		'type' => 'radio',
		'section' => 'header', // Add a default or your own section
		'label' => __('Incluir formulário de busca e categorias:', 'Frameticket'),
		'description' => __(''),
		'choices' => array(
			'S' => __('Sim'),
			'N' => __('Não'),
		),
	));

	$wp_customize->add_setting('button_new_account', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_new_account', array(
		'label' => __('Botão cadastre-se', 'Frameticket'),
		'section' => 'header',
	)));

	$wp_customize->add_setting('button_new_account_text', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_new_account_text', array(
		'label' => __('Fonte botão cadastre-se (hover)', 'Frameticket'),
		'section' => 'header',
	)));

	$wp_customize->add_setting('button_cart', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_cart', array(
		'label' => __('Botão carrinho', 'Frameticket'),
		'section' => 'header',
	)));

	$wp_customize->add_setting('button_cart_text', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_cart_text', array(
		'label' => __('Texto botão carrinho', 'Frameticket'),
		'section' => 'header',
	)));

	$wp_customize->add_setting('button_cart_hover', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_cart_hover', array(
		'label' => __('Botão carrinho (hover)', 'Frameticket'),
		'section' => 'header',
	)));

	$wp_customize->add_setting('button_cart_text_hover', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_cart_text_hover', array(
		'label' => __('Texto botão carrinho (hover)', 'Frameticket'),
		'section' => 'header',
	)));

	$wp_customize->add_setting('button_my_account', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_my_account', array(
		'label' => __('Botão minha conta', 'Frameticket'),
		'section' => 'header',
	)));

	$wp_customize->add_setting('button_my_account_text', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_my_account_text', array(
		'label' => __('Texto botão minha conta', 'Frameticket'),
		'section' => 'header',
	)));

	$wp_customize->add_setting('button_my_account_hover', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_my_account_hover', array(
		'label' => __('Botão minha conta (hover)', 'Frameticket'),
		'section' => 'header',
	)));

	$wp_customize->add_setting('button_my_account_text_hover', array(
		'transport' => 'refresh'
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_my_account_text_hover', array(
		'label' => __('Texto botão minha conta (hover)', 'Frameticket'),
		'section' => 'header',
	)));
}
add_action('customize_register', 'config');

//Banners
function create_post_type()
{
	register_post_type(
		'Banners',
		array(
			'labels' => array(
				'name' => __('Banners'),
				'singular_name' => __('Banners'),
			),
			'supports' => array(
				'title', 'thumbnail'
			),
			'public' => true,
			'has_archive' => true,
			'menu_icon' => 'dashicons-images-alt2',
			'rewrite' => array('slug' => 'banners'),
		)
	);
}
add_action('init', 'create_post_type');

register_sidebar(
	array(
		'name' => 'Funcionamento',
		'id' => 'funcionamento',
		'before_widget' => '<div>',
		'after_widget' => '</div>',
		'before_title' => '<h6>',
		'after_title' => '</h6>'
	)
);

register_sidebar(
	array(
		'name' => 'Navbar Topo',
		'id' => 'navbar-topo',
		'before_widget' => '<div class="navbar-topo">',
		'after_widget' => '</div>',
		'before_title' => '<h6>',
		'after_title' => '</h6>'
	)
);

register_sidebar(
	array(
		'name' => 'Navbar Base do Topo',
		'id' => 'navbar-basetopo',
		'before_widget' => '<div class="navbar-basetopo">',
		'after_widget' => '</div>',
		'before_title' => '<h6>',
		'after_title' => '</h6>'
	)
);

register_sidebar(
	array(
		'name' => 'Barra Lateral',
		'id' => 'sidebar-lateral',
		'before_widget' => '<div class="sidebar-lateral">',
		'after_widget' => '</div>',
		'before_title' => '<h6>',
		'after_title' => '</h6>'
	)
);

register_sidebar(
	array(
		'name' => 'Rodapé 1',
		'id' => 'footer-1',
		'before_widget' => '<div class="sb-footer sb-footer-1">',
		'after_widget' => '</div>',
		'before_title' => '<h6>',
		'after_title' => '</h6>'
	)
);

register_sidebar(
	array(
		'name' => 'Rodapé 2',
		'id' => 'footer-2',
		'before_widget' => '<div class="sb-footer sb-footer-2">',
		'after_widget' => '</div>',
		'before_title' => '<h6>',
		'after_title' => '</h6>'
	)
);

register_sidebar(
	array(
		'name' => 'Rodapé 3',
		'id' => 'footer-3',
		'before_widget' => '<div class="sb-footer sb-footer-3">',
		'after_widget' => '</div>',
		'before_title' => '<h6>',
		'after_title' => '</h6>'
	)
);
register_sidebar(
	array(
		'name' => 'Rodapé 4',
		'id' => 'footer-4',
		'before_widget' => '<div class="sb-footer sb-footer-4">',
		'after_widget' => '</div>',
		'before_title' => '<h6>',
		'after_title' => '</h6>'
	)
);
