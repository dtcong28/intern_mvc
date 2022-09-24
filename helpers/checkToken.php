<?php
if (isset($_SESSION['admin'])) {
    $db = DB::getInstance();
    $sql = " SELECT token FROM account_token WHERE account_name =:_account_name";
    $result = $db->query($sql, array('_account_name' => $_SESSION['admin']['email']))->results();
    if (!empty($result)) {
        $token = $result[0]->token;
        if ($_SESSION['admin']['token'] != $token) {
            unset($_SESSION["admin"]);
            header('Location: /?controller=authBE&action=login');
            
        }
    }
}

if (isset($_SESSION['user'])) {
    $db = DB::getInstance();
    $sql = " SELECT token FROM account_token WHERE account_name =:_account_name";
    $result = $db->query($sql, array('_account_name' => $_SESSION['user']['email']))->results();
    if (!empty($result)) {
        $token = $result[0]->token;
        if ($_SESSION['user']['token'] != $token) {
            unset($_SESSION["user"]);
            header('Location: /?controller=authBE&action=login');
            
        }
    }
}

// if(!empty(!empty($_SESSION['admin'])) || !empty($_SESSION['user'])) {
//     $sesion = !empty($_SESSION['admin']) ? $_SESSION['admin'] : $_SESSION['user'];
// }

// if (isset($sesion)) {
//     $db = DB::getInstance();
//     $sql = " SELECT token FROM account_token WHERE account_name =:_account_name";
//     $result = $db->query($sql, array('_account_name' => $sesion['email']))->results();
//     if (!empty($result)) {
//         $token = $result[0]->token;
//         if ($sesion['token'] != $token) {
//             unset($sesion);
//             header('Location: /?controller=authBE&action=login');
            
//         }
//     }
// }
