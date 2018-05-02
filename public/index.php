<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '/var/www/project/vendor/autoload.php';

require '/var/www/project/app/controllers/FrontController.php';

$obj = new StudentList\Controllers\FrontController();
$obj->start();