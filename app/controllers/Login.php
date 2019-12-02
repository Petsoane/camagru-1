<?php

class Login extends Controller {

    private $_db;

    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
        $this->_db = DB::getInstance();
    }

    public function login($input = []) {
        $this->errors = []; 
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hash = password_hash($password, PASSWORD_BCRYPT);
     
        if ($username)
        {
            if ($password) {
                if ($user = $this->_db->query('SELECT username FROM users WHERE username = ?', ['username' => $username])->results()) {
                    $user = $user[0]->username;
                    if ($pass = $this->_db->query('SELECT pass FROM users WHERE username = ?', ['username'=>$username])->results()) {
                        $pass = $pass[0]->pass;
                    }
                    if (password_verify($password, $pass)) { 
                        $_SESSION['user'] = $this->_db->query('SELECT token FROM users WHERE username = ?', ['username'=>$username])->results()[0]->token;
                    } else {
                        echo 'Incorrect Password!';
                    }
                } else {
                    echo 'Username not registered!';
                }
            } else {
                echo 'Please enter a password!';
            } 
        } else {
            echo 'Please enter a username!';
        }
    }
 
    public function logout() {
        unset($_SESSION['user']);
        Router::redirect('home');
    }
	public function index() {
       $this->view->render('login');
    }
}
