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
        $this->token = new TokenModel();
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
                $token = getToken(10);

                $_SESSION['admin'] = array(
                    "id" => $dataGetByEmailPass->id,
                    "email" => $dataGetByEmailPass->email,
                    "role_type" => $dataGetByEmailPass->role_type,
                    "token" => $token
                );

                $checkToken = $this->token->getByEmail($email, ['id', 'account_name', 'token']);

                $dataToken = [
                    'account_name' => $email,
                    'token' => $token,
                    'timemodified' => date('Y-m-d H:i:s')
                ];

                if (empty($checkToken)) {
                    $this->token->create($dataToken);
                } else {
                    $this->token->update($dataToken, ['account_name' => $email]);
                }

                if ($dataGetByEmailPass->role_type == SUPER_ADMIN) {
                    $this->redirect(DOMAIN.'/?controller=admin&action=search');
                } else {
                    $this->redirect(DOMAIN.'/?controller=user&action=search');
                }
            } elseif (empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['errLogin']['err'] = ERROR_LOGIN_EMAIL;
                $this->redirect(DOMAIN.'/?controller=authBE&action=login');
            } else {
                $_SESSION['errLogin']['err'] = ERROR_LOGIN_PASS;
                $this->redirect(DOMAIN.'/?controller=authBE&action=login');
            }
        } else {
            $this->renderNoMenu('login', [], $title = 'Admin-Login');
        }
    }

    public function logout()
    {
        unset($_SESSION["admin"]);
        $this->redirect(DOMAIN.'/?controller=authBE&action=login');
    }
}
