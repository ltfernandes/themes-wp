<?php

namespace PluginFrameticket\Control;

use PluginFrameticket\Core\Controller;
use PluginFrameticket\Middleware\Plugin;

class Config extends Controller
{

    public $_apiKey;
    public $_apiUrl;
    public $_fb_id;
    public $_fb_secret;
    public $_rc_secret_key;
    public $_rc_public_key;
    public $_cadastro_menor;
    public $_desativar_cupom;
    public $_sem_conta;
    public $_cadastro_simplificado;
    public $_fontes_externas;
    public $_bootstrap;
    public $_categorias = [];

    public function __construct()
    {
        $this->set();
    }

    public function addUp($post = [])
    {
        if (count($post)) {
            update_option('FRAMETICKET_CADASTRO_MENORES', $post['cadastro_menor']);
            update_option('FRAMETICKET_DESATIVAR_CUPOM', $post['desativar_cupom']);
            update_option('FRAMETICKET_SEM_CONTA', $post['sem_conta']);
            update_option('FRAMETICKET_CADASTRO_SIMPLIFICADO', $post['cadastro_simplificado']);
            update_option('FRAMETICKET_FONTES_EXTERNAS', $post['fontes_externas']);
            if ($post['apiKeyFt']) {
                update_option('FRAMETICKET_API_KEY', $post['apiKeyFt']);
            }
            update_option('FRAMETICKET_VENDAS', $post['vendas_off']);
            update_option('FRAMETICKET_VENDAS_CARRINHO', $post['vendas_carrinho']);

            if ($post['apiKeyFtOrg']) {
                update_option('FRAMETICKET_API_KEY_ORG', $post['apiKeyFtOrg']);
            }

            update_option('FRAMETICKET_API_URL', $post['apiUrlFt']);
            update_option('FRAMETICKET_GTAG', $post['ganalytics_id']);
            update_option('FRAMETICKET_FB_PIXEL', $post['fb_pixel_id']);
            update_option('FRAMETICKET_FB_ID', $post['fb_id']);
            update_option('FRAMETICKET_FB_SECRET', $post['fb_secret']);
            update_option('FRAMETICKET_RC_PUBLIC_KEY', $post['rc_public_key']);
            update_option('FRAMETICKET_RC_SECRET_KEY', $post['rc_secret_key']);
            update_option('FRAMETICKET_CATEGORIAS', json_encode($post['categorias']));
            update_option('FRAMETICKET_BOOTSTRAP', $post['bootstrap']);
            $this->set();
        }
    }

    public function set()
    {
        $this->_apiKey = get_option('FRAMETICKET_API_KEY');
        $this->_apiKeyOrg = get_option('FRAMETICKET_API_KEY_ORG');
        $this->_vendas_off = get_option('FRAMETICKET_VENDAS');
        $this->_vendas_carrinho = get_option('FRAMETICKET_VENDAS_CARRINHO');
        $this->_cadastro_menor = get_option('FRAMETICKET_CADASTRO_MENORES');
        $this->_desativar_cupom = get_option('FRAMETICKET_DESATIVAR_CUPOM');
        $this->_sem_conta = get_option('FRAMETICKET_SEM_CONTA');
        $this->_cadastro_simplificado = get_option('FRAMETICKET_CADASTRO_SIMPLIFICADO');
        $this->_fontes_externas = get_option('FRAMETICKET_FONTES_EXTERNAS');
        $this->_apiUrl = get_option('FRAMETICKET_API_URL');
        $this->_categorias = json_decode(get_option('FRAMETICKET_CATEGORIAS'), true);
        $this->_fb_id = get_option('FRAMETICKET_FB_ID');
        $this->_fb_secret = get_option('FRAMETICKET_FB_SECRET');
        $this->_bootstrap = get_option('FRAMETICKET_BOOTSTRAP');
        $this->_gtag = get_option('FRAMETICKET_GTAG');
        $this->_fb_pixel = get_option('FRAMETICKET_FB_PIXEL');
        $this->_rc_secret_key = get_option('FRAMETICKET_RC_SECRET_KEY');
        $this->_rc_public_key = get_option('FRAMETICKET_RC_PUBLIC_KEY');
    }

    public function showForm()
    {
        $this->addUp($_POST);
        $objPlugin = new Plugin();
        $dados = [
            'cadastro_menor' => $this->_cadastro_menor,
            'sem_conta' => $this->_sem_conta,
            'desativar_cupom' => $this->_desativar_cupom,
            'cadastro_simplificado' => $this->_cadastro_simplificado,
            'fontes_externas' => $this->_fontes_externas,
            'apiKeyFt' => $this->_apiKey,
            'vendas_off' => $this->_vendas_off,
            'vendas_carrinho' => $this->_vendas_carrinho,
            'apiKeyFtOrg' => $this->_apiKeyOrg,
            'apiUrlFt' => $this->_apiUrl,
            'fb_id' => $this->_fb_id,
            'fb_secret' => $this->_fb_secret,
            'categorias' => $this->_categorias,
            'bootstrap' => $this->_bootstrap,
            'ganalytics_id' => $this->_gtag,
            'fb_pixel_id' => $this->_fb_pixel,
            'rc_secret_key' => $this->_rc_secret_key,
            'rc_public_key' => $this->_rc_public_key,
            'dir_theme' => get_template_directory(),
            'shortcodes' => $objPlugin->getShortCodes(),
        ];
        return $this->render('form-completo', $dados);
    }

    public function addPagesDefault()
    {
        $pagesOptions = [
            [
                'title' => 'Home - Eventos',
                'slug' => 'home-eventos',
                'content' => '',
                'template' => 'home-padrao-eventos.php'
            ],
            [
                'title' => 'Home - Unidades',
                'slug' => 'home-unidades',
                'content' => '',
                'template' => 'home-padrao.php'
            ],
            [
                'title' => 'Cadastro',
                'slug' => 'cadastro',
                'content' => '[cadastro template_custom=""]',
                'template' => 'interna-padrao-semtitulo.php'
            ],
            [
                'title' => 'Agente',
                'slug' => 'agente',
                'content' => '[page_qrcode template_custom=""]',
                'template' => 'interna-padrao-full.php'
            ],
            [
                'title' => 'Busca',
                'slug' => 'busca',
                'content' => '[resultado_busca_eventos template_custom=""]',
                'template' => 'interna-padrao-full.php'
            ],
            [
                'title' => 'Carrinho',
                'slug' => 'carrinho',
                'content' => '[carrinho show_evento_titulo="" template_custom=""]',
                'template' => 'interna-padrao-full.php'
            ],
            [
                'title' => 'Categoria',
                'slug' => 'categoria',
                'content' => '[eventos_categorias template_custom=""]',
                'template' => 'interna-padrao-semtitulo.php'
            ],
            [
                'title' => 'Pagamento',
                'slug' => 'pagamento',
                'content' => '[caixa template_custom=""]',
                'template' => 'interna-padrao-semtitulo.php'
            ],
            [
                'title' => 'Página do Evento',
                'slug' => 'event',
                'content' => '',
                'template' => 'page-event.php'
            ],
            [
                'title' => 'Página da Unidade',
                'slug' => 'unit',
                'content' => '[unidade-negocios force_id="" force_data_visita="" force_horario_visita="" force_calendario="" template_custom=""]',
                'template' => 'interna-padrao-semtitulo.php'
            ],
            [
                'title' => 'Pedidos',
                'slug' => 'pedidos',
                'content' => '[pedidos template_custom=""]',
                'template' => 'interna-padrao-full.php'
            ],
            [
                'title' => 'Pedido',
                'slug' => 'pedido',
                'content' => '[pedido template_custom=""]',
                'template' => 'interna-padrao-full.php'
            ],
            [
                'title' => 'Esqueci minha Senha',
                'slug' => 'esqueci',
                'content' => '[esqueci template_custom=""]',
                'template' => 'interna-padrao-full.php'
            ],
            [
                'title' => 'Redefinir Senha',
                'slug' => 'recuperar-senha',
                'content' => '[redefinir-senha template_custom=""]',
                'template' => 'interna-padrao-full.php'
            ]
        ];
        $existingPages = $this->getPagesSlug();

        foreach ($pagesOptions as $key => $value) {
            $slug = $pagesOptions[$key]['slug'];
            $pagesId = null;

            if (in_array($slug, $existingPages)) {
                $pagesOptions[$key]['status'] = 0;
            } else {
                $pagesId = wp_insert_post(array(
                    'post_title' => $pagesOptions[$key]['title'],
                    'post_type' => 'page',
                    'post_name' => $pagesOptions[$key]['slug'],
                    'post_content' => $pagesOptions[$key]['content'],
                    'post_status' => 'publish',
                    'page_template' => $pagesOptions[$key]['template']
                ));
                $pagesOptions[$key]['status'] = $pagesId ? 1 : 0;
            }
        }
        $this->setPermalinkPostname();
        return $this->getNewPagesMessage($pagesOptions);
    }

    private function getPagesSlug()
    {
        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish'
        );
        $pages = get_pages($args);
        $pagesSlug = array();

        foreach ($pages as $page) {
            $pagesSlug[] = $page->post_name;
        }
        return $pagesSlug;
    }

    private function getNewPagesMessage($pagesOptions)
    {
        $pgCreated = $pgError = 0;
        $slugPgError = '';

        foreach ($pagesOptions as $pgOption) {

            if ($pgOption['status']) {
                $pgCreated++;
            } else {
                $pgError++;
                $slugPgError .= $pgOption['slug'] . ' / ';
            }
        }

        if ($pgError) {
            $slugPgError = 'As seguintes páginas não foram criadas pois o endereço ja existe: ' . rtrim($slugPgError, "/ ");
        }
        $msg = "Páginas criadas com sucesso (" . $pgCreated . " páginas)! " . $slugPgError;
        return $msg;
    }

    private function setPermalinkPostname()
    {
        $permalinkStructure = '/%postname%/';
        if (get_option('permalink_structure') != $permalinkStructure) {
            global $wp_rewrite;
            $wp_rewrite->set_permalink_structure($permalinkStructure);
        }
    }

    public function getIp()
    {
        if (!$_SERVER['HTTP_X_REAL_IP']) {
            return getenv('REMOTE_ADDR');
        } else {
            return $_SERVER['HTTP_X_REAL_IP'];
        }
    }

    function verifyCaptcha($captcha = '')
    {
        $ip = $this->getIP();

        $score = 0.1;

        if (isset($captcha)) {
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $this->_rc_secret_key . "&response=" . $captcha . "&remoteip=" . $ip);
            $response = json_decode($response);
            if ($response->success == true) {
                $score = $response->score;
            }
        }

        $this->setRcScore($ip, $score);
        return $this->verifyRcScore();
    }

    function setRcScore($ip, $score = 0)
    {
        //Registra o score para analise:
        $key = 'FRAMETICKET_RC_SCORE_' . $ip;
        $value = $ip . '|' . $score . '|' . date('Y-m-d H:i:s');
        update_option($key, $value);
    }

    function verifyRcScore()
    {
        $ip = $this->getIP();
        $data = get_option('FRAMETICKET_RC_SCORE_' . $ip);
        $score = explode('|', $data)[1];

        if ($this->_rc_secret_key && $score < 0.7) {
            return [
                'status' =>  'FAIL'
            ];
        }

        return [
            'status' =>  'OK'
        ];
    }
}
