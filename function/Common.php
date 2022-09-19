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

//if (!function_exists('getImgUrl')) {
//    function getImgUrl($imgUrl)
//    {
//        $url = siteURL();
//        return $url . '/' . $imgUrl;
//    }
//}

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
?>