<?php

if (!is_admin()) {
    if (!session_id()) {
        session_start();
    }
    date_default_timezone_set('America/Sao_Paulo');
}
require_once ABSPATH . '/wp-admin/includes/plugin.php';
require_once(dirname(__FILE__) . '/vendor/autoload.php');
error_reporting(E_ERROR);

/**
 * Plugin Name:       Frameticket Marketplace 5.1
 * Plugin URI:        https://frameticket.com.br/
 * Description:       Plugin de Integração com a plataforma Frameticket.
 * Version:           5.1.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Frameticket
 * Author URI:        https://frameticket.com.br/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ft-plugin
 * Domain Path:       /languages
 */

define('BRUXO_SIGLA', 'FT');
define('BRUXO_NAME', 'ft-plugin');
define('BRUXO_LABEL', 'Frameticket 5.1');
define('BRUXO_URL', plugin_dir_url(__FILE__));
define('BRUXO_DIR', __DIR__);

if (!$_SESSION['tokenPost'] && !count($_POST)) {
    $_SESSION['tokenPost'] = uniqid('fttkp');
}

$mppPluginFile = substr(strrchr(__DIR__, DIRECTORY_SEPARATOR), 1) . DIRECTORY_SEPARATOR . basename(__FILE__);

use PluginFrameticket\Control\Config;
use PluginFrameticket\Middleware\Plugin;

$objPlugin = new Plugin();

if (!is_admin()) {
    $allow = [
        'https://www.google-analytics.com',
        'https://www.gstatic.com',
        'https://www.google.com',
        'https://www.googletagmanager.com',
        'https://connect.facebook.net',
        'https://dna.uol.com.br',
        'https://stc.pagseguro.uol.com.br',
        'https://assets.pagseguro.com.br',
        'https://cdnjs.cloudflare.com',
        'https://kit.fontawesome.com',
        'https://code.jquery.com',
        'https://stackpath.bootstrapcdn.com',
        'https://use.fontawesome.com',
        'https://maxcdn.bootstrapcdn.com',
        'https://fonts.google.com',
        'https://kit.fontawesome.com',
        'https://ka-f.fontawesome.com',
        'https://cdn.jsdelivr.net',
        'https://www.googleadservices.com',
        'https://connect.facebook.net',
        'https://googleads.g.doubleclick.net',
        'https://www.youtube.com',
        'https://static.doubleclick.net',
        'https://h.online-metrix.net', //GetNet
        'https://sdk.mercadopago.com/js/v2',
        'https://http2.mlstatic.com/storage/event-metrics-sdk/js',
        'https://checkout.pagar.me',
        'https://www.paypal.com',
        'https://api.mundipagg.com',
        'https://api.mundipagg.com/core/v1/',
        'https://www.google.com/recaptcha/',
        'https://www.google.com'
    ];
    $allow_scripts = implode(' ', $allow);
    //Adiciona fontes do cliente:
    $objConfig = new Config();

    if ($objConfig->_fontes_externas) {
        $allow_scripts .= " ".str_replace("\n"," ", $objConfig->_fontes_externas);
    }
    header("X-Frame-Options: SAMEORIGIN");
    header("Content-Security-Policy: script-src 'self' 'unsafe-inline' 'unsafe-eval' " . $allow_scripts . "; child-src *; object-src *; img-src * data:; font-src * data:;");
    header("Strict-Transport-Security: max-age=31536000; includeSubdomains; preload");
    header("X-XSS-Protection: 1; mode=block");
    header("X-Content-Type-Options: nosniff");
    header("Referrer-Policy: no-referrer-when-downgrade");
    header("Feature-Policy: geolocation 'self'; midi 'none'; notifications 'none'; push 'self'; sync-xhr 'self'; microphone 'none'; camera 'none'; magnetometer 'none'; gyroscope 'none'; speaker 'none'; vibrate 'self'; fullscreen 'self'; payment 'self'");
    header("Permissions-Policy: geolocation=(self)");
}

//Personaliza URLS:
add_action('init', [$objPlugin, 'rewriteTag'], 10);
add_action('init', [$objPlugin, 'customUrl'], 11);
add_action('init', [$objPlugin, 'addShortCodes'], 12);
add_action('init', [$objPlugin, 'frameticketGallery'], 12);
add_action( 'save_post', [$objPlugin, 'saveGaleriaEvento'] );

//Menu do painel WP
add_action('admin_menu', [$objPlugin, 'addMenu']);

//Meta box de Galerias Frameticket
add_action('add_meta_boxes', [$objPlugin, 'metaboxEventosGaleria'],10,2);

//Scripts e CSS do site
add_action('wp_head', [$objPlugin, 'addGoogleAnalyticsGtag']);
add_action('wp_head', [$objPlugin, 'addPixelFacebook']);
add_action('wp_enqueue_scripts', [$objPlugin, 'scriptsPublic']);
add_action('admin_enqueue_scripts', [$objPlugin, 'scriptsPrivate']);

//Customiza o titulo das páginas
add_filter('pre_get_document_title', [$objPlugin, 'custom_document_title'], 10);
add_filter('script_loader_tag', [$objPlugin, 'my_script_loader_tag'], 10, 2);

//Registra as Widgets:
add_action('widgets_init', [$objPlugin, 'registraWidgets']);

//Ativação do plugin
register_activation_hook($mppPluginFile, [$objPlugin, 'ativaPlugin']);
//Desativação do Plugin
register_deactivation_hook($mppPluginFile, [$objPlugin, 'desativaPlugin']);
