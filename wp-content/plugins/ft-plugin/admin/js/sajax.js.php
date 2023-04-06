<?php
$file = $_SERVER['SCRIPT_FILENAME'];
$dir_wpload = dirname($file, 6);
$dir_local = dirname($file, 3);

header("Content-Type: application/javascript; charset=UTF-8", true);

/** Loads the WordPress Environment and Template */
if (file_exists($dir_wpload . '/wp-load.php')) {
    require $dir_local . '/src/Core/Sajax.php';
    require $dir_wpload . '/wp-load.php';
} else {
    echo "file not found " . $dir_wpload;
}

use PluginFrameticket\Control\Config;
use PluginFrameticket\Control\Clientes;
use PluginFrameticket\Control\Pedidos;
use PluginFrameticket\Middleware\Api;

function test()
{
    echo "teste OK";
}

function buscaEndereco($cep = "")
{
    $obj = new Api();
    return $obj->buscaEndereco($cep);
}

function verifyEmail($email, $id_cliente = 0)
{
    $obj = new Clientes();
    return $obj->verifyEmail($email, $id_cliente);
}

function consultaDisponiveis($tipo, $data_ini, $hora_ini = '', $quant = 0)
{
    $obj = new Pedidos();
    return $obj->consultaDisponiveis($tipo, $data_ini, $hora_ini, $quant);
}

function buscaCliente($palavra = '')
{
    $obj = new Clientes();
    return $obj->buscaCliente($palavra);
}

function registraPedido($post_string = '')
{
    $obj = new Pedidos();
    return $obj->registraPedido($post_string);
}

function addPagesDefault()
{
    $obj = new Config();
    return $obj->addPagesDefault();
}

#############################
sajax_init();
sajax_export("test", "buscaEndereco", "verifyEmail", "consultaDisponiveis", "buscaCliente", "registraPedido", "addPagesDefault");
sajax_handle_client_request();

echo sajax_show_javascript();
#############################
