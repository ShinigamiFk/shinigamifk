<?php

abstract class BaseController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Method must implement all drivers that inherit from the class.
     */
    abstract public function index();
    
    /* Methods and functions for all controllers */
    
}