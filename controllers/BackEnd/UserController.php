<?php
require_once('controllers/BaseController.php');
require_once('models/UserModel.php');
// require_once('function/FbCallBack.php');
require_once('function/Validated/UserValidated.php');

class UserController extends BaseController
{
    function __construct()
    {
        $this->folder = 'user';
        $this->model = new UserModel();
        $this->validated = new UserValidated();
    }

    public function edit()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $result = $this->model->getById($id, ['id']);

        if ($result && isset($_SESSION['admin'])) {
            $fields = ['id', 'avatar', 'name', 'email', 'status', 'password'];
            $oldData = $this->model->getById($id, $fields)[0];
            $path = PATH_UPLOAD_USER . $id;

            if (!empty($_POST)) {
                $data = $this->model->getByEmail(trim($_POST['email']), ['id', 'email']);
                $check = $this->validated->validateEdit($_POST, $data, $_FILES["avatar"]);

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
                    // check sai se load lai url cu 
                    $this->redirect($_SERVER['REQUEST_URI']);
                }
            } else {
                $this->render('edit', ['oldData' => $oldData], $title = 'User-Edit');
            }
        } else {
            // neu nguoi dung chua login se k hien thi thong bao 
            if (isset($_SESSION['admin'])) {
                Session::msg(NO_DATA, 'warning');
            }
            $this->redirect('/?controller=user&action=search');
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
        } else {
            Session::msg(NO_DATA, 'warning');
        }
        $this->redirect('/?controller=user&action=search');
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
