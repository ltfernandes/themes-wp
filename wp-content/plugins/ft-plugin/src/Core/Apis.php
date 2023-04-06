<?php

namespace PluginFrameticket\Core;

use PluginFrameticket\Core\Controller;

class Apis extends Controller
{
    public $_apiUrl;
    public $header;
    public $ret;
    public $puro;
    public $httpCode;
    public $rota;
    public $login;
    public $pass;

    public function __construct($url = '')
    {
        $this->_apiUrl = $url;
        $this->header = [];
        $this->ret = [];
        $this->puro = "";
        $this->httpCode = "";
        $this->rota = "";
        $this->login = "";
        $this->pass = "";
    }

    public function getIp()
    {
        if (!$_SERVER['HTTP_X_REAL_IP']) {
            return getenv('REMOTE_ADDR');
        } else {
            return $_SERVER['HTTP_X_REAL_IP'];
        }
    }

    public function validaPost($post = [])
    {
        if ($post['tokenFt'] == $_SESSION['tokenPost'] && $_SESSION['tokenPost']) {
            return true;
        } else {
            return false;
        }
    }

    public function trataEspacosBranco($string = "")
    {
        $string = trim($string);
        if ($string) {
            $string = preg_replace('/\s+/', ' ', $string);
        }
        return $string;
    }

    public function buscaEndereco($cep = "")
    {
        if ($cep) {
            $objApi = new Apis('https://viacep.com.br');
            $cep = $this->eliminaSimbolos($cep);
            $objApi->setHeader();
            $objApi->get('/ws/' . $cep . '/json/');
            return $objApi->ret;
        }
    }

    /**
     * Define a header da requição
     *
     * @param array $paramns ['contentType'=>string,'charset'=>string,adicionais=>[]]
     * @return void
     */
    public function setHeader($paramns = [])
    {
        $this->header = [
            '/' . BRUXO_NAME . '-api/1.0/service.cgi HTTP/1.1',
            'Content-Type: application/' . (($paramns['contentType']) ? $paramns['contentType'] : 'text/html') . '; charset=' . (($paramns['charset']) ? $paramns['charset'] : 'utf-8'),
            'Accept: application/' . (($paramns['contentType']) ? $paramns['contentType'] : 'text/html'),
        ];
        if ($paramns['adicionais']) {
            foreach ($paramns['adicionais'] as $chave => $valor) {
                $this->header[$chave] = $valor;
            }
        }
    }

    public function setBasicAuth($login = '', $senha = '')
    {
        $this->login = $login;
        $this->pass = $senha;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function post($post, $uri)
    {
        if ($this->validaPost($post)) {
            $post_string = http_build_query($post);
            $curlRequest = curl_init($this->_apiUrl . '/' . $uri);
            curl_setopt($curlRequest, CURLOPT_HTTPHEADER, $this->getHeader());
            curl_setopt($curlRequest, CURLOPT_POST, 1);
            curl_setopt($curlRequest, CURLOPT_POSTFIELDS, $post_string);
            curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, 1);
            //Autenticação basica
            if ($this->login) {
                curl_setopt($curlRequest, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($curlRequest, CURLOPT_USERPWD, $this->login . ":" . $this->pass);
            }
            $responseData = curl_exec($curlRequest);
            $httpCode = curl_getinfo($curlRequest, CURLINFO_HTTP_CODE);
            curl_close($curlRequest);
            $this->ret = json_decode($responseData, true);
            $this->puro = $responseData;
            $this->httpCode = $httpCode;
            $this->rota = $this->_apiUrl . '/' . $uri;
        } else {
            return ['status' => 'FAIL', 'msg' => 'Token inválido'];
        }
    }

    public function postJson($post, $uri)
    {
        if ($this->validaPost($post)) {
            $json = json_encode($post);
            $curlRequest = curl_init($this->_apiUrl . '/' . $uri);
            curl_setopt($curlRequest, CURLOPT_POST, 1);
            curl_setopt($curlRequest, CURLOPT_HTTPHEADER, $this->getHeader());
            curl_setopt($curlRequest, CURLOPT_POSTFIELDS, $json);
            curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, 1);
            //Autenticação basica
            
            if ($this->login) {
                curl_setopt($curlRequest, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($curlRequest, CURLOPT_USERPWD, $this->login . ":" . $this->pass);
            }
            $responseData = curl_exec($curlRequest);
            $httpCode = curl_getinfo($curlRequest, CURLINFO_HTTP_CODE);
            curl_close($curlRequest);
            $this->ret = json_decode($responseData, true);
            $this->puro = $responseData;
            $this->httpCode = $httpCode;
            $this->rota = $this->_apiUrl . '/' . $uri;
        } else {
            return ['status' => 'FAIL', 'msg' => 'Token inválido'];
        }
    }

    public function get($uri)
    {
        $rota = $this->_apiUrl . '/' . $uri;
        $curlRequest = curl_init($rota);
        curl_setopt($curlRequest, CURLOPT_HTTPHEADER, $this->getHeader());
        curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, 1);
        //Autenticação basica
        if ($this->login) {
            curl_setopt($curlRequest, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curlRequest, CURLOPT_USERPWD, $this->login . ":" . $this->pass);
        }
        $responseData = curl_exec($curlRequest);

        $httpCode = curl_getinfo($curlRequest, CURLINFO_HTTP_CODE);
        curl_close($curlRequest);
        $this->ret = json_decode($responseData, true);
        $this->puro = $responseData;
        $this->httpCode = $httpCode;
        $this->rota = $rota;
    }

    public function hash($txt)
    {
        return md5(md5(md5($txt)));
    }
}
