<?php
 
class Validate {

    private $_db;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function check($input = []) {
        if ($input) {
            if ($input[0] == 'username') {
                return $this->username($input);
            }
            if ($input[0] == 'password') {
                return $this->password($input);
            }
            if ($input[0] == 'email') {
                return $this->email($input);
            }
            if ($input[0] == 'match') {
                return $this->match($input);
            }
        }
    }

    public function username($input) {
        $username = $input[1];


        // $db = $this->_db->query('SELECT username FROM users WHERE username = ?', ['username' => $username]);
        // dnd($db);

        if ($this->_db->query('SELECT username FROM users WHERE username = ?', ['username' => $username])) {
            if (strlen($username) >= 3 && strlen($username) <= 32) {
                if (preg_match('/[a-zA-Z0-9_]+/', $username)) {
                    return [true];
                } else {
                    return [false, "Usernames can only contain uppercase, lowercase and digits."];
                }
            } else {
                return [false, "Username must be between 3 and 32 characters long."];
            }
        } else {
            return [false, "User $username exists."];
        }
    }       

    public function password($input) {
        $password = $input[1];
        if (strlen($password >= 6 && strlen($password) <= 32)) {
            return [true];
        } else {
            return [false, "Passwords must be between 6 and 32 characters long"];
        }
    }

    public function email($input) {
        $email = $input[1];
        if ($this->_db->query('SELECT email FROM users WHERE email = ?', ['email' => $email])) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return [true];
            } else {
                return [false, "Please enter a valid email address."];
            }
        } else {
            return [false, "Email $email exists"];
        }
    }

    public function match($input) {
        $check = $input[1];
        $match = $input[2];
        if ($check == $match) {
            return [true];
        } else {
            return [false, "Passwords do not match."];
        }
    }
}