<?php 

session_start();
//http://qlsvmvc.com/?c=subject&a=create
//router
$c = $_GET['c'] ?? 'home';
$a = $_GET['a'] ?? 'index';

// import config & connectDB
require '../config.php';
require '../connectDB.php';


//Load Composer's autoloader
require '../vendor/autoload.php';

// import model
require '../bootstrap.php';

$str = ucfirst($c). 'controller'; //StudentController
require "controller/$str.php";

$controller = new $str();
$controller->$a ();
?>