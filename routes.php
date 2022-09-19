<?php

$controllers = array(
    'admin' => ['index', 'search', 'create', 'delete', 'edit', 'login', 'logout'],
    'user'  => ['profile', 'search','create' ,'delete', 'edit', 'login' ,'logout']
);

if (!array_key_exists($controller, $controllers) || !in_array($action, $controllers[$controller])) {
    $controller = 'pages';
    $action = 'error';
}


include_once('controllers/' . ucwords($controller) . 'Controller.php');
$class = ucwords($controller) . 'Controller';
$controller = new $class;
$controller->$action();
