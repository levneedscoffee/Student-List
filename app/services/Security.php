<?php
namespace StudentList\Services;


class Security
{
    public function createUniqueTokenXSRF(){
        $cookie = new Cookie();

        if(isset($_COOKIE['token'])){
            $cookie->prolongUniqueToken($_COOKIE['token']);
        }else{
            $cookie->setUniqueToken();
        }

    }
    public function checkTokenXSRF(){
        if(isset($_COOKIE['token']) && isset($_POST['token']) && $_COOKIE['token'] === $_POST['token']){
            return true;
        }else{
            return false;
        }
    }
}