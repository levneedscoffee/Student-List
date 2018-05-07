<?php
namespace StudentList\Services;
use StudentList\Entity\User;

class Cookie
{
    public function setUniqueToken(){;
        $helper = new User();
        $token = $helper->makePassword();

        setcookie("token", $token, time()+86400,'/','',false, true);
    }

    public function prolongUniqueToken($token){
        setcookie("token", $token, time()+86400,'/','',false, true);
    }

    public function setUserCookie($email,$password){
        $time = mktime(0, 0, 0, 1, 1, 2028);
        setcookie("userPassword", $password, $time,'/','',false, true);
        setcookie("userEmail", $email, $time, '/','',false, true);

    }

    public function deleteCookie(){
        setcookie('userEmail','',time()-3600,'/');
        setcookie('userPassword','',time()-3600,'/');
    }
}