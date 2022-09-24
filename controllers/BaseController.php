<?php
require_once('function/UploadImages.php');
require_once('function/Common.php');
require_once('models/TokenModel.php');
class BaseController
{
    protected $folder;
    public $is_required_login = true; // true => phai login để vào đc trang, false k cần login  
    public $type = BACK_END;

    function render($file, $data = array(), $title)
    {
        $view_file = 'views/' . $this->folder . '/' . $file . '.php';
        if (is_file($view_file)) {
            extract($data);
            ob_start();
            require_once($view_file);
            $content = ob_get_clean();

            require_once('views/layouts/application.php');
        } else {
            header('Location: index.php?controller=pages&action=error');
        }
    }

    function renderNoMenu($file, $data = array(), $title)
    {
        $view_file = 'views/' . $this->folder . '/' . $file . '.php';
        if (is_file($view_file)) {
            extract($data);
            ob_start();
            require_once($view_file);
            $content = ob_get_clean();

            require_once('views/layouts/applicationNoMenu.php');
        } else {
            header('Location: index.php?controller=pages&action=error');
        }
    }

    public function redirect($path)
    {
        header("Location:" . $path);
    }
}
