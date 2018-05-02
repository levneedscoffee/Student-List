<?php
namespace StudentList\Models;




class StudentDataGateway
{
    private $pdo;

    public function __construct()
    {
        $db = new DbMySql();
        $this->pdo = $db->getDb();
    }

    public function showAll()
    {
        $res = $this->pdo->query('select * from students');
        return $res->fetchAll();
    }
    public function returnLimitData($page, $limit, $sort)
    {
        $res = $this->pdo->query('SELECT * FROM students ORDER by '.$sort.' LIMIT '.$page.','.$limit);
        return $res->fetchAll();

    }

    public function returnSearchData($page, $limit, $search, $sort)
    {
            $res = $this->pdo->query('select * from students
              where name like "%'.$search.'%"
              or  surname like "%'.$search.'%"
              or  groupNum like "%'.$search.'%"
              or  points like "%'.$search.'%"
 ORDER by '.$sort.' LIMIT '.$page.','.$limit);

            return $res->fetchAll();
    }
    public function countSearchPage($search){
        $res = $this->pdo->query('select COUNT(*) from students
              where name like "%'.$search.'%"
              or  surname like "%'.$search.'%"
              or  groupNum like "%'.$search.'%"
              or  points like "%'.$search.'%"');

        return $res->fetch()[0];
    }
    public function countPage()//где это используется?
    {
        $res = $this->pdo->query('SELECT COUNT(*) FROM students');
        return $res->fetch()[0];
    }
    public function insertNew($values)
    {

        $stmt = $this->pdo->prepare('insert into students(name, surname, groupNum, email, points, birthday, birthplace,gender) 
        VALUES (:name, :surname, :groupNum, :email, :points, :birthday, :birthplace, :gender)');
        $stmt->execute($values);
    }

    public function checkEmail($email)
    {
        $stmt = $this->pdo->prepare('SELECT count(*) FROM students  where email =:email' );
        $stmt->execute(array("email" => $email));
        $count =intval($stmt->fetch()["count(*)"]);
        if($count > 0){
            return true;
        }else{;
            return false;
        }
    }

    public function updateStudent($values, $userEmail)
    {
        $values["userEmail"] = $userEmail;
        $res = $this->pdo->prepare(
            'UPDATE students SET name = :name, surname = :surname, groupNum = :groupNum, email = :email, points = :points,  birthday = :birthday, birthplace = :birthplace, gender = :gender
                        WHERE  email = :userEmail');
        $values["userEmail"] = $userEmail;
        $res->execute($values);

    }


    public function returnValuesByEmail($email)
    {
        $res = $this->pdo->prepare('SELECT * FROM students WHERE email = :email');
        $res->execute(array('email' => $email));
        $val = $res->fetchAll(\PDO::FETCH_ASSOC);
        if (count($val) > 0){
            return $val[0];
        }else{
            return false;
        }

    }

}