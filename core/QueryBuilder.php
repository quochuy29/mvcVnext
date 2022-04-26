<?php

namespace App\core;

use PDO;

trait QueryBuilder
{
    private $where = '';
    private $operator = '';
    private $join = '';
    private $select = '*';
    public $totalPage;
    private $orderBy = " ORDER BY id ASC";
    public $page;

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

    public function paginate($perPage = 15)
    {
        $request = new Request();
        $this->page = $request->all()['page'];
        $prev = (int)$request->all()['prev'];
        $next = (int)$request->all()['next'];

        if ($prev < 0) {
            $this->page = $this->page + $prev;
        }

        if ($next > 0) {
            $this->page = $this->page + $next;
        }
        $start = ($this->page > 1) ? ($this->page * $perPage) - $perPage : 0;
        $total = (int)$this->fetch("SELECT COUNT(*) FROM $this->table $this->where")[0];
        $this->totalPage = ceil($total / $perPage);
        $sql = "SELECT " . $this->select . " FROM $this->table " . $this->join . $this->where . $this->orderBy . " limit $start,$perPage";
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

    public function fetch($sql)
    {
        $conn = $this->connection();
        $sql = $conn->prepare($sql);
        $sql->execute();
        return $sql->fetch();
    }

    public function first(){
        $sql = "SELECT " . $this->select . " FROM $this->table " . $this->join . $this->where;
        $conn = $this->connection();
        $sql = $conn->prepare($sql);
        $sql->execute();
        return $sql->fetchObject(get_class($this));
    }

    public function orderBy($field = "id", $operator = "ASC")
    {
        $this->orderBy .= " ORDER BY $field $operator";
        return $this;
    }
}
