<?php

namespace PluginFrameticket\Middleware;

use PluginFrameticket\Control\Config;
use PluginFrameticket\Control\Eventos;
use PluginFrameticket\Core\Controller;

class Plugin extends Controller
{

    public function __construct()
    {
        $this->_url = plugin_dir_url(__FILE__);

    }
    /**
    * Registra todas as widgets do plugin
    *
    * @return void
    */
    public function registraWidgets()
    {
        //Defina as widgets do sistema:
        register_widget('\PluginFrameticket\Middleware\WidgetCadastro');
        register_widget('\PluginFrameticket\Middleware\WidgetLogin');
    }

    /**
    * Metodo para configurar todas as shortcodes disponíveis para o site
    * O indice do array deve ser a nomenclatura da shortcode
    * @return array
    */
    public function getShortCodes(): array
    {
        return [
            'cadastro' => [
                'class' => 'PluginFrameticket\Control\Clientes',
                'method' => 'showForm',
                'attributes' => 'template_custom=""',
                'description' => 'Formulário de cadastro de clientes para inserção ou atualização.'
            ],
            'login_cadastro' => [
                'class' => 'PluginFrameticket\Control\Clientes',
                'method' => 'showLoginCad',
                'attributes' => 'template_custom=""',
                'description' => 'Formulário de login no momento do pagamento.'
            ],
            'login' => [
                'class' => 'PluginFrameticket\Control\Clientes',
                'method' => 'showLogin',
                'attributes' => 'template_custom=""',
                'description' => 'Formulário de login.'
            ],
            'esqueci' => [
                'class' => 'PluginFrameticket\Control\Clientes',
                'method' => 'showFormEsqueciSenha',
                'attributes' => 'template_custom=""',
                'description' => 'Formulário para solicitar redefinição de senha.'
            ],
            'redefinir-senha' => [
                'class' => 'PluginFrameticket\Control\Clientes',
                'method' => 'showFormRedefinirSenha',
                'attributes' => 'template_custom=""',
                'description' => 'Formulário para redefinir a senha após clicar no link de redefinição enviado por e-mail ao cliente.'
            ],
            'caixa' => [
                'class' => 'PluginFrameticket\Control\Pedidos',
                'method' => 'showCaixa',
                'attributes' => 'template_custom=""',
                'description' => 'Tela do caixa para pagamento do pedido.'
            ],
            'pedidos' => [
                'class' => 'PluginFrameticket\Control\Pedidos',
                'method' => 'showPedidos',
                'attributes' => 'template_custom=""',
                'description' => 'Lista os pedidos do cliente'
            ],
            'pedido' => [
                'class' => 'PluginFrameticket\Control\Pedidos',
                'method' => 'showPedido',
                'attributes' => 'template_custom=""',
                'description' => 'Exibe o pedido do cliente'
            ],
            'botoes-minha-conta' => [
                'class' => 'PluginFrameticket\Control\Clientes',
                'method' => 'showMenuMinhaConta',
                'attributes' => 'mobile="N" template_custom=""',
                'description' => 'Inclui o menu padrão com botões de acesso a conta e carrinho de compras.'
            ],
            'eventos_destaques' => [
                'class' => 'PluginFrameticket\Control\Eventos',
                'method' => 'showDestaques',
                'attributes' => 'ordem="rand" template_custom=""',
                'description' => 'Lista os eventos ou unidades de negócios que estão em destaque.'
            ],
            'eventos_categorias' => [
                'class' => 'PluginFrameticket\Control\Eventos',
                'method' => 'showEventosCategorias',
                'attributes' => 'template_custom=""',
                'description' => 'Lista os eventos ou unidades de negócios conforme a categoria selecionada.'
            ],
            'eventos_slider' => [
                'class' => 'PluginFrameticket\Control\Eventos',
                'method' => 'showSlider',
                'attributes' => 'ordem="rand" limite="4" template_custom=""',
                'description' => 'Exibe o slider com eventos ou unidades.'
            ],
            'unidade-negocios' => [
                'class' => 'PluginFrameticket\Control\Eventos',
                'method' => 'showUnidadeNegocios',
                'attributes' => 'force_id="" force_data_visita="" force_horario_visita="" force_calendario="" template_custom=""',
                'description' => 'Exibe a tela da unidade de negócios selecionada.'
            ],
            'evento' => [
                'class' => 'PluginFrameticket\Control\Eventos',
                'method' => 'showEvento',
                'attributes' => 'force_id="" template_custom=""',
                'description' => 'Exibe a tela do evento selecionado.'
            ],
            'categorias_lista' => [
                'class' => 'PluginFrameticket\Control\Eventos',
                'method' => 'showCategoriasLista',
                'attributes' => 'template_custom=""',
                'description' => 'Lista o menu com as categorias de eventos ou unidade de negócios.'
            ],
            'form_busca_eventos' => [
                'class' => 'PluginFrameticket\Control\Eventos',
                'method' => 'showFormBusca',
                'attributes' => 'template_custom=""',
                'description' => 'Exibe o formulário de busca por eventos ou unidade de negócios.'
            ],
            'resultado_busca_eventos' => [
                'class' => 'PluginFrameticket\Control\Eventos',
                'method' => 'showResultBusca',
                'attributes' => 'template_custom=""',
                'description' => 'Exibe a tela de resutando da busca por eventos ou unidade de negócios.'
            ],
            'carrinho' => [
                'class' => 'PluginFrameticket\Control\Pedidos',
                'method' => 'showCarrinho',
                'attributes' => 'show_evento_titulo="" template_custom=""',
                'description' => 'Exibe o carrinho de compras.'
            ],
            'capacidade' => [
                'class' => 'PluginFrameticket\Control\Eventos',
                'method' => 'showCapacidade',
                'attributes' => 'data="" template_custom=""',
                'description' => 'Exibe uma tabela com os totais de ingressos por eventos/unidades. Se não informada uma data, retorna referente a data atual. Formato da data aaaa-mm-dd'
            ],
            'page_qrcode' => [
                'class' => 'PluginFrameticket\Control\Eventos',
                'method' => 'showEventosQRcode',
                'attributes' => 'template_custom="" force_calendario=""',
                'description' => 'Lista os eventos ou unidades de negócios vínculados a um QRCode.'
            ],
            'listas' => [
                'class' => 'PluginFrameticket\Control\Listas',
                'method' => 'showEventos',
                'attributes' => 'force_id_org=""',
                'description' => 'Exibe todos os eventos do organizador que possuí a configuração de listas para integração.'
            ],
            'form_lista' => [
                'class' => 'PluginFrameticket\Control\Listas',
                'method' => 'showForm',
                'attributes' => 'force_id_event="" type="EVENTO/UNIDADE"',
                'description' => 'Exibe o formulário para inserir os nomes na lista configurada para o evento.'
            ],
            'msg_remover_conta' => [
                'class' => 'PluginFrameticket\Control\Clientes',
                'method' => 'showConfirmRemove',
                'attributes' => '',
                'description' => 'Exibe mensagem de confirmação de exclusão de conta. É preciso criar uma página com o link permanente <b>/confirmacao-de-exclusao-de-conta</b>'
            ],

        ];
    }

    /**
    * Registra as shortcodes no site:
    *
    * @return void
    */
    public function addShortCodes()
    {
        foreach ($this->getShortCodes() as $shortcode => $params) {
            add_shortcode($shortcode, [new $params['class'], $params['method']]);
        }
    }

    public function addMenu()
    {
        //Defina os itens do menu dentro do WP:
        $objConfig = new Config();

        $icone = BRUXO_URL . '/icon.png';
        add_menu_page(BRUXO_LABEL, BRUXO_LABEL, 'manage_options', 'mn-' . BRUXO_SIGLA, [$objConfig, 'showForm'], $icone, '');
        add_submenu_page(null, 'Configurações', 'Configurações', 'read', 'config-edit', [$objConfig, 'showForm']);
    }

    public function showInfoPlugin()
    {
        echo 'Tudo sobre o Plugin';
    }

    /**
    * Scripts e CSS para incluir no site
    *
    * @return void
    */
    public function scriptsPublic()
    {

        $vs = '6.3.200';

        $objConfig = new Config();


        wp_register_script(BRUXO_NAME . '-utils', BRUXO_URL . '/public/js/utils.js', ['jquery'], $vs);
        wp_enqueue_script(BRUXO_NAME . '-utils');

        wp_register_script(BRUXO_NAME . '-scripts', BRUXO_URL . '/public/js/scripts.js', ['jquery'], $vs);
        wp_enqueue_script(BRUXO_NAME . '-scripts');

        wp_register_script(BRUXO_NAME . '-pagamento', BRUXO_URL . '/public/js/pagamento.js', ['jquery', BRUXO_NAME . '-sajax'], $vs);
        wp_enqueue_script(BRUXO_NAME . '-pagamento');

        wp_enqueue_style(BRUXO_NAME . '-styles', BRUXO_URL . '/public/css/styles.css', [], $vs, 'all');

        wp_register_script(BRUXO_NAME . '-sajax', BRUXO_URL . '/public/js/sajax.js.php', [], $vs);
        wp_enqueue_script(BRUXO_NAME . '-sajax');

        wp_register_script('frameticket-mktplace-ga', BRUXO_URL . '/public/js/ft-ga.js', ['jquery'], $vs);
        wp_enqueue_script('frameticket-mktplace-ga');

        wp_register_script('frameticket-mktplace-fb_pixel', BRUXO_URL . '/public/js/ft-fb_pixel.js', ['jquery'], $vs);
        wp_enqueue_script('frameticket-mktplace-fb_pixel');

        //Libs:
        wp_enqueue_style('fontawesome', 'https://use.fontawesome.com/releases/v5.14.0/css/all.css', [], $vs, 'all');

        wp_enqueue_style('fancybox', BRUXO_URL . '/libs/public/fancybox-2.1.5/source/jquery.fancybox.css', [], $vs, 'all');
        wp_register_script('fancybox', BRUXO_URL . '/libs/public/fancybox-2.1.5/source/jquery.fancybox.js', ['jquery'], $vs);
        wp_enqueue_script('fancybox');

        if (get_option('login_nocaptcha_key')) {
            wp_register_script('recaptcha', 'https://www.google.com/recaptcha/api.js', ['jquery'], $vs);
            wp_enqueue_script('recaptcha');
        }

        //wp_enqueue_style('jquery-ui-style', BRUXO_URL . '/libs/public/jquery-ui-1.12.1/jquery-ui.min.css', [], $vs, 'all');
        //wp_enqueue_script('jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', ['jquery'], $vs, 'all');

        wp_enqueue_script('bootbox', BRUXO_URL . '/libs/public/bootbox/bootbox.min.js', ['jquery'], $vs, 'all');

        wp_register_script('maskedinput', BRUXO_URL . '/libs/public/jquery.maskedinput.js', ['jquery'], $vs);
        wp_enqueue_script('maskedinput');

        wp_register_script('maskMoney', BRUXO_URL . '/libs/public/jquery.maskMoney.min.js', ['jquery'], $vs);
        wp_enqueue_script('maskMoney');
        
        wp_register_script('moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js', ['jquery'], $vs);
        wp_enqueue_script('moment');
        
        wp_register_script('moment-locale', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js', ['jquery', 'moment'], $vs);
        wp_enqueue_script('moment-locale');
        
        wp_register_script('moment-locale-br', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/pt-br.min.js', ['jquery', 'moment', 'moment-locale'], $vs);
        wp_enqueue_script('moment-locale-br');
    }

    /**
    * Scripts e CSS para incluir no painel
    *
    * @return void
    */
    public function scriptsPrivate()
    {
        $vs = '5.2.100';

        wp_enqueue_style('fontawesome', 'https://use.fontawesome.com/releases/v5.14.0/css/all.css', [], $vs, 'all');

        wp_enqueue_style('bootstrap', BRUXO_URL . '/libs/public/bootstrap-4.5.2/dist/css/bootstrap.grid.min.css', [], $vs, 'all');

        wp_enqueue_style('bootstrap-grid', BRUXO_URL . '/admin/css/bootstrap-grid.min.css', [], $vs, 'all');

        wp_enqueue_style('style-panel-ft', BRUXO_URL . '/admin/css/styles.css', [], $vs, 'all');

        wp_register_script(BRUXO_NAME . '-sajax', BRUXO_URL . '/admin/js/sajax.js.php', [], $vs);
        wp_enqueue_script(BRUXO_NAME . '-sajax');
    }

    public function rewriteTag()
    {
        //Defina as variaveis passadas pela url do site:
        add_rewrite_tag('%id%', '([^&]+)');
        add_rewrite_tag('%tipo%', '([^&]+)');
        add_rewrite_tag('%s%', '([^&]+)');
        add_rewrite_tag('%id_comissario%', '([^&]+)');
        add_rewrite_tag('%id_org%', '([^&]+)');
        add_rewrite_tag('%slug%', '([^&]+)');
        add_rewrite_tag('%cupom%', '([^&]+)');
        add_rewrite_tag('%org%', '([^&]+)');
    }

    public function customUrl()
    {
        //Defina as urls customizadas do site:
        add_rewrite_rule('^caixa/([^/]*)/([^/]*)/?', 'index.php?pagename=caixa&id=$matches[1]&slug=$matches[2]', 'top');
        add_rewrite_rule('^event/([^/]*)/([^/]*)/?', 'index.php?pagename=event&id=$matches[1]&slug=$matches[2]', 'top');
        add_rewrite_rule('^unit/([^/]*)/([^/]*)/?', 'index.php?pagename=unit&id=$matches[1]&slug=$matches[2]', 'top');
        add_rewrite_rule('^evento-cupom/([^/]*)/([^/]*)/?', 'index.php?pagename=evento-cupom&id=$matches[1]&cupom=$matches[2]', 'top');
        add_rewrite_rule('^eventos-categoria/([^/]*)/([^/]*)/?', 'index.php?pagename=eventos-categoria&id=$matches[1]&slug=$matches[2]', 'top');
        add_rewrite_rule('^categoria/([^/]*)/([^/]*)/?', 'index.php?pagename=categoria&id=$matches[1]&slug=$matches[2]', 'top');
        add_rewrite_rule('^pedido/([^/]*)/?', 'index.php?pagename=pedido&id=$matches[1]', 'top');
        add_rewrite_rule('^recuperar-senha/([^/]*)/?', 'index.php?pagename=recuperar-senha&id=$matches[1]', 'top');
        add_rewrite_rule('^page-evento/([^/]*)/([^/]*)/([^/]*)/?', 'index.php?pagename=page-organizador-evento&org=$matches[1]&id=$matches[2]&slug=$matches[3]', 'top');
        add_rewrite_rule('^page/([^/]*)?', 'index.php?pagename=page-eventos&org=$matches[1]', 'top');
        add_rewrite_rule('^agente/([^/]*)?', 'index.php?pagename=agente&id_comissario=$matches[1]', 'top');
        add_rewrite_rule('^listas/([^/]*)?', 'index.php?pagename=listas&id_org=$matches[1]', 'top');
        add_rewrite_rule('^lista/([^/]*)/([^/]*)/?', 'index.php?pagename=lista&id=$matches[1]&tipo=$matches[2]', 'top');
        add_rewrite_rule('^confirmacao-de-exclusao-de-conta/([^/]*)/?', 'index.php?pagename=confirmacao-de-exclusao-de-conta&id=$matches[1]', 'top');
    }

    /**
    * Ações quando instala o plugin
    *
    * @return void
    */
    public function ativaPlugin()
    {
        return true;
    }

    public function desativaPlugin()
    {
        return true;
    }

    function my_script_loader_tag($tag, $handle)
    {
        $objConfig = new Config();
        if ($handle == 'frameticket-mktplace-ga') {
            return str_replace('<script', '<script gtag="' . $objConfig->_gtag . '"', $tag);
        }
        if ($handle == 'frameticket-mktplace-fb_pixel') {
            return str_replace('<script', '<script FBPixel_ID="' . $objConfig->_fb_pixel . '"', $tag);
        }
        return $tag;
    }

    /**
    * Customiza o título da página
    *
    * @return void
    */
    function custom_document_title()
    {
        $id = get_query_var('id');
        if ($id) {
            $title = get_option('FRAMETICKET_EVENT_' . $id);
            if ($title) {
                return $title . ' - ' . get_bloginfo('name');
            }
        }
    }

    /**
    * Adiciona script Gtag do Google Analytics caso a tag esteja configurada
    *
    * @return void
    */
    function addGoogleAnalyticsGtag()
    {
        $objConfig = new Config();
        if ($objConfig->_gtag) {
            echo "
            <script async src='https://www.googletagmanager.com/gtag/js?id=" . $objConfig->_gtag . "'></script>
            <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '" . $objConfig->_gtag . "');
            </script>
            ";
        }
    }
    /**
    * Adiciona script PIXEL do Facebook caso a tag esteja configurada
    *
    * @return void
    */
    function addPixelFacebook()
    {
        $objConfig = new Config();
        if ($objConfig->_fb_pixel) {
            echo "
            <!-- Facebook Pixel Code -->
                <script>
                  !function(f,b,e,v,n,t,s)
                  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                  n.queue=[];t=b.createElement(e);t.async=!0;
                  t.src=v;s=b.getElementsByTagName(e)[0];
                  s.parentNode.insertBefore(t,s)}(window, document,'script',
                  'https://connect.facebook.net/en_US/fbevents.js');
                  fbq('init', '" . $objConfig->_fb_pixel . "');
                  fbq('track', 'PageView');
                  var FBPixel_ID = '" . $objConfig->_fb_pixel . "';
                </script>
                <noscript>
                  <img height='1' width='1' style='display:none' 
                       src='https://www.facebook.com/tr?id=" . $objConfig->_fb_pixel . "&ev=PageView&noscript=1'/>
                </noscript>
                <!-- End Facebook Pixel Code -->
            ";
        } 
    }



    function frameticketGallery() {
        register_post_type(
            'galeria_frameticket',
            array(
                'labels' => array(
                    'name' => __('Galerias Frameticket'),
                    'singular_name' => __('Galeria Frameticket'),
                ),
                'supports' => array(
                    'title', 'editor'
                ),
                'public' => true,
                'has_archive' => true,
                'menu_icon' => 'dashicons-images-alt2',
                'rewrite' => array('slug' => 'galeria_frameticket'),
            )
        );
    }

    function metaboxEventosGaleria() {
        $objEvento = new Eventos();
        add_meta_box(
            'ft-filters',
            'Adicionar na página:',
            [$objEvento, 'showComboboxEventosGaleria'],
            'galeria_frameticket',
            'side',
            'core'
        );
    }

    function saveGaleriaEvento( $post_id ) {
        if (get_post_type($post_id) == "galeria_frameticket"){
            if ($_POST['galeria_evento'] == 0) {
                delete_post_meta($post_id, "_galeria_evento");
            } elseif ($_POST['galeria_evento']) {
                $my_data = sanitize_text_field( $_POST['galeria_evento'] );
                update_post_meta( $post_id, '_galeria_evento', $my_data );
            }
        }
    }
}
