<?php
namespace PluginFrameticket\Middleware;

class WidgetLogin extends \WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'WidgetLogin',
            __('[' . BRUXO_SIGLA . '] Login'),
            ['description' => 'Exibe o formulário de login.', 'panels_title' => false]
        );
    }

    public function form($instance)
    {
        echo "Formulário de cadastro de clientes";
    }

    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }

    public function widget($args, $instance)
    {
        echo "formulario de login";
    }

}
