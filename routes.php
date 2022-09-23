<?php

$controllers = array(
    // backend 
    'admin' => ['search', 'create', 'delete', 'edit'],
    'user'  => ['search', 'delete', 'edit'],
    'authBE' => ['login', 'logout'],

    // frontend
    'authFE' => ['login', 'logout'],
    'userFE' => ['profile', 'create']
);
if (!array_key_exists($controller, $controllers) || !in_array($action, $controllers[$controller])) {
    // $controller = 'pages';
    // $action = 'error';
    echo NO_PERMISSION . "<br>";
    echo "<a href='javascript:history.back()'> " . BACK . "</a>";
    exit();
}

$fileController = [
    'fileBackEnd' => 'controllers/BackEnd/' . ucwords($controller) . 'Controller.php',
    'fileFrontEnd' => 'controllers/FrontEnd/' . ucwords($controller) . 'Controller.php',
];

foreach ($fileController as $value) {
    if (file_exists($value)) {
        require_once($value);
    }
}

// include_once('controllers/' . ucwords($controller) . 'Controller.php');
$class = ucwords($controller) . 'Controller';
$controller = new $class;
$controller->$action();
