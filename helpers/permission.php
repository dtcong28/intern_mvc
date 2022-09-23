<?php
// require login 
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

// check role 
// $admin = ['user', 'authBE'];
// $superAdmin = ['admin', 'user', 'authBE'];
// $user = ['userFE', 'authFE'];

// if (isset($_SESSION["admin"])) {
//     if (isset($_SESSION["admin"]['role_type'])) {
//         $permission = $_SESSION["admin"]['role_type'];

//         if ($permission == ADMIN) {
//             if (!in_array($_GET['controller'], $admin)) {
//                 echo NO_PERMISSION . "<br>";
//                 echo "<a href='javascript:history.back()'>" . BACK . "</a>";
//                 exit();
//             }
//         } else {
//             if (!in_array($_GET['controller'], $superAdmin)) {

//                 echo NO_PERMISSION . "<br>";
//                 echo "<a href='javascript:history.back()'> " . BACK . "</a>";
//                 exit();
//             }
//         }
//     }
// }
// if (isset($_SESSION["user"])) {
//     if (!in_array($_GET['controller'], $user)) {
//         echo NO_PERMISSION . "<br>";
//         echo "<a href='javascript:history.back()'>" . BACK . "</a>";
//         exit();
//     }
// }
