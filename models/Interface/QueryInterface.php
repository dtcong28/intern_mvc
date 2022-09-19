<?php

interface QueryInterface
{
    public function create($data);

    public function update($id, $data);

    public function delete($id);

    public function getById($id, $arr);

    public function getByEmail($email, $arr);

    public function getByEmailAndPass($email, $pass, $arr);

    public function lastInsertId();

    public function getByEmailOrName($email, $name);

    public function resultSearch($conditions,$startFrom, $recordPerPage);
}
