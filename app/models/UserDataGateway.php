<?php

namespace StudentList\Models;

class UserDataGateway
{
    private $pdo;

    public function __construct(DataInterface $obj)
    {
        $this->pdo = $obj->connection();
    }
    public function checkUserInDb($email)
    {
        $stmt = $this->pdo->prepare('SELECT count(*) FROM users  where email =:email' );
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
        $stmt = $this->pdo->prepare('SELECT password FROM users  where email =:email' );
        $stmt->execute(array("email" => $email));
        return $stmt->fetch()["password"];
    }
    public function insertNewUser(User $user)
    {
        $stmt = $this->pdo->prepare('insert into users(email, password) VALUES (:email,:password)');
        $stmt->execute(array("email" => $user->getEmail(), "password" => $user->getPassword()));
    }
    public function deleteUserFromDb($email)
    {
        $stmt = $this->pdo->prepare('delete from users where email = :email');
        $stmt->execute(array("email" => $email));
    }
}