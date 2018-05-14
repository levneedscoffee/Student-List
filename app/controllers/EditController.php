<?php
namespace StudentList\Controllers;
use StudentList\Models\DatabaseMySql;
use StudentList\Services\Security;
use StudentList\Entity\Student;
use StudentList\Models\StudentDataGateway;
use StudentList\Validation\StudentValidation;
use StudentList\Entity\User;

class EditController extends Controller
{
    const CONTROLLER_VIEW = 'editView.html';


    public function actionIndex()
    {
        $security = new Security();
        $security->createUniqueTokenXSRF();
        $token = $_COOKIE['token'];

        $pdoStudent = new StudentDataGateway(new DatabaseMySql());

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
            $studentObject = $this->getSendValues();

            if (!$security->checkTokenXSRF()){
                $this->twigRender(self::CONTROLLER_VIEW, array("errorXSRF"=>true));
                exit;
            }


            $val = new StudentValidation();
            $val->validate($studentObject, $userEmail);
            $errors = $val->returnErrors();
            $values = $val->returnValues();

            if (count($errors) > 0) {
                 $this->twigRender(self::CONTROLLER_VIEW, array("errors" => $errors,
                    "userEmail" => $userEmail,
                    "db" => $values,
                     'token' => $token));
                exit;
            }else{
                if($userInStudentsList){
                    $pdoStudent->updateStudent($studentObject, $userEmail);
                }else{
                    $pdoStudent->insertNew($studentObject);
                }

                if($userEmail !== $values['email']){
                    $this->setOptionsIfUserChangeEmail($userEmail);
                    header("Location:/register?notify=userChangeEmail");
                }

                $this->twigRender(self::CONTROLLER_VIEW, array("userEmail" => $userEmail, "db" => $values, "changeSuccess" => true));
                exit;
            }
        }

        $this->twigRender(self::CONTROLLER_VIEW, array("userEmail" => $userEmail, "db" => $userValue,'token'=>$token));

    }
    private function setOptionsIfUserChangeEmail($userEmail)
    {
        $user = new User;
        $user->deleteUser($userEmail);
    }


    private function getSendValues()
    {
        $name = trim($_POST['name']);
        $surname = trim($_POST['surname']);
        $groupNum = isset($_POST["groupNum"]) ? $_POST['groupNum'] : false;
        $email = trim($_POST['email']);
        $points = trim($_POST['points']);
        $birthday = trim($_POST['birthday']);
        $birthplace = (isset($_POST["birthplace"])) ? $_POST['birthplace'] : false;
        $gender =(isset($_POST["gender"])) ? $_POST['gender'] : false;

        $sendValues =  array("name" => $name,
            "surname" => $surname,
            "groupNum" => $groupNum,
            "email" => $email,
            "points" => $points,
            "birthday" => $birthday,
            "birthplace" => $birthplace,
            "gender" => $gender);

        $student = new Student();
        return $student->setStudentValues($sendValues);
    }
}