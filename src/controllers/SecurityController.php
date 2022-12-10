<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';

class SecurityController extends AppController {

    public function login_submit()
    {
        if (!$this->isPost()) {
            $this->render('login');
            die();
        }

        $user = new User('johndoe@gmail.com', 'johndoe', 'admin');

        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($user->getUsername() !== $username) {
            $this->render('login', ['messages' => ['User with this username does not exist']]);
            return;
        }
        if ($user->getPassword() !== $password) {
            $this->render('login', ['messages' => ['Incorrect password']]);
            return;
        }

        // TODO: Use session to keep username
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/");
    }
}