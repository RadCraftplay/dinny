<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/ServerRepository.php';

class DefaultController extends AppController {
    
    public function index() {
        $repo = new ServerRepository();
        $this->render('index', ["servers" => $repo->getServers()]);
    }

    public function about() {
        $this->render('about');
    }

    public function login() {
        if (isset($_SESSION) && array_key_exists("logged_user", $_SESSION)) {
            $this->render('error', ["message" => "You are already logged in!"]);
            die();
        }

        $this->render('login');
    }

    public function register() {
        if (isset($_SESSION) && array_key_exists("logged_user", $_SESSION)) {
            $this->render('error', ["message" => "You are already logged in! Log out first if you want to register a new account."]);
            die();
        }

        $this->render('register');
    }

    public function server() {
        session_start();

        $this->render('server');
    }

    public function submit_server() {
        session_start();

        if (!isset($_SESSION) || !array_key_exists("logged_user", $_SESSION)) {
            $this->render('error', ["message" => "You can not submit a server unless you are logged in!"]);
            die();
        }

        $this->render('submit-server');
    }
}