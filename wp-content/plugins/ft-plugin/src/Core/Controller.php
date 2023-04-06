<?php

namespace PluginFrameticket\Core;

use Timber\Timber;

class Controller
{


    /**
     * Renderiza template para painel adm
     *
     * @param string $template nome do arquivo sem a extensão
     * @param array $dados Dados para serem atribuidos ao template
     * @param string $output Define se entrega o conteudo em html (S) ou se renderiza na tela (N)
     * @return void
     */
    public function render($template, $dados = [], $output = '')
    {
        if ($template) {
            //Define o diretorio do template:
            $dir = BRUXO_DIR . '/src/Views';

            Timber::$locations = [$dir];

            if ($output == 'S') {
                return Timber::fetch($template . '.htm', $dados);
            } else {
                //retorna para variavel -> output
                return Timber::render($template . '.htm', $dados);
            }
        }
    }

    /**
     * Renderiza template para o site
     *
     * @param string $template nome do arquivo sem a extensão
     * @param array $dados Dados para serem atribuidos ao template
     * @param string $output Define se entrega o conteudo em html (S) ou se renderiza na tela (N)
     * @return void
     */
    public function renderTemplate($template, $dados = [], $output = '')
    {
        //Customiza o template caso seja manipulado na shortcode:
        $template = ($this->_args['template_custom']) ? $this->_args['template_custom'] : $template;

        if ($template) {
            //Define os locais onde estão os templates para o tema, com prioridade de busca no tema
            Timber::$locations = [
                get_template_directory() . '/ft-templates-views',
                BRUXO_DIR . '/templates-views'
            ];

            $dados['rc_public_key'] = get_option('FRAMETICKET_RC_PUBLIC_KEY');

            if ($output == 'S') {
                return Timber::fetch($template . '.htm', $dados);
            }

            //retorna para variavel -> output
            return Timber::render($template . '.htm', $dados);
        }
    }

    public function datagringa($data)
    {
        $dt = explode('/', $data);
        return $dt[2] . "-" . $dt[1] . "-" . $dt[0];
    }

    public function dataBR($data = '')
    {
        return ($data) ? date('d/m/Y', strtotime($data)) : "";
    }

    public function eliminaAcento($texto)
    {
        $str = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç)/", "/(Ç)/"), explode(" ", "a A e E i I o O u U n N c C"), $texto);
        return strtoupper($str);
    }

    public function eliminaSimbolos($txt)
    {
        return str_replace(array(',', '.', '-', '-', '/', '_', '*', '#', '(', ')', '[', ']'), '', $txt);
    }

    public function showMsg($msg, $classe, $close = 'N')
    {
        if ($close == 'S') {
            $msg = '<div class="alert alert-' . $classe . ' alert-dismissible fade show" role="alert">
                          ' . $msg . '
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                         </button>
                         </div>';
        } else {
            $msg = '<div class="alert alert-' . $classe . '">' . $msg . '</div>';
        }
        return $msg;
    }

    public function redirect($url, $msg = '', $seg = 0)
    {
        if ($msg) {
            echo $this->showMsg($msg, 'warning', 'N');
        }
        $seg = ($seg) ? $seg : 0;
        echo '<meta http-equiv="refresh" content="' . $seg . ',url=' . $url . '">';
        exit;
    }

    /**
     * Retorna um array somente com os elementos do filtro
     *
     * @param array $source array com itens para considerar
     * @param array $data array inteiro
     * @param string $column coluna referente ao filtro dos itens para preservar
     * @return array
     */
    public function getArrayByValueColumn(array $source, array $data, string $column): array
    {
        $new     = array_column($data, $column);
        $keep     = array_intersect($new, $source);

        return array_intersect_key($data, $keep);
    }
}
