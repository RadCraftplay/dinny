<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('index', 'DefaultController');
Router::get('login', 'DefaultController');
Router::get('register', 'DefaultController');
Router::get('server', 'DefaultController');
Router::get('submit_server', 'DefaultController');
Router::run($path);