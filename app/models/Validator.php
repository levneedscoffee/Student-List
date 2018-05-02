<?php
namespace StudentList\Models;

class Validator
{
    public function validateName($name,  $strLength)
    {
        if (!preg_match("/^[А-ЯЁA-Z][-а-яёa-zА-ЯЁA-Z\\s]{1,".$strLength."}$/u", $name)) {
            return  "Содержимое должно начинаться с заглавной буквы не содержать цифр и не быть длиннее ".$strLength." символов";
        }
        return true;
    }

    public function validateEmail($email)
    {
        if (!preg_match('#^([\w]+\.?)+(?<!\.)@(?!\.)[a-zа-я0-9ё\.-]+\.?[a-zа-яё]{2,80}$#ui', $email)) {
            return "Email должен быть в формате name@example.com и не длиннее 80 символов.";
        }
        return true;
    }
    public function validateNumber($points)
    {
        if(!preg_match("/^\d+$/u", $points)){
            return "Здесь должно быть число";
        }
        return true;
    }

    public function validateDate($birthday)
    {
        if (!preg_match('/\d{4}\-\d{2}\-\d{2}/', $birthday)) {
            return 'Упс, дата должна быть в формате YYYY-MM-DD';
        }
        return true;
    }


}