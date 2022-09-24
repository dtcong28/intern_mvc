<?php

include_once("BaseModel.php");

class TokenModel extends BaseModel
{
    protected static $table = "account_token";

    // override
    public function getByEmail($account_name, $arr)
    {
        $fields = implode(", ", $arr);
        $table = static::$table;
        $sql = "SELECT $fields FROM {$table} WHERE account_name =:_account_name ";
        return $this->db->query($sql, array('_account_name' => $account_name))->results();
    }

    // override 
    public function create($data)
    {
        return $this->db->insert(static::$table, $data);
    }

    // override 
    public function update($values, $conditions)
    {
        return $this->db->update(static::$table, $values, $conditions);
    }
}