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

    public function upload() {
        if(isset($_FILES['image'])){
            $errors= array();
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
        
            $extensions= array("jpeg","jpg","png");
        
            if(in_array($file_ext,$extensions)=== false){
                $errors[]="extension not allowed, please choose a JPEG or PNG file.";
            }
        
            if($file_size > 2097152) {
                $errors[]='File size must be excately 2 MB';
            }
        
            if(empty($errors)==true) {
                move_uploaded_file($file_tmp,"images/".$file_name);
                echo "Success";
            }else{
                print_r($errors);
            }
        }
    }
    
}
