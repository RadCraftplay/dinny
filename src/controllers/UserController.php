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
            'users_servers' => $servers
        ]);
    }
}