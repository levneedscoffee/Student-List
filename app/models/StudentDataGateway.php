<?php
namespace StudentList\Models;
use StudentList\Entity\Student;




class StudentDataGateway
{
    private $pdo;

    public function __construct(DataInterface $obj)
    {
        $this->pdo = $obj->connection();
    }

    public function showAll()
    {
        $stmt = $this->pdo->query('select * from students');
        return $stmt->fetchAll();
    }

    public function returnLimitData($page, $limit, $sort)
    {
//        $sort = $this->pdo->quote($sort);

        $stmt = $this->pdo->query('SELECT * FROM students ORDER by '.$sort.' LIMIT '.$page.','.$limit);
        return $stmt->fetchAll();

    }

    public function returnSearchLimitData($page, $limit, $search, $sort)
    {

//        $search = $this->pdo->quote($search);
//        $sort = $this->pdo->quote($sort);

            $stmt = $this->pdo->query('select * from students
              where name like "%'.$search.'%"
              or  surname like "%'.$search.'%"
              or  groupNum like "%'.$search.'%"
              or  points like "%'.$search.'%"
 ORDER by '.$sort.' LIMIT '.$page.','.$limit);

            return $stmt->fetchAll();
    }

    public function countSearchPage($search)
    {
//        $search = $this->pdo->quote($search);

        $stmt = $this->pdo->query('select COUNT(*) from students
              where name like "%'.$search.'%"
              or  surname like "%'.$search.'%"
              or  groupNum like "%'.$search.'%"
              or  points like "%'.$search.'%"');

        return $stmt->fetch()[0];
    }

    public function countPage()//где это используется?
    {
        $stmt = $this->pdo->query('SELECT COUNT(*) FROM students');
        return $stmt->fetch()[0];
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

    public function returnValuesByEmail($email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM students WHERE email = :email');
        $stmt->execute(array('email' => $email));
        $val = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if (count($val) > 0){
            return $val[0];
        }else{
            return false;
        }
    }

    public function insertNew(Student $student)
    {

        $stmt = $this->pdo->prepare('insert into students(name, surname, groupNum, email, points, birthday, birthplace,gender) 
        VALUES (:name, :surname, :groupNum, :email, :points, :birthday, :birthplace, :gender)');

        $stmt->execute($student->returnStudentValues());
    }
    public function updateStudent(Student $student, $userEmail)
    {

        $stmt = $this->pdo->prepare(
            'UPDATE students SET name = :name, surname = :surname, groupNum = :groupNum, email = :email, points = :points,  birthday = :birthday, birthplace = :birthplace, gender = :gender
                        WHERE  email = :userEmail');

        $values = $student->returnStudentValues();
        $values["userEmail"] = $userEmail;
        $stmt->execute($values);
    }

}