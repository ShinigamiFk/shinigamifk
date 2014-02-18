<?php

class IndexController extends BaseController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        try {

            $this->_view->printTemplate();
            
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

}
