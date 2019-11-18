<?php

class Register extends Controller {

    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
        $this->loadModel('Users');
        $this->view->setLayout('default');
    }

    public function loginAction() {
        $validation = new Validate();
        if ($_POST) {
            $validation->check($_POST, [
                'username' => [
                    'display' => "Username",
                    'required' => true
                ],
                'password' => [
                    'display' => "Password",
                    'required' => true
                ]
            ]);
            if ($validation->passed()) {
                $user = $this->UsersModel->findByUsername($_POST['username']);
                if ($user && password_verify(Input::get('password'), $user->password)) {
                    $remember = (isset($_POST['remember_me']) && Input::get('remember_me')) ? true : false;
                    var_dump($_POST['remember_me']);
                    var_dump($remember);
                    $user->login($remember);
                    // Router::redirect('');   
                } else {
                    $validation->addError("Invalid Username or Password");
                }
            }
        }
        $this->view->displayErrors = $validation->displayErrors();
        $this->view->render('register/login');
    }

    public function logoutAction() {
        if (currentUser()) {
            currentUser()->logout();
        }
        Router::redirect('register/login');
    }
}