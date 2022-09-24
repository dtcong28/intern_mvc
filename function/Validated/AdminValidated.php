<?php
require_once('function/validated/BaseValidated.php');
class AdminValidated extends BaseValidated
{

    public function name($name)
    {
        $name = $this->test_input($name);
        if (empty($name)) {
            $_SESSION['errCreate']['name']['invaild'] = ERR_NAME_INVAILD;
        } elseif (strlen($name) < 6 || strlen($name) > 129) {
            $_SESSION['errCreate']['name']['invaild'] = ERR_NAME_BETWEEN;
        }
    }

    public function password($pass)
    {
        if (empty(trim($pass))) {
            $_SESSION['errCreate']['password']['invaild'] = ERR_PASS_INVAILD;
        } elseif (strlen(trim($pass)) < 3 || strlen(trim($pass)) > 100) {
            $_SESSION['errCreate']['password']['invaild'] = ERR_PASS_BETWEEN;
        }
    }

    public function image($file)
    {
        if (empty($file['name'])) {
            $_SESSION['errCreate']['image']['required'] = ERR_IMG_INVAILD;
        } else {
            if ($file["size"] < 2048 || $file["size"] > 2097152) {
                $_SESSION['errCreate']['image']['invaild'] = ERR_IMG_BETWEEN;
            }
            $fileName = explode(".", $file['name']);
            $fileName = strtoupper($fileName[1]);
            if (!($fileName == "JPG" || $fileName == "JPEG" || $fileName == "PNG")) {
                $_SESSION['errCreate']['image']['invaild'] = ERR_IMG_TYPE;
            }
        }
    }

    public function validateCreate($arr, $data, $file)
    {
        $this->password($arr['password']);
        $this->email($data, $arr['email']);
        $this->name($arr['name']);
        $this->image($file);
        $this->password_confirm($arr['password'], $arr['password_confirm']);
        $this->role($arr['role_type']);

        if (!isset($_SESSION['errCreate'])) {
            return true;
        } else {
            return false;
        }
    }

    public function validateEdit($arr, $data, $file)
    {
        $this->name($arr['name']);

        if (!empty($arr['password'])) {
            $this->password($arr['password']);
            $this->password_confirm($arr['password'], $arr['password_confirm']);
        }
        if (!empty($file["name"])) {
            $this->image($file);
        }
        if (empty($arr["email"]) || $_GET['id'] != $data[0]->id) {
            $this->email($data, $arr['email']);
        }
        if (!isset($_SESSION['errCreate'])) {
            return true;
        } else {
            return false;
        }
    }
}
