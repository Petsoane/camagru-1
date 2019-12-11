<?php

class Gallery extends Controller {
    
    public $_db;
    public $images;
    public $comments;
    public $likes;
    public $n = 0;
    public $start = [];
    public $next = [];
    public $prev = [];

    public function __construct($controller, $action) {
        parent::__construct($controller, $action);
        $this->_db = DB::getInstance();
    }

    public function setup() {
        $images = [];
        $comments = [];
        $liked = [];
        $this->images = $this->_db->query('SELECT * FROM posts')->results();
        $this->comments = $this->_db->query('SELECT * FROM comments')->results();
        $this->likes = $this->_db->query('SELECT * FROM likes')->results();
        if (isset($_SESSION['user'])) {
            $uid = $this->_db->query('SELECT id FROM users WHERE token = ?', ['token'=>$_SESSION['user']])->results();
            if ($uid) {
                $uid = $uid[0]->id;
            }
        }
        foreach ($this->images as $img) {
            foreach ($this->comments as $comment) {
                if ($comment->post == $img->id) {
                    $comments[] = $comment->text;
                }
            }
            foreach ($this->likes as $like) {
                if (isset($uid) && $like->post == $img->id && $like->user == $uid) {
                    $liked[$img->id] = $like->post;
                }
            }
            // dnd($liked);
            $images[] = [
                'id' => $img->id,
                'uid' => $img->user,
                'image' => $img->img,
                'likes' => $img->likes,
                'liked' => $liked,
                'comments' => $comments
            ];
            $comments = [];
            $liked = [];
        }
        $this->n = count($images);
        $i = $_POST['count'];
        $count = 5;
        $start = 1;
        if (isset($_POST['start']) && $_POST['start']) {
            while ($i < $this->n - 1 && $count) {
                echo "<div class='center post' id='" . $images[$i]['id'] . "'>";
                echo "<div id='imagess'>";
                    echo "<img id='" . $images[$i]['id'] . "' src='" . $images[$i]['image'] . "' style='width: 25%'><p></p>";
                echo "</div>";
                echo "<div id='likes'>";
                if (isset($_SESSION['user'])) {
                    if (array_key_exists($images[$i]['id'], $images[$i]['liked'])) {
                        echo "<input class='button text-black grey' id='unlikebutton' type='submit' value='Unlike'/>";
                    } else {
                        echo "<input class='button text-black grey' id='likebutton' type='submit' value='Like'/>";
                    }
                }
                echo "<p> Likes: " . $images[$i]['likes'] . "</p>";
                echo "</div>";
                if ($images[$i]['comments']) {
                    echo "<div style='display: inline-flex'>";
                        echo "<a class='prev' onclick='plusSlides(-1)'>&#10094; Prev</a>";
                    echo "</div>";
                    echo "<div id='comments' class='slideshow-container' style='display: inline-flex'>";
                    foreach ($images[$i]['comments'] as $text) {
                        if ($start) {
                            echo "<div class='comments fade center' style='display: block; border: 1px solid grey; margin: 20px; padding-left: 10px; padding-right: 10px'>";
                                echo "<p>" . $text . " </p>";
                            echo "</div>";
                        } else {
                            echo "<div class='comments fade center' style='display: none; border: 1px solid grey; margin: 20px; padding-left: 10px; padding-right: 10px'>";
                                echo "<p>" . $text . " </p>";
                            echo "</div>";
                        } 
                        $start = 0;
                    }
                    echo "</div>";
                    echo "<div style='display: inline-flex'>";
                        echo "<a class='next' onclick='plusSlides(1)'>Next &#10095;</a>";
                    echo "</div>";
                    echo "<div class='padding-16'>";
                        echo "<a class='prev' onclick='allSlides()'> All Comments </a>";
                    echo "</div>";
                    if (isset($_SESSION['user'])) {
                        echo "<input class='input center' id='commentin' name='next' type='text' placeholder='Add Comment'/><p></p>";
                        echo "<input class='button text-black grey' id='commentbutton' type='button' name='comment' value='Comment'><p></p>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class='center'>";
                    echo "<p> No comments</p>";
                    if (isset($_SESSION['user'])) {
                        echo "<input class='input center' id='commentin' name='next' type='text' placeholder='Add Comment'/><p></p>";
                        echo "<input class='button text-black grey' id='commentbutton' type='button' name='comment' value='Comment'><p></p>";
                    }
                    echo "</div>";
                }
                $i++;
                $count--;
                echo "<hr>";
            }
            echo "<p style='display: none; color: black;' id='counter' name='count'>" . $i . "</p>";
        }
    } 

    public function like() {
        if (isset($_POST['postId']) && $_POST['postId']) {
            if (isset($_SESSION['user'])) {
                $uid = $this->_db->query('SELECT id FROM users WHERE token = ?', ['token'=>$_SESSION['user']])->results();
                if ($uid) {
                    $uid = $uid[0]->id;
                }
            }
            $id = $this->_db->query('SELECT * FROM likes WHERE user = ? AND post = ?', ['user'=>$uid, 'post'=>$_POST['postId']])->results();
            if ($id) {
                $id = $id[0]->id;
                $this->_db->delete('likes', $id);
                echo "<p>". $_POST['postId'] . "</p>";
                echo "<p>if</p>";
            } else {
                $fields = ['post'=>$_POST['postId'], 'user'=>$uid]; 
                $this->_db->insert('likes', $fields);
                echo "<p>". $_POST['postId'] . "</p>";
                echo "<p>else</p>";
            }
        }
    }

    public function comment() {
        if (isset($_POST['comment']) && $_POST['comment']) {
            echo "<p>". $_POST['comment'] . "</p>";
        }
    }

    public function display() {
        $images = [];
        $comments = [];
        $this->images = $this->_db->query('SELECT * FROM posts')->results();
        $this->comments = $this->_db->query('SELECT * FROM comments')->results();
        foreach ($this->images as $img) {
            foreach ($this->comments as $comment) {
                if ($comment->post == $img->id) {
                    $comments[] = $comment->text;
                }
            }
            $images[] = [
                'id' => $img->id,
                'uid' => $img->user,
                'image' => $img->img,
                'likes' => $img->likes,
                'comments' => $comments
            ];
            $comments = [];
        }
        $this->n = count($images);
        $i = $_POST['count'];
        $count = 5;
        $start = 1;
        if (isset($_POST['next']) && $_POST['next']) {
            while ($i < $this->n && $count) {

                echo "<div class='center' id='maing'>";
                echo "<div id='imagess'>";
                    echo "<img id='" . $images[$i]['id'] . "' src='" . $images[$i]['image'] . "' style='width: 25%'><p></p>";
                echo "</div>";
                echo "<div id='likes'>";
                if (isset($_SESSION['user'])) {
                    $uid = $this->_db->query('SELECT id FROM users WHERE token = ?', ['token'=>$_SESSION['user']])->results();
                    if ($uid) {
                        $uid = $uid[0]->id;
                        if ($images[$i]['uid'] == $uid) {
                            echo "<input class='button text-black grey' id='unlikebutton' name='next' type='submit' value='Unlike'/>";
                        } else {
                            echo "<input class='button text-black grey' id='likebutton' name='next' type='submit' value='Like'/>";
                        }
                    }
                }
                echo "<p> Likes: " . $images[$i]['likes'] . "</p>";
                echo "</div>";
                if ($images[$i]['comments']) {
                    echo "<div style='display: inline-flex'>";
                        echo "<a class='prev' onclick='plusSlides(-1)'>&#10094; Prev</a>";
                    echo "</div>";
                    echo "<div id='comments' class='slideshow-container' style='display: inline-flex'>";
                    foreach ($images[$i]['comments'] as $text) {
                        if ($start) {
                            echo "<div class='comments fade center' style='display: block; border: 1px solid grey; margin: 20px; padding-left: 10px; padding-right: 10px'>";
                                echo "<p>" . $text . " </p>";
                            echo "</div>";
                        } else {
                            echo "<div class='comments fade center' style='display: none; border: 1px solid grey; margin: 20px; padding-left: 10px; padding-right: 10px'>";
                                echo "<p>" . $text . " </p>";
                            echo "</div>";
                        } 
                        $start = 0;
                    }
                    echo "</div>";
                    echo "<div style='display: inline-flex'>";
                        echo "<a class='next' onclick='plusSlides(1)'>Next &#10095;</a>";
                    echo "</div>";
                    echo "<div class='padding-16'>";
                        echo "<a class='prev' onclick='allSlides()'> All Comments </a>";
                    echo "</div>";

                    if (isset($_SESSION['user'])) {
                        echo "<input class='input center' id='commentin' name='next' type='text' placeholder='Add Comment'/><p></p>";
                        echo "<input class='button text-black grey' id='commentbutton' type='button' name='comment' value='Comment'><p></p>";
                    }
                    
                    echo "</div>";
                } else {
                    echo "<div class='center'>";
                    echo "<p> No comments</p>";
                    if (isset($_SESSION['user'])) {
                        echo "<input class='input center' id='commentin' name='next' type='text' placeholder='Add Comment'/><p></p>";
                        echo "<input class='button text-black grey' id='commentbutton' type='button' name='comment' value='Comment'><p></p>";
                    }
                    echo "</div>";
                }
                $i++;
                if ($i == $this->n) {
                    $i = 0;
                }
                $count--;
                echo "<hr>";
            }
            echo "<p style='display: none; color: black;' id='counter' name='count'>" . $i . "</p>";

        } else if (isset($_POST['prev']) && $_POST['prev']) {
            while ($i >= 0 && $count) {
                echo "<div class='center' id='maing'>";
                echo "<div id='imagess'>";
                    echo "<img id='" . $images[$i]['id'] . "' src='" . $images[$i]['image'] . "' style='width: 25%'><p></p>";
                echo "</div>";
                echo "<div id='likes'>";
                if (isset($_SESSION['user'])) {
                    $uid = $this->_db->query('SELECT id FROM users WHERE token = ?', ['token'=>$_SESSION['user']])->results();
                    if ($uid) {
                        $uid = $uid[0]->id;
                        if ($images[$i]['uid'] == $uid) {
                            echo "<input class='button text-black grey' id='unlikebutton' name='next' type='submit' value='Unlike'/>";
                        } else {
                            echo "<input class='button text-black grey' id='likebutton' name='next' type='submit' value='like'/>";
                        }
                    }
                }
                echo "<p> Likes: " . $images[$i]['likes'] . "</p>";
                echo "</div>";
                if ($images[$i]['comments']) {
                    echo "<div style='display: inline-flex'>";
                        echo "<a class='prev' onclick='plusSlides(-1)'>&#10094; Prev</a>";
                    echo "</div>";
                    echo "<div id='comments' class='slideshow-container' style='display: inline-flex'>";
                    foreach ($images[$i]['comments'] as $text) {
                        if ($start) {
                            echo "<div class='comments fade center' style='display: block; border: 1px solid grey; margin: 20px; padding-left: 10px; padding-right: 10px'>";
                                echo "<p>" . $text . " </p>";
                            echo "</div>";
                        } else {
                            echo "<div class='comments fade center' style='display: none; border: 1px solid grey; margin: 20px; padding-left: 10px; padding-right: 10px'>";
                                echo "<p>" . $text . " </p>";
                            echo "</div>";
                        } 
                        $start = 0;
                    }
                    echo "</div>";
                    echo "<div style='display: inline-flex'>";
                        echo "<a class='next' onclick='plusSlides(1)'>Next &#10095;</a>";
                    echo "</div>";
                    echo "<div class='padding-16'>";
                        echo "<a class='prev' onclick='allSlides()'> All Comments </a>";
                    echo "</div>";
                    if (isset($_SESSION['user'])) {
                        echo "<input class='input center' id='commentin' name='next' type='text' placeholder='Add Comment'/><p></p>";
                        echo "<input class='button text-black grey' id='commentbutton' type='button' name='comment' value='Comment'><p></p>";
                    }
                    echo "</div>";
                } else {
                    echo "<div class='center'>";
                    echo "<p> No comments</p>";
                    if (isset($_SESSION['user'])) {
                        echo "<input class='input center' id='commentin' name='next' type='text' placeholder='Add Comment'/><p></p>";
                        echo "<input class='button text-black grey' id='commentbutton' type='button' name='comment' value='Comment'><p></p>";
                    }
                    echo "</div>";
                }
                if ($i == 0) {
                    $i = $this->n;
                }
                $i--;
                $count--;
                echo "<hr>";
            }
            echo "<p style='display: none; color: black;' id='counter' name='count'>" . $i . "</p>";
        } else {
            echo "<p>No photos</p>";
            echo "<p style='display: none; color: black;' id='counter' name='count'>" . 0 . "</p>";
        }
        unset($_POST['prev']);
        unset($_POST['next']);
    }

    public function index() {
        $this->view->render('gallery');
    }

}