<?php
namespace StudentList\Validation;
use StudentList\Entity\Student;
use StudentList\Models\StudentDataGateway;
use StudentList\Models\DatabaseMySql;
use StudentList\Models\UserDataGateway;


class StudentValidation extends Validator
{


    const MIN_BIRTHDAY = 1985-01-01;
    const MAX_BIRTHDAY = 2005-01-01;

    const MAX_points = 300;
    const MIN_points = 220;


    private $values;
    private $errors;

    public function validate(Student $student, $userEmail)
    {
        $this->values = $student->returnStudentValues();

        $this->errors = [];
        $this->errors["name"] = $this->validateName($student->getName(),20);
        $this->errors["surname"] = $this->validateName($student->getSurname(),40);
        $this->errors["groupNum"] = $this->validateGroupNum(($student->getGroupNum()));
        $this->errors["email"] = $this->confirmEmail($student->getEmail(), $userEmail);
        $this->errors["points"] = $this->validatePoints($student->getPoints());
        $this->errors["birthday"] = $this->validateBirthday($student->getBirthday());
        $this->errors["birthplace"] = $this->validateBirthlace($student->getBirthplace());
        $this->errors["gender"] = $this->validateGender($student->getGender());
    }



    public function returnErrors()
    {
        foreach ($this->errors as $key => $error) {
            if ($error === true) {
                unset($this->errors[$key]);
            }
        }
        return $this->errors;
    }

    public function returnValues()
    {
        return $this->values;
    }

    public function confirmEmail($email, $userEmail){
        $checkStudentsTable =  new StudentDataGateway(new DatabaseMySql());
        $answerStudents =  $checkStudentsTable->checkEmail($email);

        $checkUserTable = new UserDataGateway(new DatabaseMySql());
        $answerUser = $checkUserTable->checkUserInDb($email);

        $validateEmail = $this->validateEmail($email);

        if($validateEmail!== true){
            return $validateEmail;
        }elseif(($answerStudents || $answerUser) && $email !== $userEmail){
            return "Извините, но такой email адрес занят";
        }else{
            return true;
        }
    }

    public function validatepoints($points)
    {
        $answer = $this->validateNumber($points);
        if($answer !== true) {
            return $answer;
        }elseif($points < self::MIN_points || $points > self::MAX_points) {
            return "Быллы ЕГЭ должно быть не меньше ".self::MIN_points." и не больше ".self::MAX_points." баллов";
        }
        return true;
    }
    public function validateBirthday($birthday)
    {
        $answer = $this->validateDate($birthday);
        if($answer !== true){
            return $answer;
        }elseif (strtotime($birthday) < strtotime(self::MIN_BIRTHDAY) || strtotime($birthday) > strtotime(self::MAX_BIRTHDAY)){
            return 'Вы не могли родиться раньше '.self::MIN_BIRTHDAY.' и позже '.self::MAX_BIRTHDAY;
        }
        return true;
    }
    public function validateBirthlace($birthplace)
    {
        if (!$birthplace) {
            return "Вы забыли выбрать откуда вы";
        }
        return true;
    }

    public function validateGroupNum($groupNum)
    {
        if(!$groupNum){
            return 'Вы не указали группу';
        }
        return true;

    }
    public function validateGender($gender)
    {
        if (!$gender) {
            return "Вы не указали пол";
        }
        return true;
    }
}




