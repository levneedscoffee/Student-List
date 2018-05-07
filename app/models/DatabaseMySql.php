<?php
namespace StudentList\Models;

class DatabaseMySql implements DataInterface
{
    private $pdo;

    public function connection()
    {
        $string = file_get_contents('/var/www/project/config.json');
        $option = json_decode($string, true)['db'];

        $host = $option['servername'];
        $dbname = $option['dbname'];
        $username = $option["username"];
        $password = $option["password"];

        try {
            $this->pdo = new \PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username , $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        return $this->pdo;
    }
}
