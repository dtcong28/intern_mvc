<?php


if (!array_key_exists($controller, $controllers) || !in_array($action, $controllers[$controller])) {
    // $controller = 'pages';
    // $action = 'error';
    echo NO_PERMISSION . "<br>";
    echo "<a href='javascript:history.back()'> " . BACK . "</a>";
    exit();
}

// check role 
$admin = ['user', 'authBE'];
$superAdmin = ['admin', 'user', 'authBE'];
$user = ['userFE', 'authFE'];

if (isset($_SESSION["admin"])) {
    if (isset($_SESSION["admin"]['role_type'])) {
        $permission = $_SESSION["admin"]['role_type'];
     
        if ($permission == ADMIN) {
            if (!in_array($_GET['controller'], $admin)) {
                echo NO_PERMISSION . "<br>";
                echo "<a href='javascript:history.back()'>" . BACK . "</a>";
                exit();
            }
        } else {
            if (!in_array($_GET['controller'], $superAdmin)) {

                echo NO_PERMISSION . "<br>";
                echo "<a href='javascript:history.back()'> " . BACK . "</a>";
                exit();
            }
        }
    }
}
if (isset($_SESSION["user"])) {
    if (!in_array($_GET['controller'], $user)) {
        echo NO_PERMISSION . "<br>";
        echo "<a href='javascript:history.back()'>" . BACK . "</a>";
        exit();
    }
}
