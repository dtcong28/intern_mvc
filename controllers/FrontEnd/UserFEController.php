<?php
require_once('controllers/BaseController.php');
require_once('models/UserModel.php');
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
            $this->redirect(DOMAIN . '/?controller=authFE&action=login');
        } else {
            $this->renderNoMenu('profile', [], 'User-Profile');
        }
    }

    public function create()
    {
        $fb = new Facebook\Facebook([
            'app_id' => APP_ID,
            'app_secret' => APP_SECRET,
            'default_graph_version' => DEFAULT_GRAPH_VERSION
        ]);

        $helper = $fb->getRedirectLoginHelper();

        // call back 
        if (isset($_GET['state'])) {
            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
        }

        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {

            // echo 'Graph returned an error: ' . $e->getMessage();
            // header("Location:".DOMAIN."/?controller=userFE&action=profile");
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {

            // echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        $oAuth2Client = $fb->getOAuth2Client();
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        $tokenMetadata->validateAppId(APP_ID);
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                // echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                // header("Location:".DOMAIN."/?controller=userFE&action=profile");
                exit;
            }

            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
        }

        $_SESSION['fb_access_token'] = (string)$accessToken;

        $response = $fb->get('/me?fields=id,name,email,picture', $accessToken->getValue());
        $fbUser = $response->getGraphUser();
        $fbUser = json_decode($fbUser, true);


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
            }

            $this->redirect(DOMAIN . '/?controller=userFE&action=profile');
        }
    }
}
