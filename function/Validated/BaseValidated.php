<?php

abstract class BaseValidated
{
    abstract public function name($name);

    abstract public function password($pass);

    public function email($data, $email)
    {
        if (empty(trim($email))) {
            $_SESSION['errCreate']['email']['invaild'] = ERR_EMAIL_INVAILD;
        } elseif (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
            $_SESSION['errCreate']['email']['invaild'] = ERR_EMAIL_FORMAT;
        } elseif (strlen(trim($email)) > 64) {
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

    abstract public function image($file);
}