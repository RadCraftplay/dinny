<?php

require_once 'AppController.php';

class DefaultController extends AppController {
    
    public function index() {
        $this->render('index');
    }

    public function login() {
        session_start();

        if (array_key_exists("logged_user", $_SESSION)) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/");
            die();
        }

        $this->render('login');
    }

    public function register() {
        session_start();

        if (array_key_exists("logged_user", $_SESSION)) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/");
            die();
        }

        $this->render('register');
    }

    public function server() {
        $this->render('server');
    }

    public function submit_server() {
        $this->render('submit-server');
    }
}