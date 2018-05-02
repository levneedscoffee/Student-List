<?php

namespace StudentList\Models;



class User
{

    private $email;
    private $password;


    public function setNewUser($email)
    {
        $db = new UserDataGateway();
        $this->email = $email;

        if(!$db->checkUserInDb($this->email)){
            $this->password= $this->makePassword();
            $db->insertNewUser($this->email, $this->password);

        }else{
            $this->password = $db->returnPasswordFromDb($this->email);
        }

        $this->setUserCookie();
    }

    private function setUserCookie(){
        $time = mktime(0, 0, 0, 1, 1, 2028);
        setcookie("userPassword", $this->password, $time,'/');
        setcookie("userEmail", $this->email, $time, '/');

    }
    public function deleteCookie(){
        setcookie('userEmail','',time()-3600,'/');
        setcookie('userPassword','',time()-3600,'/');
    }

    private function makePassword()
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


