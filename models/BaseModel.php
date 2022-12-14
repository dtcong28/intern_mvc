<?php
require_once('models/Interface/QueryInterface.php');

abstract class BaseModel implements QueryInterface
{
    protected static $table = "";
    protected static $columns = false;
    protected $db;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function create($data)
    {
        $data = array_merge($data, [
            'ins_id' => getIdFromSession(),
            'upd_id' => NULL,
            'upd_datetime' => date('Y-m-d H:i:s'),
            'ins_datetime' => date('Y-m-d H:i:s')
        ]);

        return $this->db->insert(static::$table, $data);
    }

    public function update($values, $conditions)
    {
        $data = array_merge($values, [
            'upd_id' => getIdFromSession(),
            'ins_datetime' => date('Y-m-d H:i:s'),
            'upd_datetime' => date('Y-m-d H:i:s')
        ]);
        return $this->db->update(static::$table, $data, $conditions);
    }

    public function delete($id)
    {
        $values = ['del_flag' => DELETED, 'upd_datetime' => date('Y-m-d H:i:s')];
        $conditions = ['id' => $id];
        return $this->db->update(static::$table, $values, $conditions);
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function getByEmail($email, $arr)
    {
        $fields = implode(", ", $arr);
        $table = static::$table;
        $sql = "SELECT $fields FROM {$table} WHERE email =:_email AND del_flag =:_del_flag";

        return $this->db->query($sql, array('_email' => $email, '_del_flag' => ACTIVE))->results();
    }

    public function getByEmailAndPass($email, $pass, $arr)
    {
        $fields = implode(", ", $arr);
        $table = static::$table;
        $sql = "SELECT $fields FROM {$table} WHERE  email =:_email AND password =:_password AND del_flag =:_del_flag";

        return $this->db->query($sql, array('_email' => $email, '_password' => $pass, '_del_flag' => ACTIVE))->results();
    }

    public function getByEmailOrName($email, $name)
    {
        $table = static::$table;
        $conditionStr = "";
        $binds = ['_del_flag' => ACTIVE];

        if (!empty($email) && empty($name)) {
            $conditionStr = "WHERE email LIKE %:_email% AND del_flag =:_del_flag";
            $binds = array_merge($binds, ['_email' => $email]);
        } elseif (empty($email) && !empty($name)) {
            $conditionStr = "WHERE name LIKE %:_name% AND del_flag =:_del_flag";
            $binds = array_merge($binds, ['_name' => $name]);
        } elseif (!empty($email) && !empty($name)) {
            $conditionStr = "WHERE name LIKE %:_name% AND email LIKE %:_email% AND del_flag =:_del_flag";
            $binds = array_merge($binds, ['_email' => $email, '_name' => $name]);;
        } else {
            $conditionStr = "WHERE del_flag =:_del_flag";
        }

        $sql = "SELECT * FROM {$table} $conditionStr";
        return $this->db->query($sql, $binds)->results();
    }

    public function getById($id, $arr)
    {
        $fields = implode(", ", $arr);
        $table = static::$table;
        $sql = "SELECT $fields FROM {$table} WHERE  id =:_id AND del_flag =:_del_flag";

        return $this->db->query($sql, array('_id' => $id, '_del_flag' => ACTIVE))->results();
    }


    public function resultSearch($conditions, $orerBy, $startFrom, $recordPerPage)
    {
        $searchName = isset($conditions["searchName"]) ? $conditions["searchName"] : "";
        $searchEmail = isset($conditions["searchEmail"]) ? $conditions["searchEmail"] : "";

        $table = static::$table;
        $conditionStr = " del_flag =:_del_flag";

        $sqlOrder = 'order by ' . $orerBy['column'] . ' ' . $orerBy['sortOrder'];

        $binds = ['_del_flag' => ACTIVE];

        if (!empty($searchEmail) && empty($searchName)) {
            $conditionStr = "WHERE email LIKE CONCAT('%', :_email, '%') AND" . $conditionStr;
            $binds = array_merge($binds, ['_email' => $searchEmail]);
        } elseif (empty($searchEmail) && !empty($searchName)) {
            $conditionStr = "WHERE name LIKE CONCAT('%', :_name, '%') AND" . $conditionStr;
            $binds = array_merge($binds, ['_name' => $searchName]);
        } elseif (!empty($searchEmail) && !empty($searchName)) {
            $conditionStr = "WHERE name LIKE CONCAT('%', :_name, '%') AND email LIKE CONCAT('%', :_email, '%') AND " . $conditionStr;
            $binds = array_merge($binds, ['_email' => $searchEmail, '_name' => $searchName]);;
        } else {
            $conditionStr = "WHERE " . $conditionStr;
        }

        $sql = "SELECT * FROM {$table} $conditionStr $sqlOrder";
        $count = $this->db->query($sql, $binds)->count();
       
        $data = $this->db->query("$sql LIMIT $startFrom,$recordPerPage", $binds)->results();

        return ["data" => $data, "count" => $count];
    }
}
