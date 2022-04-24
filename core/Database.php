<?php

namespace App\core;

use PDO;

class Database{
    public function getDatabase()
    {
        $host = "127.0.0.1";
        $dbname = "cruduser";
        $dbusername = "root";
        $dbpass = "";
        return new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbusername, $dbpass);
    }
}
