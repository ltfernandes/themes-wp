<?php

namespace PluginFrameticket\Control;

use PluginFrameticket\Middleware\Api;
use PluginFrameticket\Control\Config;
use PluginFrameticket\Control\Pedidos;
use PluginFrameticket\Core\Controller;

class Clientes extends Controller
{
    public $_api;
    public function __construct()
    {
        $this->_api = new Api();
        $this->_config = new Config();
        if ($_GET['redirect']) {
            $this->setUrlCallback($_GET['redirect']);
        }
        if ($_GET['sair'] == 'S') {
            $this->desconecta();
        }
    }

    public function showForm($args = [])
    {
        $this->_args = $args;
        $res = [];
        if (count($_POST) && $this->_api->validaPost($_POST)) {
            $res = $this->setCadastro($_POST);
            $this->renderTemplate('msg', $res);
        } else {
            unset($_POST);
        }


        if ($res['status'] == 'FAIL' || !$res['status']) {
            $dados = $this->get($_POST);

            $dados['permite_menor'] = $this->_config->_cadastro_menor;
            $dados['site_url'] = get_site_url();
            $dados['url_politicas'] = get_privacy_policy_url();
            $dados['options_cidades'] = $this->showCidades('HTML', $dados['estado'], $dados['cidade']);
            $dados['options_estados'] = $this->showEstados($dados['estado']);
            $dados['tokenFt'] = $_SESSION['tokenPost'];
            $dados['login_nocaptcha_key'] = get_option('login_nocaptcha_key');
            $dados['redirect'] = ($this->getUrlCallback()) ? 'S' : 'N';

            if ($this->isLogado()) {
                return $this->renderTemplate('client-up', ['dados' => $dados], true);
            } else {
                return $this->renderTemplate('client-register', ['dados' => $dados], true);
            }
        }
    }

    public function showFormEsqueciSenha($args = [])
    {
        $this->_args = $args;
        $dados['login_nocaptcha_key'] = get_option('login_nocaptcha_key');

        $objPedido = new Pedidos();
        $dados['carrinho'] =  $objPedido->getCarrinho();
        $dados['totais'] =  $objPedido->getTotalCarrinho();
        $this->renderTemplate('client-forgout-password', $dados);
    }

    public function showLoginCad($attrs = [])
    {
        $this->_args = $attrs;
        $objPedido = new Pedidos();
        $objConfig = new Config();
        $param = [
            'carrinho' => $objPedido->getCarrinho(),
            'totais' => $objPedido->getTotalCarrinho(),
            'url_esqueci' => site_url('esqueci'),
            'url_cadastro' => site_url('cadastro' . (($attrs['noredirect'] == 'S') ? '' : '?redirect=pagamento')),
            'redirect' => ($attrs['noredirect'] == 'S') ? '' : 'S',
            'prefix_name' => '-payment'
        ];
        $this->renderTemplate('client-login', $param);
    }

    public function showLogin($attr = [])
    {
        $this->_args = $attr;
        $nav_mobile = ($attr['mobile'] == 'S') ? '-mobile' : '';
        $objPed = new Pedidos();
        $args = [
            'site_url' => site_url($attr['page']),
            'vendas_carrinho' => get_option('FRAMETICKET_VENDAS_CARRINHO'),
            'url_esqueci' => site_url('esqueci'),
            'url_cadastro' => site_url('cadastro'),
            'url_pedidos' => site_url('pedidos'),
            'url_carrinho' => site_url('carrinho'),
            'total_carrinho' => $objPed->getTotalCarrinho()['total_carrinho'],
            'prefix_name' => $nav_mobile
        ];
        $this->renderTemplate('client-login', $args);
    }



    public function showMenuMinhaConta($attr = [])
    {

        $this->_args = $attr;
        $nav_mobile = ($attr['mobile'] == 'S') ? '-mobile' : '';
        $objPed = new Pedidos();
        $args = [
            'site_url' => site_url($attr['page']),
            'vendas_carrinho' => get_option('FRAMETICKET_VENDAS_CARRINHO'),
            'url_esqueci' => site_url('esqueci'),
            'url_cadastro' => site_url('cadastro'),
            'url_pedidos' => site_url('pedidos'),
            'url_carrinho' => site_url('carrinho'),
            'total_carrinho' => $objPed->getTotalCarrinho()['total_carrinho'],
            'prefix_name' => $nav_mobile
        ];

        if ($this->isLogado()) {
            $args['primeiro_nome'] = $this->getUser('primeiro_nome');
            return $this->renderTemplate('navbar-account-logged' . $nav_mobile, $args, 'S');
        } else {
            return $this->renderTemplate('navbar-account' . $nav_mobile, $args, 'S');
        }
    }

    public function showFormRedefinirSenha($args = [])
    {
        global $wp_query;
        $this->_args = $args;
        $key = $wp_query->query_vars['id'];
        if ($key) {
            $dados['login_nocaptcha_key'] = get_option('login_nocaptcha_key');
            $dados['key'] = $key;

            $objPedido = new Pedidos();
            $dados['carrinho'] =  $objPedido->getCarrinho();
            $dados['totais'] =  $objPedido->getTotalCarrinho();

            $this->renderTemplate('client-reset-pass', $dados);
        }
    }

    public function recuperarSenha($login = '')
    {
        $post = [
            'tokenFt' => $_SESSION['tokenPost'],
            'login' => $login,
            'url' => get_site_url(),
            'whitelabel' => $this->_api->_apiKeyOrg,
        ];
        $this->_api->postJson($post, '/site/reset-pass-client');
        return $this->_api->ret;
    }

    public function salvarSenha($senha_nova, $hash)
    {
        $post = [
            'password' => md5($senha_nova),
            'tokenFt' => $_SESSION['tokenPost'],
            'hash' => $hash,
        ];
        $this->_api->postJson($post, '/site/up-pass-client');
        $arr = $this->_api->ret;
        $arr['url'] = get_site_url();
        return $arr;
    }

    public function showEstados($estado = 0)
    {
        $this->_api->get('/site/search-states');
        $html = '';
        if ($this->_api->ret) {
            $html .= '<option value=""></option>';
            foreach ($this->_api->ret as $id => $d) {
                $selected = ($estado == $id) ? "selected" : "";
                if ($d['estado']) {
                    $html .= '<option value="' . $id . '" ' . $selected . '>' . $d['estado'] . '</option>';
                }
            }
            return $html;
        }
    }

    public function showCidades($formato, $estado = 0, $cidade = 0)
    {
        if ($estado) {
            $this->_api->get('/site/search-citys/' . $estado);
            if ($formato == 'HTML') {
                if (count($this->_api->ret)) {
                    $html = '<option value=""></option>';
                    foreach ($this->_api->ret as $d) {
                        $selected = ($cidade == $d['value']) ? "selected" : "";
                        $html .= '<option value="' . $d['value'] . '" ' . $selected . '>' . $d['text'] . '</option>';
                    }
                    return $html;
                }
            } else {
                return $this->_api->puro;
            }
        }
    }

    public function getEndereco($cep = "")
    {
        if ($cep) {
            $cep = str_replace(['.', '-', '_', '/'], "", $cep);
            $this->_api->get('/site/search-zipcode/' . $cep);
            return $this->_api->puro;
        }
    }

    public function verifyEmail($email, $id = 0)
    {
        if ($email) {
            $this->_api->postJson(['email' => $email, 'id' => $id, 'tokenFt' => $_SESSION['tokenPost']], '/site/verifyEmail');
            $res = $this->_api->ret;
            if ($res['id_cliente']) {
                return 'FAIL';
            } else {
                return 'OK';
            }
        }
    }

    public function verifyLogin($login, $senha, $ambiente = '')
    {
        if ($login && $senha) {

            $objConfig = new Config();
            if ($objConfig->verifyRcScore()['status'] == 'FAIL' && $ambiente == 'pagamento') {
                return [
                    'status' => 'FAIL',
                    'msg' => 'Acesso não autorizado!',
                ];
            }

            $this->_api->postJson(['login' => $login, 'password' => md5($senha), 'tokenFt' => $_SESSION['tokenPost']], '/site/login-client');
            $res = $this->_api->ret;

            if ($res['id']) {

                $this->setAuth($res);
                if ($this->getUrlCallback()) {
                    $this->zeraUrlCallback();
                    return [
                        'status' => 'OK',
                        'url' => ($ambiente) ? get_site_url() . '/' . $ambiente : $this->getUrlCallback(),
                    ];
                } else {
                    return [
                        'status' => 'OK',
                        'url' => ($ambiente) ? get_site_url() . '/' . $ambiente : get_site_url(),
                    ];
                }
            } else {
                return [
                    'status' => 'FAIL',
                    'msg' => 'Não foi possível autenticar seu usuário, por favor, tente novamente.',
                ];
            }
        } else {
            return [
                'status' => 'FAIL',
                'msg' => 'Por favor, informe seu login e senha',
            ];
        }
    }

    public function getUser($campo)
    {
        return $_SESSION['auth'][$campo];
    }

    public function validaReCaptcha($response)
    {
        if (get_option('login_nocaptcha_secret')) {
            $remoteip = $_SERVER["REMOTE_ADDR"];
            $secret = get_option('login_nocaptcha_secret');
            //echo "secret $secret";
            $payload = array('secret' => $secret, 'response' => $response, 'remoteip' => $remoteip);

            $result = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array('body' => $payload));
            if (is_wp_error('WP_Error')) { // disable SSL verification for older cURL clients
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $result = curl_exec($ch);
                $g_response = json_decode($result);
            } else {
                $g_response = json_decode($result['body']);
            }
            if ($g_response->success) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function setCadastro($p = [])
    {
        $objConfig = new Config();

        if ($objConfig->verifyRcScore()['status'] == 'FAIL' && !$this->getUser('id')) {
            return [
                'msg' => 'Não autorizado',
                'msg_error' => 'Não autorizado',
                'url' => '/',
                'status' => 'FAIL'
            ];
        }

        $datanasc = explode('/', $p['datanasc']);
        $idade = date('Y') - $datanasc[2];
        $status = 'FAIL';
        $p['tipo_login'] = 'EMAIL';
        $p['nome'] = $this->_api->trataEspacosBranco($p['nome']);
        $nomeCompleto = explode(" ", $p['nome']);

        if ($p['tipo_login'] == 'FACEBOOK' || $p['tipo_login'] == 'EMAIL') {
            //Valida recapcha
            /*
            if (!$this->validaReCaptcha($p['g-recaptcha-response'])) {
                $msg_erro = "Validação do captcha incorreta.";
            }*/
            //Valida campos obrigatórios
            if (!$p['nome'] || !$p['doc_principal'] || !$p['documento_num'] || !$p['celular'] || !$p['datanasc'] || !$p['cep'] || !$p['endereco'] || !$p['bairro'] || !$p['estado'] || !$p['cidade'] || !$p['email']) {
                $msg_erro = "Por favor, preencha todos os campos com asterísco (*).";
            }
            //Nome completo
            else if (count($nomeCompleto) <= 1) {
                $msg_erro = "Por favor, informe seu nome completo!";
            }
            //aceite
            else if ($p['aceite'] != 'S' && !$this->isLogado()) {
                $msg_erro = "Você não confirmou o aceite nas políticas de uso do site.";
            }
            //se nao aceita menor de idade
            else if ($this->_config->_cadastro_menor == 'N' && $idade < 18) {
                $msg_erro = "Não será permitido acesso aos menores de 18 (dezoito) anos sem o consentimento expresso de seus pais, tutores ou representantes legais. Maiores informações, por favor, entre em contato conosco.";
            }
            //Se o tipo de autenticação for email, confirma a senha digitada
            else if ($p['tipo_login'] == 'EMAIL' && ($p['senha'] != $p['csenha'] || !$p['senha'] || !$p['csenha'])) {
                $msg_erro = "As senhas não conferem, por favor, tente novamente.";
            }
            //Verifica o email
            else if ($this->verifyEmail($p['email'], $this->getUser('id')) == 'FAIL') {
                $msg_erro = "Esse e-mail já está em uso por outro cadastro, você pode utilizar o recurso <b>Esqueci Minha Senha</b> para recuperá-lo.";
            }
            //tudo certo, envia para cadastrar
            else {
                $cadastro = [
                    'name' => $p['nome'],
                    'type_doc' => $p['doc_principal'],
                    'number_doc' => $p['documento_num'],
                    'cell' => $p['celular'],
                    'birth_date' => $this->datagringa($p['datanasc']),
                    'zip_code' => $p['cep'],
                    'address' => $p['endereco'],
                    'address_number' => $p['num'],
                    'complement' => $p['complemento'],
                    'district' => $p['bairro'],
                    'state' => $p['estado'],
                    'city' => $p['cidade'],
                    'email' => $p['email'],
                    'password' => $p['senha'],
                    'accepted_news' => ($p['recebe_news'] == 'S') ? 'S' : 'N',
                    'tokenFt' => $_SESSION['tokenPost']
                ];
                if ($this->isLogado()) {
                    $cadastro['id'] = $this->getUser('id');
                    $this->_api->postJson($cadastro, 'site/up-client');
                } else {
                    $this->_api->postJson($cadastro, 'site/add-client');
                }
                $res = $this->_api->ret;
                //print_r($res);
                if ($res['id']) {
                    $status = 'OK';
                    $p['id'] = $res['id'];
                    $p['cidade_nome'] = $res['city_name'];
                    $p['estado_nome'] = $res['state_name'];
                    if ($this->getUrlCallback()) {
                        $url = site_url($this->getUrlCallback());
                        $this->zeraUrlCallback();
                    } else {
                        $url = ($this->isLogado()) ? get_site_url() . "/cadastro" : get_site_url();
                    }
                    $msg = ($this->isLogado()) ? "Cadastro atualizado com sucesso!" : "Parabéns, seu cadastro foi realizado com sucesso!";
                    $this->setAuth($p);
                } else {
                    $msg_erro = $res['msg'];
                }
            }
        } else {
            $msg_erro = "Formulário não reconhecido.";
        }

        return [
            'msg' => $msg,
            'msg_error' => $msg_erro,
            'url' => $url,
            'status' => $status,
        ];
    }

    public function excluirContaUser($pass = '')
    {
        if ($pass) {
            $user = [
                'senha_auth' => md5($pass),
                'id' => $this->getUser('id'),
            ];
            $this->post($user, '/site/excluir-conta-user');
            $ret = $this->ret;
        } else {
            $ret = ['status' => 'FAIL', 'msg' => 'Por favor, informe a senha.'];
        }
        $ret['url'] = get_site_url() . '/?sair=S';
        return $ret;
    }

    public function get($post = [])
    {
        if ($post) {
            if ($this->isLogado()) {
                $post['id'] = $this->getUser('id');
            }
            return $post;
        } else if ($this->isLogado()) {
            return $this->getDadosUser();
        }
    }

    public function setUrlCallback($url)
    {
        $_SESSION['url_callback'] = $url;
    }

    public function getUrlCallback()
    {
        return $_SESSION['url_callback'];
    }

    public function zeraUrlCallback()
    {
        unset($_SESSION['url_callback']);
    }

    public function isLogado()
    {
        if ($_SESSION['auth']['id']) {
            return true;
        } else {
            return false;
        }
    }

    public function upAuth($p)
    {
        if (count($p)) {
            $p['recebe_news'] = ($p['recebe_news'] == 'S') ? 'S' : 'N';
            foreach ($p as $c => $v) {
                $_SESSION['auth'][$c] = $v;
            }
        }
    }

    public function setAuth($d = [])
    {
        if ($d['id']) {
            $_SESSION['auth'] = [
                'id' => $d['id'],
                'nome' => $d['nome'],
                'primeiro_nome' => explode(" ", $d['nome'])[0],
                'email' => $d['email'],
                'avatar' => $d['avatar_url'],
                'id_fb' => $d['facebook_id'],
                'documento_num' => $d['documento_num'],
                'doc_principal' => $d['doc_principal'],
                'sexo' => $d['sexo'],
                'datanasc' => $d['datanasc'], //d/m/Y
                'rg' => $d['rg'],
                'orgaoexp' => $d['orgaoexp'],
                'cep' => $d['cep'],
                'endereco' => $d['endereco'],
                'num' => $d['num'],
                'complemento' => $d['complemento'],
                'bairro' => $d['bairro'],
                'estado' => $d['estado'],
                'cidade' => $d['cidade'],
                'uf' => $d['estado'],
                'cidade_nome' => $d['cidade_nome'],
                'estado_nome' => $d['estado_nome'],
                'celular' => $d['celular'],
                'tel' => $d['tel'],
                'email' => $d['email'],
                'recebe_news' => $d['recebe_news'],
                'campo_extra' => $d['campo_extra'],
            ];
        }
    }

    public function desconecta()
    {
        $objPedido = new Pedidos();
        $objPedido->zeraCarrinho();
        unset($_SESSION['auth']);
    }

    public function getDadosUser()
    {
        return $_SESSION['auth'];
    }

/**
 * 
 */
    public function requestRemoveAccount()
    {
        $post = [
            'tokenFt' => $_SESSION['tokenPost'],
            'url' => get_site_url() . '/confirmacao-de-exclusao-de-conta'
        ];
        $this->_api->postJson($post, "site/confirm-remove-account/" . $this->getUser('id'));
        return ["msg" => $this->_api->ret['msg']];
    }

/**
 * Exibe mensagem de confirmação de exclusão de conta
 * @param string $
 */
    public function showConfirmRemove($args = [])
    {

        global $wp_query;
        $this->_args = $args;
        $hash = $wp_query->query_vars['id'];
        if ($hash) {

            $this->_api->get("site/remove-account/" . $hash);
            if ($this->_api->ret['status'] == 'OK') {
                $this->desconecta();
                $dados = [
                    'msg' => 'Sua conta foi excluída com sucesso.',
                    'class' => 'success',
                ];
            } else {

                $dados = [
                    'msg' => $this->_api->ret['msg'],
                    'class' => 'danger',
                ];

            }


        } else {

            $dados = [
                'msg' => 'Dados insuficientes.',
                'class' => 'warning',
            ];
        }


        $this->renderTemplate('client-confirm-remove', $dados);
    }
}
