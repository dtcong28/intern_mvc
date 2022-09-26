<?php
require_once('controllers/BaseController.php');
require_once('models/UserModel.php');
require_once('function/FbCallBack.php');
require_once('function/Validated/UserValidated.php');

class UserFEController extends BaseController
{
    function __construct()
    {
        $this->folder = 'user';
        $this->model = new UserModel();
        $this->validated = new UserValidated();
        $this->type = FRONT_END;
        $this->is_required_login = false;
    }
    public function profile()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect(DOMAIN.'/?controller=authFE&action=login');
        } else {
            $this->renderNoMenu('profile', [], 'User-Profile');
        }
    }

    public function create()
    {
        if (isset($_GET['code'])) {

            $fb = new Facebook\Facebook([
                'app_id' => APP_ID,
                'app_secret' => APP_SECRET,
                'default_graph_version' => DEFAULT_GRAPH_VERSION
            ]);

            $helper = $fb->getRedirectLoginHelper();
            $fbUser = FbCallBack($fb, $helper);
            $fbUser['facebook_id'] = $fbUser['id'];
            $fbUser['avatar'] = $fbUser['picture']['url'];
            unset($fbUser['id']);
            unset($fbUser['picture']);

            if (!empty($fbUser)) {
                $fields = ['id', 'facebook_id', 'name', 'avatar', 'email'];
                $objData = '';
                $data = $this->model->getByEmail($fbUser['email'], $fields);
                if (!empty($data)) {
                    $objData = $data[0];
                }
                Session::msg(LOGIN_SUCCESSFUL, 'success');
                if (!empty($objData) && $objData->facebook_id == $fbUser['facebook_id']) {
                    // Da ton tai tk fb
                    $_SESSION['user'] = array(
                        "id" => $objData->id,
                        "facebook_id" => $objData->facebook_id,
                        "name" => $objData->name,
                        "email" => $objData->email,
                        "avatar" => $objData->avatar,
                    );

                    $this->renderNoMenu('profile', ['fbUser' => $objData], 'User-Profile');
                } else {
                    // lan dau tao tk 
                    $this->model->create($fbUser);
                    $id = $this->model->lastInsertId();

                    $_SESSION['user'] = array(
                        "id" => $id,
                        "facebook_id" => $fbUser['facebook_id'],
                        "name" => $fbUser['name'],
                        "email" => $fbUser['email'],
                        "avatar" => $fbUser['avatar'],
                    );
                    $this->renderNoMenu('profile', ['fbUser' => $fbUser], 'User-Profile');
                }
            }
        }
    }
}
