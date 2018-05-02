<?php
namespace StudentList\Models;

class DbMySql
{
    protected $pdo;

    public function __construct()
    {
        $option = parse_ini_file('/var/www/project/config.ini');

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
    }
    public function getDb()
    {
        return $this->pdo;
    }
}
