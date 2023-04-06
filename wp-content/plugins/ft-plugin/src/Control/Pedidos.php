<?php

namespace PluginFrameticket\Control;

use PluginFrameticket\Middleware\Api;
use PluginFrameticket\Control\Clientes;
use PluginFrameticket\Control\Config;
use PluginFrameticket\Control\Eventos;
use PluginFrameticket\Core\Controller;

class Pedidos extends Controller
{

    public function __construct()
    {
        $this->_api = new Api();
        $this->_config = new Config();
    }

    public function zeraCarrinho($id_prog = 0)
    {
        if ($id_prog) {
            unset($_SESSION['carrinho'][$id_prog]);
        } else {
            unset($_SESSION['carrinho']);
        }
    }


    public function isLogado()
    {
        $objCli = new Clientes();
        return $objCli->isLogado();
    }

    public function getCarrinho($id_evento = 0, $id_plano = 0, $campo = '')
    {
        if ($id_evento && $id_plano && $campo) {
            return $_SESSION['carrinho'][$id_evento]['produtos'][$id_plano][$campo];
        } else if ($id_evento && $id_plano) {
            return $_SESSION['carrinho'][$id_evento]['produtos'][$id_plano];
        } else if ($id_evento) {
            return $_SESSION['carrinho'][$id_evento];
        } else {
            return $_SESSION['carrinho'];
        }
    }



    public function acessarCaixa($post = [])
    {
        if ($post['ide']) {
            if ($post['data_visita']) {
                $_SESSION['carrinho'][$post['ide']]['data_visita'] = $post['data_visita'];
            }
            if ($post['hora_visita']) {
                $_SESSION['carrinho'][$post['ide']]['hora_visita'] = $post['hora_visita'];
            }
        }
        $_SESSION['id_comissario'] = ($post['id_comissario']) ? $post['id_comissario'] : 0;

        return site_url('pagamento');
    }


    public function clearCarrinho($id_evento = 0)
    {
        if ($id_evento) {
            unset($_SESSION['carrinho'][$id_evento]);
        } else {
            unset($_SESSION['carrinho']);
        }
    }

    public function showCarrinhoCard($id_evento = 0)
    {
        if (count($_SESSION['carrinho'][$id_evento]['produtos'])) {
            $total = 0;
            foreach ($_SESSION['carrinho'][$id_evento]['produtos'] as $id => $item) {
                $html .= '<div class="row small listaPlanosDesconto">';
                $html .= '<div class="col-md-7 col-7">
                                ' . $item['produto'] . '
                            </div>
                            <div class="col-md-3 col-3 text-center">
                            ' . $item['quant'] . '
                            </div>
                            <div class="col-md-1 col-1 text-center">
                                <button type="button" class="btn btn-danger btn-sm del-item-carrinho" data-ide="' . $id_evento . '" data-id="' . $id . '"><i class="fa fa-trash"></i></button>
                            </div>
                            ';
                $html .= '</div>';
                $total += $item['quant'] * $item['valor_venda'];
            }

            $html .= '<div class="row small listaPlanosDesconto font-weight-bold">';
            $html .= '<div class="col-md-6 col-6">
                                <b>TOTAL</b>
                            </div>
                            <div class="col-md-6 col-6 text-right">
                                R$ ' . number_format($total, 2, ',', '.') . '
                            </div>
                            ';
            $html .= '</div>';

            return $html;
        } else {
            return 'Nenhum produto adicionado até o momento!';
        }
    }

    protected function setPedido()
    {
        $objEvent = new Eventos();
        $partner = $objEvent->getPartner();
        $objEvent->cleanPartner();

        $objCli = new Clientes();
        $pedido = [
            'id_cliente' => $objCli->getUser('id'),
            'whitelabel' => 'N',
            'ip' => $this->_api->getIP(),
            'tokenFt' => $_SESSION['tokenPost'],
            'id_comissario' => $partner['id']
        ];


        $carrinho = $this->getCarrinho();


        if ($carrinho) {
            foreach ($carrinho as $id_event => $c) {
                if ($c['produtos']) {

                    foreach ($c['produtos'] as $item) {

                        if ($item['quant'] >= 1) {
                            $p = 0;
                            for ($q = 1; $q <= $item['quant']; $q++) {
                                $id_lugar = $item['lugares'][$p];
                                $ingresso = [
                                    'id_plano' => $item['id_plano'],
                                    'id_lote' => $item['id_lote'],
                                    'lote' => $item['lote'],
                                    'tipo_evento' => $item['tipo_evento'],
                                    'plano' => $item['produto'],
                                    'meiaentrada' => ($item['meiaentrada']) ? $item['meiaentrada'] : 'N',
                                    'tipomeia' => '',
                                    'valor' => $item['valor_venda'],
                                    'cupom' => $item['cupom'],
                                    'id_comissario' => $partner['id'],
                                    'nome' => $objCli->getUser('nome'),
                                    'doc' => $objCli->getUser('documento_num'),
                                    'tipodoc' => $objCli->getUser('doc_principal'),
                                    'tel' => $objCli->getUser('celular'),
                                    'email' => $objCli->getUser('email'),
                                    'empresa' => '',
                                    'cargo' => '',
                                    'convenio' => $item['convenio'],
                                    'id_lugar' => $id_lugar,
                                    'id_programacao' => $item['id_programacao'],
                                    'data_visita' => ($c['data_visita']) ? $this->datagringa($c['data_visita']) : "",
                                    'hora_visita' => $c['hora_visita'],
                                ];
                                $p++;
                                $ingressos[$id_event][] = $ingresso;
                            }
                        }
                    } //foreach itens
                }
            }

            $pedido['ingressos'] = $ingressos;
            $this->_api->postJson($pedido, '/site/set-order');
            return $this->_api->ret;
        }
    }

    public function getBrand($bin = "", $id_conta = 0)
    {
        if ($bin && $id_conta) {
            $this->_api->get('/site/get-brand/' . str_pad($bin, 0, 9) . '/' . $id_conta);
            return $this->_api->ret;
        }
    }

    public function showCaixa($args = [])
    {
        $this->_args = $args;
        $objCli = new Clientes();

        if (count($this->getCarrinho())) {
            $objConfig = new Config();
            if ($objCli->isLogado() || $objConfig->_sem_conta == 'S') {
                //Registra o pedido:
                $res = $this->setPedido();



                if ($res['id_pedido']) {
                    $_SESSION['carrinho']['id_pedido'] = $res['id_pedido'];
                    $dados = [
                        'carrinho' => $this->getCarrinho(),
                        'totais' => $this->getTotalCarrinho(),
                        'id_pedido' => $res['id_pedido'],
                        'cliente' => $objCli->getDadosUser(),
                        'gw' => $res['gw'],
                        'termos_compra' => $res['termos_compra'],
                        'parcelas' => $res['parcelas']
                    ];
                    $this->renderTemplate('payment', $dados);
                } else {
                    $this->renderTemplate('payment-error', $res);
                }
            } else {
                $objCli->showLoginCad();
            }
        } else {
            $this->renderTemplate('empty-cart');
        }
    }

    public function getPagSeguroIdSession($id_conta)
    {
        $this->_api->get('/site/pagseguro/production/getsession/' . $id_conta);
        return $this->_api->ret['id_session'][0];
    }

    public function geraBin($cartao)
    {
        $cartao = str_replace(array(' ', '_', '.', '-', '/'), '', $cartao);
        return substr($cartao, 0, 6) . '******' . substr($cartao, -4);
    }

    public function efetuarPagamento($post = [])
    {
        $objCli = new Clientes();
        $objConfig = new Config();

        if ($objConfig->verifyRcScore()['status'] == 'FAIL') {
            return [
                'status' => 'FAIL',
                'msg' => 'Não autorizado',
                'url' => '/'
            ];
        }

        if ($post && ($objCli->isLogado() || $objConfig->_sem_conta == 'S')) {
            $totais = $this->getCarrinhoTotais();

            if ($objConfig->_sem_conta == 'S' && !$objCli->isLogado()) {
                $pagador = [
                    'nome' => $post['cob_nome'],
                    'datanasc' => $post['cob_datanasc'],
                    'tipodoc' => 'CPF',
                    'doc' => $post['cob_documento'],
                    'tel' => $post['cob_tel'],
                    'email' => $post['cob_email'],
                ];
            } else {
                $pagador = [
                    'nome' => $post['cob_nome'],
                    'datanasc' => $post['cob_datanasc'],
                    'tipodoc' => 'CPF',
                    'doc' => $post['cob_documento'],
                    'endereco' => $objCli->getUser('endereco'),
                    'num' => $objCli->getUser('num'),
                    'bairro' => $objCli->getUser('bairro'),
                    'cidade' => $objCli->getUser('cidade_nome'),
                    'uf' => $objCli->getUser('estado_nome'),
                    'cep' => $objCli->getUser('cep'),
                    'tel' => $post['cob_tel'],
                    'email' => $objCli->getUser('email'),
                ];
            }
            $payment = [
                'tokenFt' => $_SESSION['tokenPost'],
                'fpgto' => $post['fpgto'],
                'total' => $totais['total'],
                'parcelas' => $post['condicao'],
                'hash' => $post['hash'],
                'cartao' => [
                    'bandeira' => $post['bandeira'],
                    'token' => $post['tokenCard'],
                    'id_pgto' => isset($post['id_pgto']) ? $post['id_pgto'] : null,
                    'transaction_id' => $post['transaction_id'], // Gerado em transações no frontend, geralmente carteiras digitais (PayPal...)
                    'transaction_data' => $post['transaction_data'], // Gerado em transações no frontend, geralmente carteiras digitais (PayPal...)
                    'payment_method_id' => isset($post['MPHiddenInputPaymentMethod']) ? $post['MPHiddenInputPaymentMethod'] : null, // No mercadopago.js gera uma identificação, usado na API
                    'numero' => (!$post['tokenCard']) ? $post['cartao_num'] : '',
                    'cvv' => (!$post['tokenCard']) ? $post['cartao_cvv'] : '',
                    'mes' => (!$post['tokenCard']) ? $post['cartao_mes_exp'] : '',
                    'ano' => (!$post['tokenCard']) ? $post['cartao_ano_exp'] : '',
                    'bin' => $this->geraBin($post['cartao_num']),
                ],
                'pagador' => $pagador
            ];
            $extra = ['transaction_id' => $post['transaction_id']];
            $res_payer = $this->validatePayer($pagador, $post['fpgto'], $extra);

            if ($res_payer['status'] === 'OK') {
                $this->_api->postJson($payment, 'site/set-payment/' . $_SESSION['carrinho']['id_pedido']);
                $res = $this->_api->ret;

                if ($res['status'] == 'OK' || $res['status'] == 'ANALISE' || ($res['status'] == 'PENDENTE' && $res['qrcode'])) {
                    $res['order_id'] = $_SESSION['carrinho']['id_pedido'];
                    $res['url'] = get_site_url() . '/pedido/' . $res['hash_localizador'];
                    $this->clearCarrinho();
                }
                return $res;
            }
            return [
                'status' => 'FAIL',
                'msg' => $res_payer['msg'],
                'url' => ''
            ];
        }
        return [
            'status' => 'FAIL',
            'msg' => 'A sua conexão expirou!',
            'url' => get_site_url()
        ];
    }

    function validatePayer($pagador = [], $fpgto = '', $extra = [])
    {
        if (in_array($fpgto, array('PIX', 'BOLETO', 'FREE'))) {
            $status = 'OK';
            $msg = [];

            if (count(explode(' ', trim($pagador['nome']))) <= 1) {
                $msg[] = 'Informe o nome completo';
                $status = 'FAIL';
            }

            if (strlen($pagador['doc']) < 11) {
                $status = 'FAIL';
                $msg[] = 'Informe o CPF corretamente';
            }
            return [
                'status' => $status,
                'msg' => implode('<br>', $msg)
            ];
        }

        if ($fpgto == 'CREDITO' && $pagador['nome'] && $pagador['doc'] && $pagador['tel'] && $pagador['email']) {
            return [
                'status' => 'OK'
            ];
        }

        if ($fpgto == 'PAYPAL') {
            if ($extra['transaction_id']) {
                return [
                    'status' => 'OK'
                ];
            } else {
                return [
                    'status' => 'FAIL',
                    'msg' => 'PayPal não processou o pagamento. Verifique se houve a cobrança devidamente e tente novamente.',
                    'url' => ''
                ];
            }
        }
        return [
            'status' => 'FAIL',
            'msg' => 'Por favor, preencha todos os campos!',
            'url' => ''
        ];
    }

    public function getPedidos()
    {
        $objCli = new Clientes();
        $id_cliente = $objCli->getUser('id');

        if ($id_cliente) {
            $this->_api->get('/site/pedidos/' . $this->_api->hash($id_cliente));
            return $this->_api->ret;
        }
    }




    public function getTabelaIngressos($vendas = [], $status_tipo)
    {
        if (count($vendas)) {
            foreach ($vendas as $v) {
                if (count($v['itens'])) {

                    if (count($v['ingressos'])) {
                        if ($status_tipo == 'APROVADA') {
                            $html .= '<div class="alert alert-info">Clique sobre o ingresso para abrir o e-ticket em PDF.</div>';
                            $html .= '<div class="row">';
                            foreach ($v['ingressos'] as $ing) {
                                $html .= $this->showEticket($ing);
                            }
                            $html .= '</div>';
                        } else if ($status_tipo == 'ANALISE') {
                            $html = '<div class="alert alert-warning">Somente será possível acessar os ingressos após confirmação de pagamento, por favor, aguarde.</div>';
                        } else {
                            $html = '<div class="alert alert-danger">Nenhum ingresso emitido nesse pedido.</div>';
                        }
                    } else {
                        $html = '<div class="alert alert-info">Nenhum ingresso emitido nesse pedido.</div>';
                    }
                } else {
                    $html = '<div class="alert alert-info">Nenhum ingresso emitido nesse pedido.</div>';
                }
            }
        }
        return $html;
    }

    public function showEticket($ing = [])
    {
        if ($ing['permite'] == 'S') {
            $btn_edita = '<button type="button" class="btn btn-primary btn-sm float-right" title="' . $edicao['msg'] . '" onclick="editaEticket(' . $ing['localizador'] . ')"><i class="fa fa-pencil-square-o"></i> Editar</button>';
        }

        $plano = $btn_edita . $ing['plano'];
        $html .= '<div class="col-12 col-lg-4">';
        $html .= '<div class="card pad">';
        $html .= '<div class="text-truncate card-header"><b>' . $plano . '</b></div>';
        if ($ing['forma_retirada'] != 'PONTO DE VENDA') {
            $url_eticket = $this->_api->_apiUrl . '/eticket/' . $this->_api->hash($ing['localizador']);
            $html .= '<a href="' . $url_eticket . '" style="color:inherit;text-decoration:none" target="_blank">';
        }
        $html .= '<div class="card-body">';
        $lugar_numerado = "";
        if ($ing['lugar_numerado']) {
            $lugar_numerado = '<div>' . $ing['lugar_numerado'] . '</div>';
        }
        $html .= '<div class="text-center pad"><b><div class="text-truncate">' . mb_strtoupper($ing['nominal']) . '</div><small><i class="fa fa-hashtag"></i> ' . $ing['localizador'] . '</small>' . $lugar_numerado . '</b></div>';
        $html .= '<b><i class="fa fa-calendar"></i> Data da visita: ' . date('d/m/Y', strtotime($ing['data'])) . ' ' . (($ing['hora_abertura'] != '00:00:00') ? substr($ing['hora_abertura'], 0, 5) : '') . '</b><br>';
        if ($ing['validade'] && $ing['validade'] != '0000-00-00') {
            $html .= '<small><i class="fa fa-calendar"></i> Válido até ' . date('d/m/Y', strtotime($ing['validade'])) . ' ' . (($ing['hora_abertura'] != '00:00:00') ? substr($ing['hora_abertura'], 0, 5) : '') . '</small><br>';
        }
        $html .= '<small>' . $ing['local'] . ' ' . $ing['cidade'] . '/' . $ing['uf'] . '<br>' . $ing['endereco'] . ' ' . $ing['num'] . ' - ' . $ing['bairro'] . '</small>';
        $html .= '</div>'; //card-body
        if ($ing['forma_retirada'] != 'PONTO DE VENDA') {
            $html .= '</a>'; //card
        }
        $html .= '</div>'; //card
        $html .= '</div>'; //col

        return $html;
    }

    public function consultaHorarios($id_evento, $id_pedido = 0, $data_visita = '')
    {
        $post = [
            'id_evento' => $id_evento,
            'id_pedido' => $id_pedido,
            'data_visita' => $data_visita,
            'tokenFt' => $_SESSION['tokenPost'],
            'carrinho' => $this->getCarrinho($id_evento, 0, 'produtos'),
        ];
        $this->_api->postJson($post, '/site/consulta-horarios');
        return $this->_api->puro;
    }

    public function finalizarCompra($id_evento = 0, $titulo = '', $post = [])
    {
        $this->addCarrinho($id_evento, $titulo, $post);
        return get_site_url() . '/pagamento';
    }


    public function addCarrinho($id_evento = 0, $titulo = '', $post = [])
    {
        //Limpa o carrinho se não tiver "carrinho de compras" ativo no site:
        if (get_option('FRAMETICKET_VENDAS_CARRINHO') <> 'S') {
            $this->zeraCarrinho();
        }
        $objEvent = new Eventos();
        $partner = $objEvent->getPartner();

        if (count($post['quant'])) {

            foreach ($post['quant'] as $index => $quant) {

                if ($quant >= 1) {
                    $plano = $_SESSION['plans'][$id_evento][$index];
                    $total = ($plano['price'] * $quant);
                    $lugares = [];
                    $lugares_nomes = "";
                    $register = 'OK';

                    if ($post['lugares'][$index]) {
                        $lugares = array_filter(explode(',', $post['lugares'][$index]));

                        if ($lugares) {

                            foreach ($lugares as $id_lugar) {
                                $lugaresn[] = $_SESSION['lugares'][$id_lugar];
                            }
                            $lugares_nomes = implode(', ', $lugaresn);
                        }
                    }

                    //Controle para não vender plano de lugares numerados sem a seleção
                    if ($post['k'][$index] == 'S' && !$lugares_nomes) {
                        $register = '';
                    }

                    if ($register == 'OK') {
                        $_SESSION['carrinho'][$id_evento]['produtos'][$index] = [
                            'id_plano' => $plano['id'],
                            'lote' => $plano['batch'],
                            'id_lote' => $plano['id_batch'],
                            'tipo_evento' => $plano['event_type'],
                            'id_programacao' => $plano['id_prog'],
                            'produto' => $plano['plan'],
                            'valor_venda' => $plano['price'],
                            'taxa' => $plano['rate'],
                            'quant' => $quant,
                            'lugares' => $lugares,
                            'lugares_nomes' => $lugares_nomes,
                            'cupom' => ($plano['coupon_apply']) ? $post['cupom'] : "",
                            'convenio' => ($plano['convenio_apply']) ? $plano['convenio'] : [],
                            'id_comissario' => ($plano['partner_apply']) ? $partner['id'] : "",
                            'total' => $total,
                        ];
                    }
                } else {
                    unset($_SESSION['carrinho'][$id_evento]['produtos'][$index]);
                }
            }
            $tem = count($_SESSION['carrinho'][$id_evento]['produtos']);

            if ($tem) {
                $_SESSION['carrinho'][$id_evento]['titulo'] = utf8_encode($titulo);
                $_SESSION['carrinho'][$id_evento]['data_visita'] = $post['data_visita'];
                $_SESSION['carrinho'][$id_evento]['hora_visita'] = $post['hora_visita'];
                $_SESSION['carrinho'][$id_evento]['cupom'] = $post['cupom'];
            }
            return ($tem) ? $this->getTotalCarrinho() : ['status' => 'FAIL'];
        }

        if ($post['data_visita'] && $id_evento) {
            $_SESSION['carrinho'][$id_evento]['data_visita'] = $post['data_visita'];

            if ($post['hora_visita']) {
                $_SESSION['carrinho'][$id_evento]['hora_visita'] = $post['hora_visita'];
            }
        }
    }

    function maisCarrinho($id_evento = 0, $index = 0, $post = [])
    {
        $plano = $_SESSION['plans'][$id_evento][$index];

        if ($_SESSION['carrinho'][$id_evento]) {

            $nova_quant = $_SESSION['carrinho'][$id_evento]['produtos'][$index]['quant'] + 1;
            if ($plano['quantity_max'] >= $nova_quant) {

                $total_item = ($plano['price'] * $nova_quant);
                $_SESSION['carrinho'][$id_evento]['produtos'][$index]['quant'] = $nova_quant;
                $_SESSION['carrinho'][$id_evento]['produtos'][$index]['total'] = $total_item;

                $totais = $this->getTotalCarrinho();

                return [
                    'status' => 'OK',
                    'quant' => $nova_quant,
                    'total_item' => number_format($total_item, 2, ',', '.'),
                    'total' => number_format($totais['total'], 2, ',', '.'),
                    'taxas' => number_format($totais['total_taxas'], 2, ',', '.'),
                    'total_compra' => number_format($totais['total_compra'], 2, ',', '.'),
                    'total_carrinho' => $totais['total_carrinho'],
                ];
            } else {
                return [
                    'status' => 'FAIL',
                    'msg' => 'O total não pode ultrapassar o limite de ' . $plano['quantity_max'] . ' itens'
                ];
            }
        }
        //Cria o carrinho
        else if ($post) {
            $post['quant'][$index] = 1;
            $totais = $this->addCarrinho($id_evento = 0, utf8_decode($post['titulo']), $post);
            return [
                'status' => 'OK',
                'quant' => 1,
                'total_item' => number_format($plano['price'], 2, ',', '.'),
                'total' => number_format($totais['total'], 2, ',', '.'),
                'taxas' => number_format($totais['total_taxas'], 2, ',', '.'),
                'total_compra' => number_format($totais['total_compra'], 2, ',', '.'),
                'total_carrinho' => $totais['total_carrinho'],
            ];
        }
    }

    function menosCarrinho($id_evento = 0, $index = 0)
    {
        $plano = $_SESSION['plans'][$id_evento][$index];

        if ($_SESSION['carrinho'][$id_evento]) {

            $nova_quant = $_SESSION['carrinho'][$id_evento]['produtos'][$index]['quant'] - 1;

            if ($nova_quant >= 1) {
                $total_item = ($plano['price'] * $nova_quant);
                $_SESSION['carrinho'][$id_evento]['produtos'][$index]['quant'] = $nova_quant;
                $_SESSION['carrinho'][$id_evento]['produtos'][$index]['total'] = $total_item;

                $totais = $this->getTotalCarrinho();

                return [
                    'status' => 'OK',
                    'quant' => $nova_quant,
                    'total_item' => number_format($total_item, 2, ',', '.'),
                    'total' => number_format($totais['total'], 2, ',', '.'),
                    'taxas' => number_format($totais['total_taxas'], 2, ',', '.'),
                    'total_compra' => number_format($totais['total_compra'], 2, ',', '.'),
                    'total_carrinho' => $totais['total_carrinho'],
                ];
            } else {
                unset($_SESSION['carrinho'][$id_evento]['produtos'][$index]);
                if (!count($_SESSION['carrinho'][$id_evento]['produtos'])) {
                    unset($_SESSION['carrinho'][$id_evento]);
                }
                return [
                    'quant' => 0,
                ];
            }
        }
    }


    function showCarrinho($args = [])
    {
        $this->_args = $args;
        $args['carrinho'] = $_SESSION['carrinho'];
        $args['totais'] = $this->getTotalCarrinho();
        $args['url_pagamento'] = get_site_url() . '/pagamento';

        return $this->renderTemplate('cart', $args, 'S');
    }

    function getTotalCarrinho()
    {
        $total_carrinho = 0;
        $total_compra = 0;
        $total_taxas = 0;
        $ingressos = 0;

        if (count($_SESSION['carrinho'])) {

            foreach ($_SESSION['carrinho'] as $carrinho) {

                if (count($carrinho['produtos'])) {

                    foreach ($carrinho['produtos'] as $p) {
                        $valor_compra = $p['quant'] * $p['valor_venda'];
                        $valor_taxas = $p['quant'] * $p['taxa'];
                        $total_carrinho += $p['quant'];
                        $total_compra += $valor_compra;
                        $total_taxas += $valor_taxas;
                        $ingressos += $p['quant'];
                    }
                }
            }
        }
        return [
            'ingressos' => $ingressos,
            'total' => $total_compra,
            'total_compra' => $total_compra + $total_taxas,
            'total_taxas' => $total_taxas,
            'total_carrinho' => $total_carrinho,
        ];
    }

    public function getCarrinhoTotais()
    {
        $produtos = 0;
        $total = 0;
        $total_taxas = 0;
        if (count($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $ev) {
                foreach ($ev['produtos'] as $item) {
                    $produtos += $item['quant'];
                    $total_taxas += $item['quant'] * $item['taxa'];
                    $total += $item['quant'] * $item['valor_venda'];
                }
            }
            $total_compra = $total + $total_taxas;
            return ['produtos' => $produtos, 'total' => $total_compra, 'total_itens' => $total];
        }
        return ['produtos' => $produtos, 'total' => $total];
    }

    public function agendarVisita($id_pedido, $data_visita, $horario)
    {
        if (!$data_visita || !$horario || !$id_pedido) {
            return ['status' => 'FAIL', 'msg' => 'Por favor, selecione um horário'];
        } else {
            $post = [
                'id_pedido' => $id_pedido,
                'data_visita' => $data_visita,
                'horario' => $horario,
                'tokenFt' => $_SESSION['tokenPost'],
            ];
            $this->_api->postJson($post, '/site/agenda-horario');
            return $this->_api->ret;
        }
    }

    public function nomearTicket($id = 0, $post_string = '')
    {
        $objCli = new Clientes();
        $objConfig = new Config();
        if ($objCli->isLogado() || $objConfig->_sem_conta == 'S') {
            if ($id && $post_string) {
                parse_str($post_string, $p);

                if ($p['nome'] && $p['numdoc']) {

                    $p['id_cliente'] = $objCli->getUser('id');
                    $p['tokenFt'] = $_SESSION['tokenPost'];

                    $this->_api->postJson($p, '/site/up-ticket/' . $id);
                    return $this->_api->ret;
                }
                return ['status' => 'FAIL', 'msg' => 'Por favor, preencha os campos com asterísco (*)'];
            }
            return ['status' => 'FAIL', 'msg' => 'Dados insuficientes!'];
        }
        return ['status' => 'FAIL', 'msg' => 'Sua sessão expirou, por favor, efetue login novamente!'];
    }

    public function showPedido($args = [])
    {
        global $wp_query;
        $this->_args = $args;
        $id_pedido = $wp_query->query_vars['id'];

        $objCli = new Clientes();
        $objConfig = new Config();
        if ($objCli->isLogado() || $objConfig->_sem_conta == 'S') {

            $this->_api->postJson(['id_client' => $objCli->getUser('id'), 'tokenFt' => $_SESSION['tokenPost']], 'site/order/' . $id_pedido);
            $pedido = $this->_api->ret['order'];
            if ($pedido['id']) {
                $args = [
                    'hash_localizador' => $id_pedido,
                    'pedido' => $pedido
                ];
                //echo '<pre>' . print_r($pedido['sales'], true) . '</pre>';
                //Transforma em array os campos extras:
                if ($args['pedido']['sales']) {
                    foreach ($args['pedido']['sales'] as $s => $sale) {
                        foreach ($sale['tickets'] as $i => $t) {
                            if ($t['form']['form_json']) {
                                $args['pedido']['sales'][$s]['tickets'][$i]['form']['form_json'] = json_decode($t['form']['form_json'], true);
                            }
                        }
                    }
                }
                return $this->renderTemplate('order', $args, 'S');
            } else {
                return $this->renderTemplate('not-found', [], 'S');
            }
        } else {
            $args = [
                'url_cadastro' => get_site_url() . '/cadastro',
                'prefix_name' => '-back',
            ];
            $objCli->setUrlCallback('./pedido/' . $id_pedido);
            return $this->renderTemplate('not-logged', $args, 'S');
        }
    }

    public function showPedidos($args = [])
    {
        $this->_args = $args;
        $objCli = new Clientes();
        if ($objCli->isLogado()) {
            $this->_api->get('site/orders/' . $objCli->getUser('id'));
            $args = [
                'pedidos' => $this->_api->ret['orders'],
                'url_pedido' => get_site_url() . '/pedido',
            ];

            return $this->renderTemplate('orders', $args, 'S');
        } else {
            $args = [
                'url_cadastro' => get_site_url() . '/cadastro',
                'prefix_name' => '-back',
            ];
            $objCli->setUrlCallback('./pedidos');
            return $this->renderTemplate('not-logged', $args, 'S');
        }
    }


    public function showMenuCarrinho($attr = [])
    {
        $attr['page'] = ($attr['page']) ? site_url($attr['page']) : '#';
        $totais_carrinho = $this->getCarrinhoTotais();
        $attr['produtos'] = $totais_carrinho['produtos'];
        $attr['total'] = $totais_carrinho['total'];
        return $this->renderTemplate('navbar-cart', $attr, 'S');
    }

    function verifyPaymentPix($hash_localizador = '', $txt = '')
    {
        if ($hash_localizador) {
            $this->_api->get('site/order-status/' . $hash_localizador);
            $ret = $this->_api->ret;
            if ($ret['status'] == 'OK') {
                $ret['url'] = get_site_url() . '/pedido/' . $hash_localizador;
                return $ret;
            }
            return $ret;
        }
    }


    public function setTransaction($transaction_id)
    {
        $id_pedido = $_SESSION['carrinho']['id_pedido'];
        $totais = $this->getCarrinhoTotais();

        $payment = [
            'msg_gw' => 'Iniciando o pagamento com PayPal',
            'fpgto' => 'PAYPAL',
            'codtrans' => $transaction_id,
            'fk_pedido' => $id_pedido,
            'situacao' => 'PENDENTE',
            'dispositivo' => 'WEB',
            'total' => number_format($totais['total'], 2, ',', '.'),
            'tokenFt' => $_SESSION['tokenPost']
        ];

        $this->_api->postJson($payment, 'site/set-payment-data/' . $id_pedido);
        $res = $this->_api->ret;
        return $res;
    }
}
