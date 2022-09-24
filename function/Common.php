<?php
if (!function_exists('siteURL')) {
    function siteURL()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
            $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        return $protocol . $domainName;
    }
}

if (!function_exists('includeVariables')) {
    function includeVariables($filePath, $variables = array(), $print = true)
    {
        $output = NULL;
        //        Lưu vào bộ nhớ cache
        if (file_exists($filePath)) {
            extract($variables);
            ob_start();

            include $filePath;

            $output = ob_get_clean();
        }
        if ($print) {
            print $output;
        }
        return $output;
    }
}

if (!function_exists('getId')) {
    function getIdFromSession()
    {
        $id = '';
        if (isset($_SESSION['admin']['id']) || isset($_SESSION['user']['id'])) {
            $id = isset($_SESSION['admin']['id']) ? $_SESSION['admin']['id'] : $_SESSION['user']['id'];
        }
        return $id;
    }
}

if (!function_exists('getToken')) {
    function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }

        return $token;
    }
}
