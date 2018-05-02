<?php
namespace StudentList\Controllers;
use StudentList\Models\StudentDataGateway;
use StudentList\Models\StudentValidation;
use StudentList\Models\UserDataGateway;
use StudentList\Models\User;

class EditController extends Controller
{
    const CONTROLLER_VIEW = 'editView.html';


    public function actionIndex()
    {
        $pdoStudent = new StudentDataGateway();

        $userEmail = isset($_COOKIE["userEmail"]) ? $_COOKIE["userEmail"] : false;
        $userValue = '';


        if($userEmail){
            $userInStudentsList = $pdoStudent->checkEmail($userEmail);;
            if($userInStudentsList){
                $userValue = $pdoStudent->returnValuesByEmail($userEmail);
            }
        }else{
            header("Location: /register?notify=pleaseRegister");
        }


        if ($_SERVER["REQUEST_METHOD"] === "POST"){
            $sendValues = $this->getSendValues();
            $val = new StudentValidation();
            $val->validate($sendValues, $userEmail);
            $errors = $val->returnErrors();
            $values = $val->returnValues();

            if (count($errors) > 0) {
                 $this->twigRender(self::CONTROLLER_VIEW, array("errors" => $errors,
                    "userEmail" => $userEmail,
                    "db" => $values));
                exit;
            }else{
                if($userInStudentsList){
                    $pdoStudent->updateStudent($values, $userEmail);
                }else{
                    $pdoStudent->insertNew($values);
                }

                if($userEmail !== $values["email"]){
                    $this->setOptionsIfUserChangeEmail($userEmail);
                    header("Location:/register?notify=userChangeEmail");
                }

                $this->twigRender(self::CONTROLLER_VIEW, array("userEmail" => $userEmail, "db" => $values, "changeSuccess" => true));
                exit;
            }
        }

        $this->twigRender(self::CONTROLLER_VIEW, array("userEmail" => $userEmail, "db" => $userValue));

    }
    private function setOptionsIfUserChangeEmail($userEmail)
    {
        $deleteFromDb = new UserDataGateway();
        $deleteFromDb->deleteUserFromDb($userEmail);

        $deleteUserCookie = new User;
        $deleteUserCookie->deleteCookie();
    }

    private function getSendValues()
    {
        $name = trim($_POST['name']);
        $surname = trim($_POST['surname']);
        $groupNum = trim( $_POST['groupNum']);
        $email = trim($_POST['email']);
        $points = trim($_POST['points']);
        $birthday = trim($_POST['birthday']);
        $birthplace = trim($_POST['birthplace']);
        $gender =(isset($_POST["gender"])) ? $_POST['gender'] : false;

        return array("name" => $name,
            "surname" => $surname,
            "groupNum" => $groupNum,
            "email" => $email,
            "points" => $points,
            "birthday" => $birthday,
            "birthplace" => $birthplace,
            "gender" => $gender);
    }
}