<?php

namespace StudentList\Models;

class UserDataGateway
{
    private $pdo;

    public function __construct()
    {
        $db = new DbMySql();
        $this->pdo = $db->getDb();
    }
    public function checkUserInDb($email)
    {
        $stmt = $this->pdo->prepare('SELECT count(*) FROM students_list.users  where email =:email' );
        $stmt->execute(array("email" => $email));
        $count =intval($stmt->fetch()["count(*)"]);

        if($count > 0){
            return true;
        }else{
            return false;
        }
    }
    public function returnPasswordFromDb($email)
    {
        $stmt = $this->pdo->prepare('SELECT password FROM students_list.users  where email =:email' );
        $stmt->execute(array("email" => $email));
        return $stmt->fetch()["password"];
    }
    public function insertNewUser($email, $password)
    {
        $stmt = $this->pdo->prepare('insert into students_list.users(email, password) VALUES (:email,:password)');
        $stmt->execute(array("email" => $email, "password" => $password));
    }
    public function deleteUserFromDb($email)
    {
        $stmt = $this->pdo->prepare('delete from students_list.users where email = :email');
        $stmt->execute(array("email" => $email));
    }
}