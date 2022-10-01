<?php
if (!array_key_exists($controller, $controllers) || !in_array($action, $controllers[$controller])) {
    echo NO_PERMISSION . "<br>";
    echo "<a href='javascript:history.back()'> " . BACK . "</a>";
    exit();
}

// check role 
$admin = ['user', 'authBE'];
$superAdmin = ['admin', 'user', 'authBE', 'authFE', 'userFE'];
$user = ['userFE', 'authFE', 'authBE'];

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
} else if (isset($_SESSION["user"])) {
    $controller = isset($_GET['controller']) ? $_GET['controller'] : 'authFE';
    if (!in_array($controller, $user)) {
        echo NO_PERMISSION . "<br>";
        echo "<a href='javascript:history.back()'>" . BACK . "</a>";
        exit();
    }
}
