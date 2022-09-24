<?php

include_once("BaseModel.php");

class AdminModel extends BaseModel
{
    protected static $table = "admin";

    public function checkLogin($email, $password)
    {
        $fields = ['id', 'email', 'role_type'];
        $dataGetByEmailPass = $this->getByEmailAndPass($email, $password, $fields);
        return ['dataGetByEmailPass' => $dataGetByEmailPass];
    }
}
