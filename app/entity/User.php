<?php

namespace StudentList\Entity;
use StudentList\Models\UserDataGateway;
use StudentList\Models\DatabaseMySql;
use StudentList\Services\Cookie;




class User
{

    private $email;
    private $password;


    public function setNewUser($email)
    {
        $userDataGateway = new UserDataGateway(new DatabaseMySql());
        $this->email = $email;

        if(!$userDataGateway->checkUserInDb($this->email)){
            $this->password= $this->makePassword();
            $userDataGateway->insertNewUser($this);

        }else{
            $this->password = $userDataGateway->returnPasswordFromDb($this->email);
        }

        $cookie = new Cookie();
        $cookie->setUserCookie($this->email, $this->password);
    }

    public function deleteUser($userEmail){
        $this->email = null;
        $this->password = null;

        $deleteUserFromDb = new UserDataGateway(new DatabaseMySql());
        $deleteUserFromDb->deleteUserFromDb($userEmail);

        $cookie = new Cookie();
        $cookie->deleteCookie();
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPassword(){
        return $this->password;
    }

    public function makePassword()//no here
    {
        $symb = 'abdefhiknrstyzABDEFGHKNQRSTYZ123456789';
        $length = strlen($symb) - 1;
        $col = 32;
        $str = '';
        for ($i = 0; $i < $col; $i++) {
            $str .= substr($symb, mt_rand(0, $length), 1);
        }
        return $str;
    }

}


