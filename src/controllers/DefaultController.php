<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/ServerRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/Server.php';

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
        $server_repository = new ServerRepository();
        $user_repository = new UserRepository();

        session_start();

        $this->errorIfFalseWithMessage(
            array_key_exists("id", $_GET) != null,
            "Bad request (no id provided)");

        $id = $_GET["id"];
        $this->errorIfFalseWithMessageAndCode(
            $id != null,
            "Not found (server with provided id does not exist)",
            404);

        $server = $server_repository->getServerById($id);
        $this->errorIfFalseWithMessageAndCode(
            $server != null,
            "Not found (server with provided id does not exist)",
            404);

        $args = [ "server" => $server ];
        $submitter = $user_repository->getUserById($server->getSubmitterId());
        if ($submitter) {
            $args["submitter"] = $submitter;
        }

        $this->render('server', $args);
    }

    public function submit_server() {
        session_start();

        if (!isset($_SESSION) || !array_key_exists("logged_user", $_SESSION)) {
            $this->render('error', ["message" => "You can not submit a server unless you are logged in!"]);
            die();
        }

        $this->render('submit-server');
    }

    public function post_submission() {
        $server_repository = new ServerRepository();

        session_start();

        $this->errorIfFalseWithMessage($this->isPost(), "Bad request (not a POST request)");
        $this->errorIfFalseWithMessage(isset($_SESSION) && array_key_exists("logged_user", $_SESSION), "You are not logged in!");

        $submitter_id = $_SESSION["logged_user"]->getUserId();
        $title = $_POST['title'];
        $service_type = $_POST['service_type'];
        $address = $_POST['address'];
        $description = $_POST['description'];

        switch ($service_type) {
            case "Discord":
                $service_type_id = 1;
                break;
            case "Mumble":
                $service_type_id = 2;
                break;
            case "TeamSpeak":
                $service_type_id = 3;
                break;
            case "Other":
                $service_type_id = 4;
                break;
            default:
                $this->render('submit-server', ["message" => "Service type not selected!"]);
                return;
        }

        $server_repository->submitServer(
            $submitter_id,
            $title,
            $service_type_id,
            $address,
            $description
        );

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");
    }
}