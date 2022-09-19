<?php
require_once('controllers/BaseController.php');
require_once('models/AdminModel.php');
require_once('function/Validated/AdminValidated.php');

class AdminController extends BaseController
{
    public $model;
    public $validated;

    function __construct()
    {
        $this->folder = 'admin';
        $this->model = new AdminModel();
        $this->validated = new AdminValidated();
    }

    public function create()
    {
        if (!empty($_POST)) {
            $fields = ['id'];
            $data = $this->model->getByEmail($_POST['email'], $fields);

            $check = $this->validated->validateCreate($_POST, $data, $_FILES["avatar"]);
            if ($check == true) {
                $avatar = "";
                $password = md5($_POST['password']);

                if ($_FILES["avatar"]["name"] != "") {
                    $avatar = time() . "_" . $_FILES["avatar"]["name"];
                }
                $arrInsert = array(
                    "name" => trim($_POST['name']),
                    "email" => trim($_POST['email']),
                    "password" => $password,
                    "role_type" => $_POST['role_type'],
                    "avatar" => $avatar
                );

                $conn = $this->model->create($arrInsert);
                $id = $this->model->lastInsertId();
                $path = PATH_UPLOAD_ADMIN . $id;
                $newPath = $path . '/' . $avatar;
                createImage($_FILES["avatar"], $path, $newPath);

                Session::msg(CREATE_SUCCESSFUL, 'success');
                $this->redirect('/?controller=admin&action=search');
            } else {
                $this->render('create', [], $title = 'Admin-Create');
            }
        } else {
            $this->render('form', [], $title = 'Admin-Create');
        }
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $fields = ['id', 'avatar', 'name', 'email', 'role_type', 'password'];
        $oldData = $this->model->getById($id, $fields)[0];
        $path = PATH_UPLOAD_ADMIN . $id;

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
                    updateImage($_FILES["avatar"], $path, $pathNewAvatar);
                }
                $arrInsert = array(
                    "name" => trim($_POST['name']),
                    "email" => trim($_POST['email']),
                    "password" => $password,
                    "role_type" => $_POST['role_type'],
                    "avatar" => $avatar
                );

                $this->model->update($arrInsert, ['id' => $id]);

                Session::msg(UPDATE_SUCCESSFUL, 'success');
                $this->redirect('/?controller=admin&action=search');
            } else {
                $this->render('create', [], $title = 'Admin-Edit');
            }
        } else {
            $this->render('form', ['oldData' => $oldData], $title = 'Admin-Edit');
        }
    }

    public function login()
    {
        if (!empty($_POST)) {
            $email = $_POST['email'];
            $password = md5($_POST['password']);
            $data = $this->model->checkLogin($email, $password);
            $dataGetByEmailPass = $data['dataGetByEmailPass'][0];

            if (isset($dataGetByEmailPass->id)) {
                $_SESSION['admin'] = array(
                    "id" => $dataGetByEmailPass->id,
                    "email" => $dataGetByEmailPass->email,
                    "role_type" => $dataGetByEmailPass->role_type
                );
                $this->redirect('/?controller=admin&action=index');
            } elseif (empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['errLogin']['err'] = ERROR_LOGIN_EMAIL;
                $this->redirect('/?controller=admin&action=login');

            } else {
                $_SESSION['errLogin']['err'] = ERROR_LOGIN_PASS;
                $this->redirect('/?controller=admin&action=login');
            }
        } else {
            if (isset($_SESSION['admin'])) {
                $this->redirect('/?controller=admin&action=index');
            }
            $this->render('login', [], $title = 'Admin-Login');
        }

    }

    public function index()
    {
        $this->render('index', [], $title = 'Admin-Index');
    }

    public function logout()
    {
        unset($_SESSION["admin"]);
        $this->redirect('/?controller=admin&action=login');
    }

    public function search()
    {
        $page = isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0 ? $_GET["page"] - 1 : 0;
        $start_from = $page * RECORDPERPAGE;

        $searchEmail = isset($_GET['searchEmail']) ? trim($_GET['searchEmail']) : '';
        $searchName = isset($_GET['searchName']) ? trim($_GET['searchName']) : '';
        $conditions = ['searchEmail' => $searchEmail, 'searchName' => $searchName];

//        $columns = ['id', 'name', 'email', 'role_type'];
//        $column = isset($_GET['column']) && in_array($_GET['column'], $columns, true) ? $_GET['column'] : $columns[0];
//        $sort_order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'desc' : 'asc';
//        $asc_or_desc = $sort_order == 'asc' ? 'desc' : 'asc';

        if (isset($_GET['searchEmail']) && isset($_GET['searchName'])) {
            $dataResults = $this->model->resultSearch($conditions, $start_from, RECORDPERPAGE);
            $total_pages = ceil($dataResults['count'] / RECORDPERPAGE);
            $results = [
                'data' => $dataResults['data'],
                'total_pages' => $total_pages,
            ];
            $this->render('search', ['results' => $results], $title = 'Admin-Search');
        } else {
            $this->render('search', [], $title = 'Admin-Search');
        }

    }

    public function delete()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $fields = ['id'];
        $result = $this->model->getById($id, $fields);
        $path = PATH_UPLOAD_ADMIN . $id;
        if ($result) {
            deleteImage($path);
            $this->model->delete($id);
            if ($_SESSION['admin']['id'] == $id) {
                unset($_SESSION['admin']);
            } else {
                Session::msg(DELETE_SUCCESSFUL, 'success');
            }
        }
        $this->redirect('/?controller=admin&action=search');
    }


}
