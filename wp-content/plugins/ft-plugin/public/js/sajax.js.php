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

use PluginFrameticket\Control\Eventos;
use PluginFrameticket\Control\Clientes;
use PluginFrameticket\Control\Config;
use PluginFrameticket\Control\Pedidos;
use PluginFrameticket\Control\Listas;

function getEndereco($cep = 0)
{
    if ($cep) {
        $obj = new Clientes();
        return $obj->getEndereco($cep);
    }
}

function getCidades($estado = 0)
{
    if ($estado) {
        $obj = new Clientes();
        return $obj->showCidades('JSON', $estado);
    }
}

function verifyEmail($email, $id = 0)
{
    if ($email) {
        $obj = new Clientes();
        return $obj->verifyEmail($email, $id);
    }
}

function verifyLogin($login, $senha, $ambiente = '')
{
    $obj = new Clientes();
    return $obj->verifyLogin($login, $senha, $ambiente);
}

function getPagSeguroIdSession($id_conta, $gw = "")
{
    $obj = new Pedidos();
    return $obj->getPagSeguroIdSession($id_conta);
}


function getBrand($bin = "", $id_conta = 0)
{
    if ($bin) {
        $obj = new Pedidos();
        return $obj->getBrand($bin, $id_conta);
    }
}

function efetuarPagamento($post_string = '')
{
    if ($post_string) {
        $obj = new Pedidos();
        parse_str($post_string, $post);
        return $obj->efetuarPagamento($post);
    } else {
        return ['status' => 'FAIL', 'msg' => 'Nenhum dado para ser processado!'];
    }
}

function recuperarSenha($login = "")
{
    if ($login) {
        $obj = new Clientes();
        return $obj->recuperarSenha($login);
    }
}

function salvarSenha($senha_nova, $hash)
{
    if ($senha_nova && $hash) {
        $obj = new Clientes();
        return $obj->salvarSenha($senha_nova, $hash);
    }
}


function getMapa($id_evento, $id_controle, $id_prog, $lugares_selecionados = '')
{
    if ($id_controle) {
        $obj = new Eventos();
        return $obj->getMapa($id_evento, $id_controle, $id_prog, $lugares_selecionados);
    }
}

function showPlanos($type = '', $id_event = 0, $date_visit = '', $cupom = "", $id_partner = 0, $hour_visit = '', $convenio = '')
{
    $obj = new Eventos();
    $type = ($type) ? strtolower($type) : 'unidade';
    return $obj->showPlanos($type, $id_event, $date_visit, $cupom, $id_partner, $hour_visit, $convenio);
}

function buscaCupom($cupom)
{
    $obj = new Eventos();
    return $obj->buscaCupom($cupom);
}

function consultaData($type = '', $id_evento = 0, $data_visita = '', $timetable = '', $cupom = '', $id_comissario = 0, $convenio = '')
{
    $obj = new Eventos();
    return $obj->consultaData($type, $id_evento, $data_visita, $timetable, $cupom, $id_comissario, $convenio);
}

/**
 * Consulta horários para alterar o agendamento do pedido
 *
 * @param integer $id_evento
 * @param integer $id_pedido
 * @param string $data_visita
 * @return void
 */
function consultaHorarios($id_evento, $id_pedido, $data_visita = '')
{
    $obj = new Pedidos();
    return $obj->consultaHorarios($id_evento, $id_pedido, $data_visita);
}

function agendarVisita($id_pedido, $data_visita, $horario)
{
    $obj = new Pedidos();
    return $obj->agendarVisita($id_pedido, $data_visita, $horario);
}


function clearCarrinho($id_evento = 0)
{
    $obj = new Pedidos();
    return $obj->clearCarrinho($id_evento);
}

function acessarCaixa($post_string = 0)
{
    $obj = new Pedidos();
    parse_str($post_string, $post);
    return $obj->acessarCaixa($post);
}

function addCarrinho($id_evento = 0, $titulo = '', $post_string = '')
{
    $obj = new Pedidos();
    parse_str($post_string, $post);
    return $obj->addCarrinho($id_evento, $titulo, $post);
}

function finalizarCompra($id_evento = 0, $titulo = '', $post_string = '')
{
    $obj = new Pedidos();
    parse_str($post_string, $post);
    return $obj->finalizarCompra($id_evento, $titulo, $post);
}

function maisCarrinho($id_evento = 0, $index = 0, $post_string = '')
{
    $obj = new Pedidos();
    if ($post_string) {
        parse_str($post_string, $post);
    } else {
        $post = [];
    }
    return $obj->maisCarrinho($id_evento, $index, $post);
}

function menosCarrinho($id_evento = 0, $index = 0)
{
    $obj = new Pedidos();
    return $obj->menosCarrinho($id_evento, $index);
}

function excluirContaUser($pass = '')
{
    $obj = new Clientes();
    return $obj->excluirContaUser($pass);
}

function desconectarFB($pass = '')
{
    $obj = new Clientes();
    return $obj->desconectarFB($pass);
}

function getPlans($id_event = 0)
{
    if ($id_event) {
        $obj = new Eventos();
        return $obj->getPlansCart($id_event);
    }
}

function renderLocalMap($id_plano = 0, $plan = '', $type='',$id=0)
{
    $obj = new Eventos();
    return $obj->getplacesPlan($id_plano, $plan, $type, $id);
}

function nomearTicket($id = 0, $post_string = '')
{
    $obj = new Pedidos();
    return $obj->nomearTicket($id, $post_string);
}

function verifyPaymentPix($hash_localizador = '', $txt = '')
{
    $obj = new Pedidos();
    return $obj->verifyPaymentPix($hash_localizador, $txt);
}

function getTotalCarrinho()
{
    $obj = new Pedidos();
    return $obj->getTotalCarrinho();
}

function setTransaction($transaction_id = '')
{
    $obj = new Pedidos();
    return $obj->setTransaction($transaction_id);
}

function verifyCaptcha($token=''){
    $obj = new Config();
    return $obj->verifyCaptcha($token);
}

function eventListSave($post_string = '')
{
    if ($post_string) {
        $obj = new Listas();
        parse_str($post_string, $post);
        return $obj->eventListSave($post);
    } else {
        return ['status' => 'FAIL', 'msg' => 'Nenhum dado para ser processado!'];
    }
}

function requestRemoveAccount()
{
    $cliente = new Clientes();
    return $cliente->requestRemoveAccount();

}

function getDataCalendar($id_event,$month,$year)
{
    $event = new Eventos();
    return $event->getDataCalendar($id_event, $month, $year);
}


#############################
sajax_init();
sajax_export("teste", "getCidades", "getEndereco", "verifyEmail",  "getPagSeguroIdSession", "getBrand", "recuperarSenha", "salvarSenha", "localizador", "getMapa", "editaEticket", "salvarEticket", "showPlanos", "efetuarPagamento", "excluirContaUser", "desconectarFB", "verifyLogin", "clearCarrinho", "buscaCupom", "consultaData", "agendarVisita", "acessarCaixa", "consultaHorarios", "getPlans", "addCarrinho", "menosCarrinho", "maisCarrinho", "finalizarCompra", "renderLocalMap","nomearTicket","verifyPaymentPix","getTotalCarrinho","setTransaction","verifyCaptcha","eventListSave", "requestRemoveAccount", "getDataCalendar");
sajax_handle_client_request();
header("Content-Type: application/javascript; charset=UTF-8", true);
echo sajax_show_javascript();
#############################
