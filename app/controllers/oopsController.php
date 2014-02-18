<?php

class OopsController extends BaseController {

    const ERROR_DEFAULT = 'XIII';

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->error();
    }

    public function error($code = self::ERROR_DEFAULT) {
        $this->_view->assign('code', $code);
        $this->_view->printTemplate('oops');
    }

}