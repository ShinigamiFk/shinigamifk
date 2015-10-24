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

        $view = $tpl = str_replace(array(">", ":save", ":", "> ", " : ", ": save"), '', trim($html)); // Limpiamos para obtener vista
        $path = $this->isModule() ? // Establece el Path por Defecto dependiendo de la Petición (App/Module)
                APP_ROOT . "modules/{$this->getModule()}/views/{$view}/html/" :
                APP_PATH . "views/{$view}/html/";

        if (is_numeric(strpos($html, '>'))) {// Evaluamos que se trate de una Vista Externa
            if (substr(trim($html), 0, 1) !== '>') {//Evaluamos si la Vista Externa es un Module o App
                list($module, $view) = explode('>', trim($html)); //Si es Module Separamos la vista y el modulo
                if (strpos($html, ':')) {//Si la vista Contiene un metodo ':' la limpiamos de ese metodo
                    list($view) = explode(':', trim($view)); //
                }
                $tpl = $module . "_" . $view; // Re armamos el nombre que llevara en TPL
                $path = APP_ROOT . "modules/{$module}/views/{$view}/html/"; // Asignamos Ruta de Modulo
            } else {// Si se trata de path APP
                $path = APP_PATH . "views/{$view}/html/";
            }
        } elseif (is_numeric(strstr($html, ':')) || $html) { // Evaluamos de que venga con un metodo o sea compatible
            if (!is_numeric((strstr($html, '>')))) {// Si no se trata de una vista externa asignamos el Path
                $path = $this->_path['view']; // Asignamos Ruta de APP
            }
        } else {// Si no es una Vista Externa, Ni trae Metodos, o viene Vacía
            $this->error(1, $html); // Renderizar Error
        }
        $this->setLayout('empty'); //Tomar Template por Defecto vacío
        $arr = array(); //Inicializar Array
        foreach ($array as $value) {// Recorremos el array de tpl que fueron pasados por el metodo
            $this->render($value, $path); //Verificamos que cada template exista en el directorio
            $arr[$value] = $this->fetch("index.tpl"); // Objeto Smarty con el Template
        }
        $this->setLayout('default'); // Reestablecemos el por defecto
        $this->clearCompiledTemplate(); // Delete compiled template file
        return strstr($html, ':save') ? (object) $arr : $this->assign($tpl, $arr); //Retorna el Templae o lo guarda en variable TPL
    }

    public function printTemplate($tpl = null) {
        $this->render((is_null($tpl) ? $this->getMethod() : $tpl), $this->_path['view']);
        $this->loadFilter("output", "trimwhitespace"); //Quitar Espcios en Blanco del Template Renderizado
        $this->display("index.tpl");
    }

    /**
     * Renderiza el template solicitado
     * 
     * @param String $template Template o vista
     * @throws ViewNotFound
     */
    private function render($tpl, $path) {

        $pathTPL = $path . $tpl . '.tpl';
        $pathHTML = $path . $tpl . '.html';

        if (!file_exists($pathTPL) && !file_exists($pathHTML)) {
            $this->error(2, $path . $tpl);
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
                $public[str_replace(APP_ROOT . 'public' . DS, '', $name)] = str_replace(APP_ROOT, SITE . DS, $name);
            }
            $this->assign('_public', $public);
            $this->assign('_content', (file_exists($pathTPL)) ? $pathTPL : $pathHTML);
            $this->assign('_params', $params);
            $this->assign('_root', APP_ROOT);
            $this->assign('_site', SITE);
            $this->assign('_base', BASE_URL);
            $this->assign('_path', str_replace(APP_ROOT, SITE . '/', $this->_path['view']));
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

        $view = str_replace(array(":add", ":remove"), '', trim($action));
        $path = $this->isModule() ?
                APP_ROOT . "modules/{$this->getModule()}/views/{$view}/{$name}/" :
                APP_PATH . "views/{$view}/{$name}/";

        if ($action && is_numeric(strpos($action, ':'))) {
            if (strpos($action, '>')) {
                list($module, $view) = explode('>', trim($action));
                if (strpos($action, ':')) {
                    list($view) = explode(':', trim($view));
                }
                $path = APP_ROOT . "modules/{$module}/views/{$view}/{$name}/";
            } else {
                $path = APP_PATH . "views/{$view}/{$name}/";
            }
        } elseif (is_numeric(strstr($action, ':')) && $action) {
            if (!is_numeric((strstr($action, '>')))) {
                $path = $this->_path['view'];
            }
        } else {
            $this->error(3, "$name('$action') - Params invalids!");
        }
        list($pt, $ac) = explode(':', trim($action));

        if (!$data) {
            switch ($ac) {
                case 'none':
                    unset($this->_path[$name]);
                    break;
                case 'own':
                    $this->autoload();
                    break;
                default:
                    $this->error(3, "$name('$action') - Method name invalid!");
                    break;
            }
        } else {
            foreach ($data as $value) {
                $file = $path . $value . "." . $name;
                switch ($ac) {
                    case 'add':
                        $this->_path[$name][] = $file;
                        break;
                    case 'remove':
                        $key = array_search($file, $this->_path[$name]);
                        unset($this->_path[$name][$key]);
                        break;
                    case 'url':
                        $this->_path[$name][] = $value;
                        break;
                    default:
                        $this->error(3, "$name('$action') - Method name invalid or Params!");
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

    private function error($error, $msj) {
        echo "<pre style='background:black;color:greenyellow;padding:20px;'>";
        print_r($this->_route->values);
        switch ($error) {
            case 1:
                echo "Method Does Not Exist: this->_view->tpl('" . $msj . "')";
                break;
            case 2:
                echo "<br/>Template not found:<br/> $msj.tpl <br/>or<br/> $msj.html";
                break;
            case 3:
                echo "Method Does Not Exist: this->_view->" . $msj;
                break;
            case 4:
                break;
            case 5:
                break;
            default:
                echo "Error Desconocido en la Vista :c";
                break;
        }
        echo "<br><br>- - Shinigami FK - -</pre>";
        exit;
    }

}
