<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/ServerRepository.php';
require_once __DIR__.'/../repository/ServerViewsRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/Server.php';

class DefaultController extends AppController {
    
    public function index() {
        $repo = new ServerRepository();

        if (array_key_exists("p", $_GET)) {
            $page = $_GET["p"];
        } else {
            $page = 1;
        }

        $this->render('index', [
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

    public function server() {
        $server_repository = new ServerRepository();
        $server_views_repository = new ServerViewsRepository();
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

        if (!array_key_exists("logged_user", $_SESSION)
            || !($server->canBeRemovedBy($_SESSION["logged_user"]->getUserId())
                || $_SESSION["logged_user"]->isAdmin())
        ) {
            $args["can_remove"] = false;
        } else {
            $args["can_remove"] = true;
        }

        if (!array_key_exists("logged_user", $_SESSION) || $submitter->getUserId() != $_SESSION["logged_user"]->getUserId()) {
            $server_views_repository->submitViewForServer($server->getSubmissionId());
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

    public function delete_server() {
        $server_repository = new ServerRepository();
        session_start();

        $this->errorIfFalseWithMessageAndCode(
            isset($_SESSION) && array_key_exists("logged_user", $_SESSION),
            "Unauthorized",
            403
        );
        $this->errorIfFalseWithMessageAndCode(
            array_key_exists("id", $_GET),
            "No submission id provided",
            400
        );

        $id = $_GET["id"];
        $server = $server_repository->getServerById($id);
        $this->errorIfFalseWithMessageAndCode(
            $server != null,
            "No submission with such id found",
            404
        );
        $this->errorIfFalseWithMessageAndCode(
            $server->canBeRemovedBy($_SESSION["logged_user"]->getUserId()),
            "Unauthorized",
            403
        );

        $server_repository->deleteServer($server);

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");
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
            case "TeamSpeak":
                $service_type_id = 2;
                break;
            case "Mumble":
                $service_type_id = 3;
                break;
            case "Other":
                $service_type_id = 4;
                break;
            default:
                $this->render('submit-server', [
                    "service_type_message" => "Service type not selected!",
                    "title" => $title,
                    "address" => $address,
                    "description" => $description
                ]);
                return;
        }

        if (strlen($title) < 8 || strlen($title) > 100) {
            $this->render('submit-server', [
                "title_message" => "Server name has to be at least 8 characters long and no longer than 100 characters!",
                "service_type" => $service_type,
                "address" => $address,
                "description" => $description
            ]);
            return;
        }

        // TODO: Improve address-checking
        if (strlen($address) < 8) {
            $this->render('submit-server', [
                "title" => $title,
                "service_type" => $service_type,
                "address_message" => "Address has to be at least 8 characters long",
                "description" => $description
            ]);
            return;
        }

        if (strlen($description) < 8) {
            $this->render('submit-server', [
                "title" => $title,
                "service_type" => $service_type,
                "address" => $address,
                "description_message" => "Description has to be at least 8 characters long!"
            ]);
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