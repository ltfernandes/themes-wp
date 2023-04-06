<?php
namespace PluginFrameticket\Middleware;

use PluginFrameticket\Control\Clientes;

class WidgetCadastro extends \WP_Widget
{

    public $_cliente;

    public function __construct()
    {
        parent::__construct(
            'WidgetCadastro', 
            __('[' . BRUXO_SIGLA . '] Cadastro de Cliente'), 
            ['description' => "Exibe o formulário de cadastro de cliente."]
        );
        $this->_cliente = new Clientes();

    }

    public function form($instance)
    {
        echo "Formulário de cadastro para clientes";
    }

    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }

    public function widget($args, $instance)
    {
        $this->_cliente->showForm();
    }

}
