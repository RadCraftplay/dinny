<?php

require_once 'AppController.php';

class DefaultController extends AppController {
    
    public function index() {
        $this->render('index');
    }

    public function login() {
        $this->render('login');
    }

    public function register() {
        $this->render('register');
    }

    public function server() {
        $this->render('server');
    }

    public function submit_server() {
        $this->render('submit-server');
    }
}