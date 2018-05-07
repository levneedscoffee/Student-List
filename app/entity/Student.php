<?php

namespace StudentList\Entity;


class Student
{
    private $name;
    private $surname;
    private $groupNum;
    private $email;
    private $points;
    private $birthday;
    private $birthplace;
    private $gender;

    public function setStudentValues(array $values)
    {
        $this->name = $values['name'];
        $this->surname =$values['surname'];
        $this->groupNum = $values['groupNum'];
        $this->email = $values['email'];
        $this->points = $values['points'];
        $this->birthday = $values['birthday'];
        $this->birthplace = $values['birthplace'];
        $this->gender = $values['gender'];

        return $this;
    }
    public function returnStudentValues(){
        $values = array();

        foreach ($this as $key=>$value){
            $values[$key] = $value;
        }

        return $values;
    }

    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getSurname()
    {
        return $this->surname;
    }
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    public function getFullName()
    {
        return "{$this->name} {$this->surname}";
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function getGroupNum()
    {
        return $this->groupNum;
    }

    public function setGroupNum($groupNum)
    {
        $this->groupNum = $groupNum;

    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getBirthday()
    {
        return $this->birthday;
    }
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }


    public function getPoints()
    {
        return $this->points;
    }

    public function setPoints($points)
    {
        $this->points = $points;
    }

    public function getBirthplace(){
        return $this->birthplace;
    }
    public function setBirtplace($birthplace){
        $this->birthplace = $birthplace;
    }

}