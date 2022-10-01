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
            $_SESSION['dataInput'] = $_POST;
            $fields = ['id'];
            $data = $this->model->getByEmail($_POST['email'], $fields);

            // xử lý upload ảnh vào folder tmp
            uploadImageToTmp($_FILES["avatar"]);

            $check = $this->validated->validateCreate($_POST, $data, $_FILES["avatar"]);
            if ($check == true) {
                // xử lý lấy tên ảnh (khi lần đầu tạo thì lấy từ FILE,
                // khi img pass mà các trường khác k pass validate thì lấy tên trên session)
                if ($_FILES["avatar"]["name"] != "") {
                    $avatar = $_FILES["avatar"]["name"];
                } else {
                    $avatar = $_SESSION['dataInput']['tmp_avatar'];
                }

                $password = md5($_POST['password']);
                $arrInsert = array(
                    "name" => trim($_POST['name']),
                    "email" => trim($_POST['email']),
                    "password" => $password,
                    "role_type" => $_POST['role_type'],
                    "avatar" => $avatar
                );

                $this->model->create($arrInsert);

                $id = $this->model->lastInsertId();

                $path = PATH_UPLOAD_ADMIN . $id;
                $newPath = $path . '/' . $avatar;
                createFolder($path);

                $tempPath = PATH_UPLOAD_TMP . $avatar;
                $adminPath = PATH_UPLOAD_ADMIN . $id . '/' . $avatar;
                moveImage($tempPath, $adminPath);

                Session::msg(CREATE_SUCCESSFUL, 'success');
                unset($_SESSION['dataInput']);

                $this->redirect(DOMAIN . '/?controller=admin&action=search');
            } else {
                $this->redirect(DOMAIN . '/?controller=admin&action=create');
            }
        } else {
            $this->render('form', [], $title = 'Admin-Create');
        }
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        $result = $this->model->getById($id, ['id']);

        // them && session de check khi nguoi dung past link url de truy cap ma chua login
        if ($result && isset($_SESSION['admin'])) {
            $fields = ['id', 'avatar', 'name', 'email', 'role_type', 'password'];
            $oldData = $this->model->getById($id, $fields)[0];

            if (!empty($_POST)) {
                $_SESSION['dataInput'] = $_POST;
                $data = $this->model->getByEmail(trim($_POST['email']), ['id', 'email']);
                $check = $this->validated->validateEdit($_POST, $data, $_FILES["avatar"]);

                uploadImageToTmp($_FILES["avatar"]);
                if ($check == true) {

                    $password = $oldData->password;

                    if (!empty($_POST['password'])) {
                        $password = md5($_POST['password']);
                    }

                    if ($_FILES["avatar"]["name"] != "") {
                        $avatar = $_FILES["avatar"]["name"];
                    } else if (isset($_SESSION['dataInput']['tmp_avatar'])) {
                        $avatar = $_SESSION['dataInput']['tmp_avatar'];
                    } else {
                        $avatar = $oldData->avatar;
                    }

                    $tempPath = PATH_UPLOAD_TMP . $avatar;
                    $adminPath = PATH_UPLOAD_ADMIN . $id . '/' . $avatar;
                    moveImage($tempPath, $adminPath);

                    $arrInsert = array(
                        "name" => trim($_POST['name']),
                        "email" => trim($_POST['email']),
                        "password" => $password,
                        "role_type" => $_POST['role_type'],
                        "avatar" => $avatar
                    );

                    $this->model->update($arrInsert, ['id' => $id]);

                    Session::msg(UPDATE_SUCCESSFUL, 'success');
                    unset($_SESSION['dataInput']);

                    $this->redirect(DOMAIN . '/?controller=admin&action=search&searchEmail=&searchName=&page=1&column=id&order=asc');
                } else {
                    // check sai se load lai url cu
                    $this->redirect($_SERVER['REQUEST_URI']);
                }
            } else {
                $this->render('form', ['oldData' => $oldData], $title = 'Admin-Edit');
            }
        } else {
            // neu nguoi dung chua login se k hien thi thong bao 
            if (isset($_SESSION['admin'])) {
                Session::msg(NO_DATA, 'warning');
            }
            $this->redirect(DOMAIN . '/?controller=admin&action=search&searchEmail=&searchName=&page=1&column=id&order=asc');
        }
    }

    public function search()
    {
        $page = isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0 ? $_GET["page"] - 1 : 0;
        $start_from = $page * RECORDPERPAGE;

        $searchEmail = isset($_GET['searchEmail']) ? trim($_GET['searchEmail']) : '';
        $searchName = isset($_GET['searchName']) ? trim($_GET['searchName']) : '';
        $conditions = ['searchEmail' => $searchEmail, 'searchName' => $searchName];

        $columns = ['id', 'name', 'email', 'role_type'];
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
        } else {
            Session::msg(NO_DATA, 'warning');
        }
        $this->redirect(DOMAIN . '/?controller=admin&action=search');
    }
}
