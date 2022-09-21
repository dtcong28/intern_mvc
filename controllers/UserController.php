<?php
require_once('controllers/BaseController.php');
require_once('models/UserModel.php');
require_once('function/FbCallBack.php');
require_once('function/Validated/UserValidated.php');

class UserController extends BaseController
{
    function __construct()
    {
        $this->folder = 'user';
        $this->model = new UserModel();
        $this->validated = new UserValidated();
    }

    public function login()
    {
        if (!empty($_POST)) {
            $email = $_POST['email'];
            $password = md5($_POST['password']);
            $data = $this->model->checkLogin($email, $password);
            $dataGetByEmailPass = $data['dataGetByEmailPass'][0];

            if (isset($dataGetByEmailPass->id)) {
                Session::msg(LOGIN_SUCCESSFUL, 'success');
                $_SESSION['user'] = array(
                    "id" => $dataGetByEmailPass->id,
                    "facebook_id" => $dataGetByEmailPass->facebook_id,
                    "name" => $dataGetByEmailPass->name,
                    "email" => $dataGetByEmailPass->email,
                    "avatar" => $dataGetByEmailPass->avatar,
                );

                $this->redirect('/?controller=user&action=profile');
            } elseif (empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['errLogin']['err'] = ERROR_LOGIN_EMAIL;
                $this->redirect('/?controller=user&action=login');

            } else {
                $_SESSION['errLogin']['err'] = ERROR_LOGIN_PASS;
                $this->redirect('/?controller=user&action=login');
            }
        } else {
            $fb = new Facebook\Facebook([
                'app_id' => APP_ID,
                'app_secret' => APP_SECRET,
                'default_graph_version' => DEFAULT_GRAPH_VERSION
            ]);
            $helper = $fb->getRedirectLoginHelper();

            $permission = ['email'];
            $loginUrl = $helper->getLoginUrl('https://paraline.local:80/?controller=user&action=create', $permission);
            $this->render('login', ['loginUrl' => $loginUrl], 'User-Login');
        }
    }

    public function logout()
    {
        unset($_SESSION["user"]);
        header('location: /?controller=user&action=login');
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

                    $this->render('profile', ['fbUser' => $objData], 'User-Profile');
                } else {
                    // chua ton tai tk fb
                    $this->model->create($fbUser);
                    $id = $this->model->lastInsertId();

                    $_SESSION['user'] = array(
                        "id" => $id,
                        "facebook_id" => $fbUser['facebook_id'],
                        "name" => $fbUser['name'],
                        "email" => $fbUser['email'],
                        "avatar" => $fbUser['avatar'],
                    );
                    $this->render('profile', ['fbUser' => $fbUser], 'User-Profile');
                }
            }
        }
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $fields = ['id', 'avatar', 'name', 'email', 'status', 'password'];
        $oldData = $this->model->getById($id, $fields)[0];
        $path = PATH_UPLOAD_USER . $id;

        if (!empty($_POST)) {
            $check = $this->validated->validateEdit($_POST, $_FILES["avatar"]);

            if ($check == true) {

                $avatar = $oldData->avatar;
                $password = $oldData->password;

                if (!empty($_POST['password'])) {
                    $password = md5($_POST['password']);
                }

                if ($_FILES["avatar"]["name"] != "") {
                    $avatar = time() . "_" . $_FILES["avatar"]["name"];
                    $pathNewAvatar = $path . '/' . $avatar;
                    createImage($_FILES["avatar"], $path, $pathNewAvatar);
                }
                $arrInsert = array(
                    "name" => trim($_POST['name']),
                    "email" => trim($_POST['email']),
                    "password" => $password,
                    "status" => $_POST['status'],
                    "avatar" => $avatar
                );

                $this->model->update($arrInsert, ['id' => $id]);

                Session::msg(UPDATE_SUCCESSFUL, 'success');
                $this->redirect('/?controller=user&action=search');
            } else {
                $this->render('edit', [], $title = 'User-Edit');
            }
        } else {
            $this->render('edit', ['oldData' => $oldData], $title = 'User-Edit');
        }

    }

    public function delete()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $fields = ['id'];
        $result = $this->model->getById($id, $fields);
        $path = PATH_UPLOAD_USER . $id;
        if ($result) {
            deleteImage($path);
            $this->model->delete($id);
            Session::msg(DELETE_SUCCESSFUL, 'success');
        }
        $this->redirect('/?controller=user&action=search');
    }

    public function profile()
    {
        $this->render('profile', [], 'User-Profile');
    }

    public function search()
    {
        $page = isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0 ? $_GET["page"] - 1 : 0;
        $start_from = $page * RECORDPERPAGE;

        $searchEmail = isset($_GET['searchEmail']) ? trim($_GET['searchEmail']) : '';
        $searchName = isset($_GET['searchName']) ? trim($_GET['searchName']) : '';
        $conditions = ['searchEmail' => $searchEmail, 'searchName' => $searchName];

        $columns = ['id', 'name', 'email', 'status'];
        $column = isset($_GET['column']) && in_array($_GET['column'], $columns, true) ? $_GET['column'] : $columns[0];
        $sortOrder = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'desc' : 'asc';
        $ascOrDesc = $sortOrder == 'asc' ? 'desc' : 'asc';

        $orderBy = ['column' => $column, 'sortOrder' => $sortOrder];

        if (isset($_GET['searchEmail']) && isset($_GET['searchName'])) {
            $dataResults = $this->model->resultSearch($conditions, $orderBy, $start_from, RECORDPERPAGE);
            $totalPages = ceil($dataResults['count'] / RECORDPERPAGE);
            $results = [
                'data' => $dataResults['data'],
                'totalPages' => $totalPages,
                'ascOrDesc' => $ascOrDesc,
                'sortOrder' => $sortOrder,
                'column' => $column
            ];
            $this->render('search', ['results' => $results], $title = 'User-Search');
        } else {
            $this->render('search', [], $title = 'User-Search');
        }
    }

}