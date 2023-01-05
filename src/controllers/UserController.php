<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/ServerRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/Server.php';

class UserController extends AppController {
    public function user() {
        $server_repository = new ServerRepository();
        $user_repository = new UserRepository();
        $service_type_repository = new ServiceTypeRepository();

        session_start();

        $this->errorIfFalseWithMessage(
            array_key_exists("id", $_GET) != null,
            "Bad request (no id provided)");

        $id = $_GET["id"];
        $this->errorIfFalseWithMessageAndCode(
            $id != null,
            "Not found (empty id provided)",
            404);

        $user = $user_repository->getUserById($id);
        $this->errorIfFalseWithMessageAndCode(
            $user != null,
            "Not found (user with provided id does not exist)",
            404);

        $servers = $server_repository->getServersBySubmitterId($user->getUserId());

        $this->render('user-profile', [
            'user' => $user,
            'users_servers' => $servers,
            "service_types" => $service_type_repository->getServiceTypes()
        ]);
    }

    public function me() {
        session_start();

        $this->errorIfFalseWithMessageAndCode(
            isset($_SESSION) && array_key_exists("logged_user", $_SESSION),
            "You can not go to your profile if you are not logged in!",
            404
        );


        $user = $_SESSION["logged_user"];
        $id = $user->getUserId();

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/user?id={$id}");
    }
}