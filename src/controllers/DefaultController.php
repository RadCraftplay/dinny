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

        $args["can_remove"] = array_key_exists("logged_user", $_SESSION)
            && $server->canBeRemovedBy($_SESSION["logged_user"]);

        $args["can_edit"] = array_key_exists("logged_user", $_SESSION)
            && $server->canBeEditedBy($_SESSION["logged_user"]);

        if (!array_key_exists("logged_user", $_SESSION) || $submitter->getUserId() != $_SESSION["logged_user"]->getUserId()) {
            $server_views_repository->submitViewForServer($server->getSubmissionId());
        }
        $this->render('server', $args);
    }

    public function submit_server() {
        $service_type_repo = new ServiceTypeRepository();

        session_start();

        if (!isset($_SESSION) || !array_key_exists("logged_user", $_SESSION)) {
            $this->render('error', ["message" => "You can not submit a server unless you are logged in!"]);
            die();
        }

        $this->render('submit-server', [
            "service_types" => $service_type_repo->getServiceTypes(),
        ]);
    }

    public function edit_server() {
        $server_repository = new ServerRepository();
        $service_type_repo = new ServiceTypeRepository();

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
        $this->errorIfFalseWithMessageAndCode(
            $id != null,
            "Not found (no id provided)",
            404
        );

        try {
            $server = $server_repository->getServerById($id);
        } catch (Exception $ex) {
            $this->render_error("Invalid id provided", 400);
            $server = null;
        }

        $this->errorIfFalseWithMessageAndCode(
            $server != null,
            "No submission with such id found",
            404
        );
        $this->errorIfFalseWithMessageAndCode(
            $server->canBeEditedBy($_SESSION["logged_user"]),
            "Unauthorized",
            403
        );

        $this->render('submit-server', [
            "service_types" => $service_type_repo->getServiceTypes(),
            "title" => $server->getTitle(),
            "service_type" => $server->getServiceTypeId(),
            "address" => $server->getAddress(),
            "description" => $server->getDescription(),
            "edited_submission_id" => $server->getSubmissionId()
        ]);
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
        $this->errorIfFalseWithMessageAndCode(
            $id != null,
            "Not found (no id provided)",
            404
        );

        try {
            $server = $server_repository->getServerById($id);
        } catch (Exception $ex) {
            $this->render_error("Invalid id provided", 400);
            $server = null;
        }

        $this->errorIfFalseWithMessageAndCode(
            $server != null,
            "No submission with such id found",
            404
        );
        $this->errorIfFalseWithMessageAndCode(
            $server->canBeRemovedBy($_SESSION["logged_user"]),
            "Unauthorized",
            403
        );

        $server_repository->deleteServer($server);

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");
    }

    public function post_submission() {
        $server_repository = new ServerRepository();
        $service_type_repo = new ServiceTypeRepository();

        session_start();

        $this->errorIfFalseWithMessage($this->isPost(), "Bad request (not a POST request)");
        $this->errorIfFalseWithMessage(isset($_SESSION) && array_key_exists("logged_user", $_SESSION), "You are not logged in!");

        $submitter = $_SESSION["logged_user"];
        $title = $_POST['title'];
        $service_type_name = $_POST['service_type'];
        $address = $_POST['address'];
        $description = $_POST['description'];

        $service_type_id = -1;
        foreach ($service_type_repo->getServiceTypes() as $type) {
            if ($type->getServiceName() == $service_type_name) {
                $service_type_id = $type->getServiceTypeId();
                break;
            }
        }

        if ($service_type_id == -1) {
            $this->render('submit-server', [
                "service_types" => $service_type_repo->getServiceTypes(),
                "service_type_message" => "Service type not selected!",
                "title" => $title,
                "address" => $address,
                "description" => $description
            ]);
            return;
        }

        if (strlen($title) < 8 || strlen($title) > 100) {
            $this->render('submit-server', [
                "service_types" => $service_type_repo->getServiceTypes(),
                "title_message" => "Server name has to be at least 8 characters long and no longer than 100 characters!",
                "service_type" => $service_type_id,
                "address" => $address,
                "description" => $description
            ]);
            return;
        }

        // TODO: Improve address-checking
        if (strlen($address) < 8) {
            $this->render('submit-server', [
                "service_types" => $service_type_repo->getServiceTypes(),
                "title" => $title,
                "service_type" => $service_type_id,
                "address_message" => "Address has to be at least 8 characters long",
                "description" => $description
            ]);
            return;
        }

        if (strlen($description) < 8) {
            $this->render('submit-server', [
                "service_types" => $service_type_repo->getServiceTypes(),
                "title" => $title,
                "service_type" => $service_type_id,
                "address" => $address,
                "description_message" => "Description has to be at least 8 characters long!"
            ]);
            return;
        }

        if (!array_key_exists("edited_server_id", $_POST)) {
            $server_repository->submitServer(
                $submitter->getUserId(),
                $title,
                $service_type_id,
                $address,
                $description
            );

            // Do not try this at home kids
            goto label_post_submission_redirect;
        }


        $id = $_POST["edited_server_id"];
        $this->errorIfFalseWithMessageAndCode(
            $id != null,
            "Not found (no id provided)",
            404
        );

        $server = $server_repository->getServerById($id);
        $this->errorIfFalseWithMessageAndCode(
            $server != null,
            "No submission with such id found",
            404
        );
        $this->errorIfFalseWithMessageAndCode(
            $server->canBeEditedBy($submitter),
            "Unauthorized",
            403
        );

        $server_repository->updateServer(
            $id,
            $title,
            $service_type_id,
            $address,
            $description
        );

label_post_submission_redirect:
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");
    }
}