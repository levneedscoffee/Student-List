<?php
namespace StudentList\Controllers;
use StudentList\Models\DatabaseMySql;
use StudentList\Services\Security;
use StudentList\Models\StudentDataGateway;
use StudentList\Entity\User;
use StudentList\Validation\Validator;


class RegisterController extends Controller//такое себе
{
    const CONTROLLER_VIEW = "registerView.html";

    public function actionIndex()
    {
        $security = new Security();
        $token = $security->createUniqueTokenXSRF();

        $userName = $this->checkUserAuth();

        if(isset($_GET["notify"]) && $_GET["notify"] === "pleaseRegister" ){
            $this->twigRender(self::CONTROLLER_VIEW, array("pleaseRegister"=>true));
            exit;
        }
        if(isset($_GET["notify"]) && $_GET["notify"] === "userChangeEmail"){
            $this->twigRender(self::CONTROLLER_VIEW, array("userChangeEmail"=>true));
            exit;
        }

        if($_SERVER["REQUEST_METHOD"] === "POST") {

            if (!$security->checkTokenXSRF()){
                echo json_encode(array("error" => true, "text" => "Что-то пошло не так"));
                exit;
            }

            $email = $this->authorizationUser();

             if(!$email){
                 echo json_encode(array("error" => true, "text" => "Email должен быть в формате name@example.com и не длиннее 80 символов."));
                 exit;
             }else{
                 echo json_encode(array("error" => false));
                 exit;
             }

        }


        $this->twigRender(self::CONTROLLER_VIEW, array("userName"=>$userName,'token'=>$token));
    }

    private function checkUserAuth($email = null){
        if($email != null || isset($_COOKIE["userEmail"]) ){
            $userEmail = ($email != null) ? $email : $_COOKIE["userEmail"];

            $student = new StudentDataGateway(new DatabaseMySql());
            $valuesOfUser = $student->returnValuesByEmail($userEmail);

            if($valuesOfUser){
                $fullName = $valuesOfUser["name"]." ".$valuesOfUser["surname"];
                return $fullName;
            }else{
                return $userEmail;
            }
        }else{
            return false;
        }
    }

    private function authorizationUser()
    {
        $email = trim($_POST["emailAuthorization"]);
        $validator = new Validator();

        if($validator->validateEmail($email) === true){
            $auth = new User();
            $auth->setNewUser($email);
            return $email;
        }else{
            return false;
        }
    }
}