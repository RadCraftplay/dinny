<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../models/User.php';

class SecurityController extends AppController {

    public function login_submit()
    {
        // TODO: Use singleton?
        $user_repository = new UserRepository();

        session_start();

        if (!$this->isPost()) {
            $this->render('error', ["message" => "Bad request (not a POST request)"]);
            http_response_code(400);
            die();
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $user_repository->getUser($email);

        if (!$user) {
            $this->render('login', ['messages' => ['User with such email does not exist']]);
            return;
        }

        $password_with_salt = $password . $user->getPasswordSalt();
        if (!password_verify($password_with_salt, $user->getPasswordHash())) {
            $this->render('login', ['messages' => ['Incorrect password']]);
            return;
        }

        $_SESSION["logged_user"] = $user;

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");
    }

    public function register_submit()
    {
        // TODO: Use singleton?
        $user_repository = new UserRepository();

        session_start();

        if (!$this->isPost()) {
            $this->render('error', ["message" => "Bad request (not a POST request)"]);
            http_response_code(400);
            die();
        }

        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password_repeated = $_POST['password_repeated'];

        // TODO: Validate email
        // TODO: Validate username
        // TODO: Validate password length
        // TODO: Validate both passwords matching

        if (!$user_repository->createUser($email, $username, $password)) {
            $this->render('error', ["message" => "Something bad happened: Can not create user"]);
            die();
        }

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }

    public function logout()
    {
        session_start();

        if (!$this->isGet()) {
            $this->render('error', ["message" => "Bad request (not a GET request)"]);
            http_response_code(400);
            die();
        }

        if (!array_key_exists("logged_user", $_SESSION)) {
            $this->render('error', ["message" => "You are not logged in!"]);
            die();
        }

        session_destroy();

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }
}