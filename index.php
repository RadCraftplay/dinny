<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('', 'DefaultController');
Router::get('login', 'DefaultController');
Router::get('about', 'DefaultController');
Router::get('logout', 'SecurityController');
Router::post('login_submit', 'SecurityController');
Router::post('register_submit', 'SecurityController');
Router::get('register', 'DefaultController');
Router::get('server', 'DefaultController');
Router::get('submit_server', 'DefaultController');
Router::get('post_submission', 'DefaultController');
Router::get('delete_server', 'DefaultController');
Router::get('user', 'UserController');
Router::run($path);