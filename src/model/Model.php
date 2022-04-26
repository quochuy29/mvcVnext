<?php

namespace MVC\model;

use App\core\Database;
use App\core\QueryBuilder;
use PDO;

class Model
{

    use QueryBuilder;
    
    private $connection;

    public function connection()
    {
        $this->connection = new Database();
        return $this->connection->getDatabase();
    }

    public function fill($request)
    {
        $record = array_merge($request, $this->fillable);
        foreach ($record as $key => $value) {
            if (is_int($key)) {
                unset($record[$key]);
            }
        }
        return $record;
    }

    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = $id";
        $conn = $this->connection();
        $sql = $conn->prepare($sql);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_CLASS, get_class($this));
    }

    public function insert($data)
    {
        $sql = "insert into " . $this->table;
        $sql .= "(";

        foreach ($data as $key => $col) {
            $sql .= " $key, ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= ")";
        $sql .= " values ";
        $sql .= "(";
        foreach ($data as $key => $col) {
            $sql .= "'" . $col . "', ";
        }
        $sql = rtrim($sql, ", ");
        $sql .= ")";
        $this->save($sql);
    }

    public function update($data,$id)
    {
        $sql = "update $this->table ";
		$sql .= " set ";
		foreach ($data as $key => $col) {
			$sql .= " $key = '" . $col ."', ";
		}
		$sql = rtrim($sql, ", ");
		
		$sql .= " where id = '" . $id ."' ";
        $this->save($sql);
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table $this->where";
        $conn = $this->connection();
        $sql = $conn->prepare($sql);
        $sql->execute();
    }

    public function save($sql)
    {
        $result = $this->connection()->prepare($sql);
        return $result->execute();
    }
}
