<?php

class Settings extends Controller {
    
    public $_db;

    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
        $this->_db = DB::getInstance();
        //var_dump($_Session);
    }

    public function index() {
    $this->view->render('settings');
    }

    
}
