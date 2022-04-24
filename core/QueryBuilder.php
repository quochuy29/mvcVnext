<?php

namespace App\core;

use PDO;

trait QueryBuilder
{
    private $where = '';
    private $operator = '';
    private $join = '';
    private $select = '*';

    public function where($field, $compare = "=", $value)
    {
        if (empty($this->operator)) {
            $this->operator = "WHERE";
        } else {
            $this->operator = "AND";
        }
        $this->where .= ' ' . $this->operator . " $field $compare '$value'";
        return $this;
    }

    public function join($table, $idTable, $operator, $idTableJoin)
    {
        $this->join .= "INNER JOIN $table ON $idTable $operator $idTableJoin";
        return $this;
    }

    public function select($selectField = '*')
    {
        $this->select = $selectField;
        return $this;
    }

    public function paginate($perPage = 15, $column = ['*'])
    {
        $request = new Request();
        $page = $request->all()['page'];
        $start = ($page > 1) ? ($page * $perPage) - $perPage : 0;
        $sql = "SELECT " . $this->select . " FROM $this->table " . $this->join . $this->where . "limit $start,$perPage";
        $conn = $this->connection();
        $sql = $conn->prepare($sql);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_CLASS, get_class($this));
    }

    public function get()
    {
        $sql = "SELECT " . $this->select . " FROM $this->table " . $this->join . $this->where;
        $conn = $this->connection();
        $sql = $conn->prepare($sql);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_CLASS, get_class($this));
    }
}
