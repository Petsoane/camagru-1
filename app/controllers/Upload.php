<?php
class Upload extends Controller {
    
    private $_db;

    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
        $this->_db = DB::getInstance();
    }

    public function index() {
       $this->view->render('upload');
    }

 
    public function logout() {
        unset($_SESSION['user']);
        Router::redirect('home');
    }

    public function upload() {
        if (isset($_POST['img'])) {
            $img = $_POST['img'];
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file =  ROOT . DS . 'img' . DS . 'img_' . date('YmdHis') . '.png';
            file_put_contents($file, $data);

            $save = 'img' . DS . 'img_' . date('YmdHis') . '.png';
            $id = $this->_db->query('SELECT id FROM users WHERE token = ?', ['token'=>$_SESSION['user']])->results()[0]->id;
            $fields = ['img'=>$save,'user'=>$id]; 
            $this->_db->insert('posts', $fields);

        } else {
            echo "Error: No image data";
        }


        // if (preg_match('/^data:image\/(\w+);base64,/', $file, $type)) {
            //     $data = substr($file, strpos($file, ',') + 1);
        //     $type = strtolower($type[1]);
        //     if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
        //         throw new \Exception('invalid image type');
        //     }
        //     $data = base64_decode($data);
        //     if ($data === false) {
        //         throw new \Exception('base64_decode failed');
        //     }
        // } else {
        //     throw new \Exception('did not match data URI with image data');
        // }
        // file_put_contents("img.{$type}", $data);

    }

}