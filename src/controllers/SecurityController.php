<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../models/User.php';

class SecurityController extends AppController {

    public function login_submit()
    {
        $user_repository = new UserRepository();

        session_start();

        $this->errorIfFalseWithMessage($this->isPost(), "Bad request (not a POST request)");

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

    private static function isValidEmail($str): bool
    {
        return !!preg_match(
            "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $str);
    }

    private static function isValidUsername($str): bool
    {
        return !!preg_match(
            "^[_a-z0-9-]+$^", $str);
    }

    private static function isValidPassword($str): bool
    {
        return !!preg_match(
            "^(?=.{8,48})(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[,.<>!#$%&? \"])^", $str);
    }

    public function register_submit()
    {
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

        if (!self::isValidEmail($email)) {
            $this->render('register', ['messages' => ['The provided email is invalid']]);
            return;
        }
        if (!self::isValidUsername($username)) {
            $this->render('register', ['messages' => ['Invalid username! Allowed characters are: a-z, A-Z, 0-9, -, _']]);
            return;
        }
        if (!self::isValidPassword($password)) {
            $this->render('register', ['messages' => ['Password has to be at least 8 characters long, and maximum 48 characters long']]);
            return;
        }
        if ($password != $password_repeated) {
            $this->render('register', ['messages' => ['Passwords do not match!']]);
            return;
        }

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