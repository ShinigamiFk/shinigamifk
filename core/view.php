<?php

//error_reporting(E_ERROR | E_PARSE);

class View extends Smarty {

    /**
     * Titulo de la vista actual.
     * @var String
     */
    private $_title;

    /**
     * Layout que utiliza la vista.
     * @var String
     */
    private $_layout;

    /**
     * Establece las rutas de (views, js, css) utilizados por la vista solicitada.
     * @var Array
     */
    private $_path;

    /**
     * Router
     * @var Route 
     */
    private $_route;

    /**
     * Mantiene la vista actual.
     * @var Array 
     */
    public $_view;

    /**
     * Configura y establece la configuración de la vista.
     * @param Route $route
     */
    public function __construct(Route $route) {
        parent::__construct();
        $this->template_dir = APP_PATH . '/views/layout/' . DEFAULT_LAYOUT . DS;
        $this->compile_dir = APP_ROOT . 'libraries/smarty/temp/template/';
        $this->cache_dir = APP_ROOT . 'libraries/smarty/temp/cache/';
        $this->_route = $route;
        $this->_view = $this->_route->values['controller'];
        $this->autoload();
    }

    /**
     * Obtiene el titulo de la vista actual.
     * @return String
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
     * Actualiza el titulo de la vista actual.
     * @param String $_title
     */
    public function setTitle($_title) {
        $this->_title = $_title;
    }

    /**
     * Obtiene el layout que la vista actual utiliza.
     * @return String
     */
    public function getLayout() {
        return $this->_layout;
    }

    /**
     * Convierte un array en JSON retornandolo al navegador.
     * 
     * @param array $data
     */
    public function printJSON(array $data) {
        header("content-type: application/json");
        print json_encode($data);
    }

    /**
     * Comprueba si la vista a tratar pertenece a un módulo.
     * 
     * @return boolean
     */
    private function isModule() {
        return isset($this->_route->values['module']) ? true : false;
    }

    /**
     * Obtiene el nombre del módulo que esta tratando la vista.
     * 
     * @return Null|String
     */
    private function getModule() {
        return $this->isModule() ? $this->_route->values['module'] : null;
    }

    /**
     * Obtiene el método de la vista actual.
     * 
     * @return String
     */
    private function getMethod() {
        $debug = debug_backtrace();
        foreach ($debug as $trace) {
            if (isset($trace['class']) && $trace['class'] != __CLASS__) {
                return $trace['function'];
            }
        }
    }

    /**
     * Actualiza el layout de la vista.
     * @param String $_layout
     */
    public function setLayout($_layout) {
        $this->_layout = $_layout;
        $this->setTemplateDir(APP_PATH . 'views/layout/' . $_layout . DS);
        //$this->setConfigDir(APP_PATH . 'views/layout/' . $_layout . '/configs/');
    }

    public function tpl($html, $array = array()) {
        if (strstr($html, '>') && !strstr($html, ':')) {
            if (substr(trim($html), 0, 1) === '>') {
                $view = str_replace(array(">"), "", trim($html));
                $path = $this->isModule() ?
                        APP_ROOT . "modules/{$this->getModule()}/views/{$view}/html/" :
                        APP_PATH . "views/{$view}/html/";
                $tpl = str_replace(array(">", ":save", ":"), '', $html);
            } else {
                list($module, $view) = explode('>', trim($html));
                $path = APP_ROOT . "modules/{$module}/views/{$view}/html/";
                $tpl = $view;
            }
        } elseif (strstr($html, ':')) {
            $path = $this->_path['view'];
        } else {
            $this->error("Method Does Not Exist: this->_view->tpl('" . $html . "')");
        }
        $this->setLayout('empty');
        $arr = array();
        foreach ($array as $value) {
            $this->render($value, $path);
            $arr[$value] = $this->fetch("index.tpl");
        }
        $this->setLayout('default');
        $this->clearCompiledTemplate();

        return strstr($html, ':save') ? (object) $arr : $this->assign($tpl, $arr);
    }

    public function printTemplate($tpl = null) {
        $this->render((is_null($tpl) ? $this->getMethod() : $tpl), $this->_path['view']);
        //$this->loadFilter("output", "trimwhitespace"); //Quitar Espcios en Blanco del Template Renderizado
        $this->display("index.tpl");
    }

    /**
     * Renderiza el template solicitado
     * 
     * @param String $template Template o vista
     * @throws ViewNotFound
     */
    private function render($tpl, $path) {


       /* $this->getPath(3);

        echo $tpl;
        echo "<br/>";
        echo $path;

        exit;*/

        if (!file_exists($path . "$tpl.tpl")) {

            /* $this->assign('XVI', array(
              'details' => $this->_route->values,
              'template' => "$path$tpl.tpl"
              )); */

            //$this->tpl('>opps', array('aspp'));

            $this->error("Template not found:   $path$tpl.tpl");
        } else {
            $params = array(
                'configs' => array(
                    'title' => $this->_title
                ),
                'request' => array(
                    'controller' => $this->_route->values['controller'],
                    'method' => $this->getMethod()
                ),
                'resources' => array(
                    'css' => $this->_path['css'],
                    'js' => $this->_path['js'],
                )
            );
            // Autoload URL folders in /public/*
            $public = array();
            foreach (glob(APP_ROOT . 'public' . DS . '*', GLOB_ONLYDIR) as $name) {
                $public[str_replace(APP_ROOT . 'public' . DS, '', $name)] =
                        str_replace(APP_ROOT, SITE . DS, $name);
            }
            $this->assign('_public', $public);
            $this->assign('_content', $path . "$tpl.tpl");
            $this->assign('_params', $params);
            $this->assign('_root', APP_ROOT);
            $this->assign('_site', SITE);
            $this->assign('_base', BASE_URL);
            $this->assign('_session', Session::singleton());
        }
    }

    public function css($action, $array = array()) {
        $this->setResources($action, 'css', $array);
    }

    public function js($action, $array = array()) {
        $this->setResources($action, 'js', $array);
    }

    private function setResources($action, $name, $data = array()) {

        $path = $this->isModule() ?
                APP_ROOT . "modules/{$this->getModule()}/views/" : APP_PATH . "views/";

        if (strstr($action, '>') && !strstr($action, ':')) {
            if (substr(trim($action), 0, 1) === '>') {
                $action = str_replace(array(">", " "), "", $action);
                foreach ($data as $value)
                    $this->_path[$name][] = $path . $action . DS . $name . DS . $value . "." . $name;
            } else {
                list($module, $view) = explode('>', trim($action));
                $path = APP_ROOT . "modules/{$module}/views/{$view}/";
                foreach ($data as $value)
                    $this->_path[$name][] = $path . $name . DS . $value . "." . $name;
            }
        } else {
            $diff = array();
            foreach ($data as $value)
                $diff[] = $path . $this->_view . DS . $name . DS . $value . "." . $name;
            if (strstr($action, ':') ? $action : ':' . $action) {
                switch ($action) {
                    case ':add':
                        foreach ($diff as $value)
                            $this->_path[$name][] = $value;
                        break;
                    case ':url':
                        foreach ($data as $value)
                            $this->_path[$name][] = $value;
                        break;
                    case ':none':
                        unset($this->_path[$name]);
                        break;
                    case ':remove':
                        $this->_path[$name] = array_diff($this->_path[$name], $diff);
                        break;
                    case strstr($action, ':app>'):
                        list($app, $view) = explode(':app>', $action);
                        $path = APP_PATH . "views/{$view}/";
                        foreach ($data as $value)
                            $this->_path[$name][] = $path . $name . DS . $value . "." . $name;
                        break;
                    default:
                        $this->error("Method Does Not Exist in  : this->_view->$name('" . $action . "')");
                        break;
                }
            }
        }
    }

    /*
     * Autocargar Recursos JS,CSS de la Vista Actual
     */

    private function autoload() {

        if ($this->isModule()) {
            $this->_path['view'] = APP_ROOT . "modules/{$this->getModule()}/views/" .
                    $this->_view . DS . "html" . DS;
            $this->_path['js'] = glob(APP_ROOT . "modules/{$this->getModule()}/views/" .
                    $this->_view . "/js/*{.js,.JS}", GLOB_BRACE);
            $this->_path['css'] = glob(APP_ROOT . "modules/{$this->getModule()}/views/" .
                    $this->_view . "/css/*{.css,.CSS}", GLOB_BRACE);
        } else {
            $this->_path['view'] = APP_PATH . 'views/' .
                    $this->_view . DS . "html" . DS;
            $this->_path['js'] = glob(APP_PATH . "views/" .
                    $this->_view . "/js/*{.js,.JS}", GLOB_BRACE);
            $this->_path['css'] = glob(APP_PATH . "views/" .
                    $this->_view . "/css/*{.css,.CSS}", GLOB_BRACE);
        }
    }

    private function error($msj) {

        echo "<pre>";
        @print_r($this->_route->values);
        echo "</pre>";
        exit($msj);
    }

    //

    private function getPath($format) {

        $array = array();

        if (strstr($format, '>') && !strstr($format, ':')) { // Si Tiene '>' y no tiene ':' ghace referencia a otra Lista!.
            if (substr(trim($format), 0, 1) === '>') {// Pertenece a la Carpeta APP
                $view = str_replace(array(">"), "", trim($format)); // Reemplazar '>' por espacios (ya tenemos un nonbre limpio)

                $path = $this->isModule() ? // Determina ruta para el path
                        APP_ROOT . "modules/{$this->getModule()}/views/{$view}/html/" :
                        APP_PATH . "views/{$view}/html/";

                $tpl = str_replace(array(">", ":save", ":"), '', $format); //Limpiar caracteres
            } else {
                list($module, $view) = explode('>', trim($format)); // Sino no se encuentra al inicio '>', busca y separa en modulo y vista

                $path = APP_ROOT . "modules/{$module}/views/{$view}/html/";

                $tpl = $view;
            }
        } elseif (strstr($html, ':')) {// Si tiene dos Puntos se Considera como Método
            $path = $this->_path['view'];
        } else {
            $this->error("Method Does Not Exist: this->_view->tpl('" . $html . "')");
        }
    }

}