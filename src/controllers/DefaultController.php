<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/ServerRepository.php';
require_once __DIR__.'/../repository/ServerViewsRepository.php';
require_once __DIR__.'/../repository/ServiceTypeRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/Server.php';

class DefaultController extends AppController {
    
    public function index() {
        $repo = new ServerRepository();
        $service_type_repo = new ServiceTypeRepository();

        if (array_key_exists("p", $_GET)) {
            $page = $_GET["p"];
        } else {
            $page = 1;
        }

        $this->render('index', [
            "service_types" => $service_type_repo->getServiceTypes(),
            "servers" => $repo->getPage($page),
            "page" => $page,
            "page_count" => $repo->getPageCount()
        ]);
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

    public function browse() {
        $server_repository = new ServerRepository();
        $server_views_repository = new ServerViewsRepository();

        session_start();

        $this->errorIfFalseWithMessage(
            isset($_SESSION) && array_key_exists("logged_user", $_SESSION),
            "You are not logged in");

        $popular_ids = $server_views_repository->getPopularServerIds();
        $popular_servers = $server_repository->getServersByIds($popular_ids);

        $this->render('browse', [
            "popular_servers" => $popular_servers
        ]);
    }
}