<?php
if (!function_exists('createFolder')) {
    function createFolder($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }
}

if (!function_exists('createImage')) {
    function createImage($file, $path, $newPath)
    {
        if ($file["name"] != "") {
            createFolder($path);
            move_uploaded_file($file["tmp_name"], $newPath);
        }
    }
}

if (!function_exists('updateImage')) {
    function updateImage($file, $path, $pathNewAvatar)
    {
        $files = glob($path . '/*');
        unlink($files[0]);
        move_uploaded_file($file["tmp_name"], $pathNewAvatar);
    }
}

if (!function_exists('deleteImage')) {
    function deleteImage($path)
    {
        $files = glob($path . '/*');
        if (!empty($files)) {
            foreach ($files as $file) {
                unlink($file);
            }
        }
        rmdir($path);
    }
}