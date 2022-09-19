<?php

include_once("BaseModel.php");

class UserModel extends BaseModel
{
    protected static $table = "users";

    public function checkLogin($email, $password)
    {
        $fields = ['id', 'email', 'name', 'avatar', 'facebook_id'];
        $dataGetByEmailPass = $this->getByEmailAndPass($email, $password, $fields);
        return ['dataGetByEmailPass' => $dataGetByEmailPass];
    }
}