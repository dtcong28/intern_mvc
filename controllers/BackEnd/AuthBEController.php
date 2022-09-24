<?php
require_once('controllers/BaseController.php');
require_once('models/AdminModel.php');
require_once('function/Validated/AdminValidated.php');

class AuthBEController extends BaseController
{
    public $model;
    public $validated;

    function __construct()
    {
        $this->folder = 'admin';
        $this->model = new AdminModel();
        $this->validated = new AdminValidated();
        $this->is_required_login = false;
    }

    public function login()
    {
        if (!empty($_POST)) {
            $email = $_POST['email'];
            $password = md5($_POST['password']);
            $data = $this->model->checkLogin($email, $password);
            $dataGetByEmailPass = $data['dataGetByEmailPass'][0];

            if (isset($dataGetByEmailPass->id)) {
                // $token = getToken();
                // var_dump($token);
                // exit;
                $_SESSION['admin'] = array(
                    "id" => $dataGetByEmailPass->id,
                    "email" => $dataGetByEmailPass->email,
                    "role_type" => $dataGetByEmailPass->role_type,
                );

                if ($dataGetByEmailPass->role_type == SUPER_ADMIN) {
                    $this->redirect('/?controller=admin&action=search');
                } else {
                    $this->redirect('/?controller=user&action=search');
                }
            } elseif (empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['errLogin']['err'] = ERROR_LOGIN_EMAIL;
                $this->redirect('/?controller=authBE&action=login');
            } else {
                $_SESSION['errLogin']['err'] = ERROR_LOGIN_PASS;
                $this->redirect('/?controller=authBE&action=login');
            }
        } else {
            // if (isset($_SESSION['admin'])) {
            //     $this->redirect('/?controller=admin&action=search');
            // }

            $this->renderNoMenu('login', [], $title = 'Admin-Login');
            // $this->redirect('/?controller=authBE&action=login');
        }
    }

    public function logout()
    {
        // session_id($_SESSION['session_id']);
        unset($_SESSION["admin"]);
        $this->redirect('/?controller=authBE&action=login');
    }
}
