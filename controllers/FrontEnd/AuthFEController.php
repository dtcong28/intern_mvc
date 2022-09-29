<?php
require_once('controllers/BaseController.php');
require_once('models/UserModel.php');
require_once('function/Validated/UserValidated.php');

class AuthFEController extends BaseController
{
    function __construct()
    {
        $this->folder = 'user';
        $this->model = new UserModel();
        $this->validated = new UserValidated();
        $this->token = new TokenModel();
        $this->is_required_login = false;
        $this->type = FRONT_END;
    }

    public function login()
    {
        if(isset($_SESSION['user'])) {
            // var_dump('1');
            // exit;
            $this->redirect(DOMAIN.'/?controller=userFE&action=profile');
        }
        if (!empty($_POST)) {
            $email = $_POST['email'];
            $password = md5($_POST['password']);
            $data = $this->model->checkLogin($email, $password);
            $dataGetByEmailPass = $data['dataGetByEmailPass'][0];

            if (isset($dataGetByEmailPass->id)) {
                Session::msg(LOGIN_SUCCESSFUL, 'success');
                $token = getToken(10);

                $_SESSION['user'] = array(
                    "id" => $dataGetByEmailPass->id,
                    "facebook_id" => $dataGetByEmailPass->facebook_id,
                    "name" => $dataGetByEmailPass->name,
                    "email" => $dataGetByEmailPass->email,
                    "avatar" => $dataGetByEmailPass->avatar,
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

                $this->redirect(DOMAIN.'/?controller=userFE&action=profile');
            } elseif (empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['errLogin']['err'] = ERROR_LOGIN_EMAIL;
                $this->redirect(DOMAIN.'/?controller=authFE&action=login');
            } else {
                $_SESSION['errLogin']['err'] = ERROR_LOGIN_PASS;
                $this->redirect(DOMAIN.'/?controller=authFE&action=login');
            }
        } else {
            
            $fb = new Facebook\Facebook([
                'app_id' => APP_ID,
                'app_secret' => APP_SECRET,
                'default_graph_version' => DEFAULT_GRAPH_VERSION
            ]);
            $helper = $fb->getRedirectLoginHelper();

            $permission = ['email'];
            $loginUrl = $helper->getLoginUrl( DOMAIN.'/?controller=userFE&action=create', $permission);

            $this->renderNoMenu('login', ['loginUrl' => $loginUrl], 'User-Login');
        }
    }

    public function logout()
    {
        unset($_SESSION["user"]);
        header('location: '.DOMAIN.'/?controller=authFE&action=login');
    }
}
