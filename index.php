<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('', 'DefaultController');
Router::get('login', 'DefaultController');
Router::get('browse', 'DefaultController');
Router::get('about', 'DefaultController');
Router::get('register', 'DefaultController');

Router::post('login_submit', 'SecurityController');
Router::post('register_submit', 'SecurityController');
Router::get('logout', 'SecurityController');

Router::get('server', 'ServerController');
Router::get('submit_server', 'ServerController');
Router::get('post_submission', 'ServerController');
Router::get('delete_server', 'ServerController');
Router::get('edit_server', 'ServerController');
Router::get('bookmark_server', 'ServerController');
Router::get('unbookmark_server', 'ServerController');
Router::post('search', 'ServerController');

Router::get('user', 'UserController');
Router::get('me', 'UserController');

Router::run($path);