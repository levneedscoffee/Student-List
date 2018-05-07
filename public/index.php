<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$string = file_get_contents('/var/www/project/config.json');
$path = json_decode($string, true)['path'];

define('PATH', $path);

require_once (PATH.'vendor/autoload.php');

require PATH.'app/controllers/FrontController.php';


$obj = new StudentList\Controllers\FrontController();
$obj->start();