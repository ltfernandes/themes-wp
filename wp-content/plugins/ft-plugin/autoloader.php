<?php
namespace PluginAlq\Autoload;

class Load
{
    public $_dirPlugin;

    public function __construct()
    {
        $this->_dirPlugin = dirname(__FILE__);
        $this->init();
    }

    public function init()
    {
        spl_autoload_register([$this, 'registraClasses']);
    }

    public function registraClasses()
    {
        $this->requireClass('/src/*.class.php');
    }

    public function requireLibs($pathlib)
    {
        //Inclue autoload de terceiros
        require_once $this->_dirPlugin . '/libs/private/' . $pathlib;
    }

    public function requireClass($path)
    {
        $arquivos = $this->rglob($this->_dirPlugin . $path);
        if (count($arquivos)) {
            foreach ($arquivos as $arquivo) {
                require_once $arquivo;
            }
        }
    }

    public function rglob($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        $arrdir = glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT);

        if (is_array($arrdir) && count($arrdir)) {
            sort($arrdir);
            foreach ($arrdir as $dir) {
                $arrfiles = $this->rglob($dir . '/' . basename($pattern), $flags);
                if (count($arrfiles) && is_array($arrfiles)) {
                    if (count($files) && is_array($files)) {
                        $files = array_merge($files, $arrfiles);
                    } else {
                        $files = $arrfiles;
                    }
                }
            }
        }
        if (count($files)) {
            return $files;
        }
    }
}
