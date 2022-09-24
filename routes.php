<?php
$controllers = array(
    // backend 
    'admin' => ['search', 'create', 'delete', 'edit'],
    'user'  => ['search', 'delete', 'edit'],
    'authBE' => ['login', 'logout'],

    // frontend
    'authFE' => ['login', 'logout'],
    'userFE' => ['profile', 'create'],
);
require_once('helpers/permission.php');

$fileController = [
    'fileBackEnd' => 'controllers/BackEnd/' . ucwords($controller) . 'Controller.php',
    'fileFrontEnd' => 'controllers/FrontEnd/' . ucwords($controller) . 'Controller.php',
];

foreach ($fileController as $value) {
    if (file_exists($value)) {
        require_once($value);
    }
}

$class = ucwords($controller) . 'Controller';
$controller = new $class;

// check require login 
$is_required_login = $controller->is_required_login;
$type = $controller->type;
if ($is_required_login) {
    if (!isset($_SESSION['admin']) && $type == BACK_END) {
        header('location: /?controller=authBE&action=login');
    }
    if (!isset($_SESSION['user']) && $type == FRONT_END) {
        header('location: /?controller=authFE&action=login');
    }
}

$controller->$action();
