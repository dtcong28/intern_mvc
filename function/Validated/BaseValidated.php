<?php

abstract class BaseValidated
{
    abstract public function name($name);

    abstract public function password($pass);

    public function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function email($data, $email)
    {
        $email = $this->test_input($email);
        if (empty($email)) {
            $_SESSION['errCreate']['email']['invaild'] = ERR_EMAIL_INVAILD;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['errCreate']['email']['invaild'] = ERR_EMAIL_FORMAT;
        } elseif (strlen($email) > 64) {
            $_SESSION['errCreate']['email']['invaild'] = ERR_EMAIL_BETWEEN;
        } elseif (!empty($data)) {
            $_SESSION['errCreate']['email']['invaild'] = ERR_EMAIL_EXIST;
        }
    }

    public function password_confirm($pass, $password_confirm)
    {
        if (!isset($_SESSION['errCreate']['password'])) {
            if (empty(trim($password_confirm))) {
                $_SESSION['errCreate']['confirmation_pwd']['required'] = ERR_PASSVERIFY_INVAILD;
            } elseif (strlen(trim($password_confirm)) != strlen($pass)) {
                $_SESSION['errCreate']['confirmation_pwd']['invaild'] = ERR_PASSVERIFY_CONFIRMED;
            }
        }
    }

    public function role($role)
    {
        if (empty($role)) {
            $_SESSION['errCreate']['role_type']['invaild'] = ERR_ROLE;
        }
    }

    abstract public function image($file);
}
