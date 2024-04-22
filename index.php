<?php

// 328/hell_FatFree/index.php
// this is my controller!

//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require
require_once ('vendor/autoload.php');

//Instantiate the F3 base class
$f3 = Base::instance();


//Define a default route
//https://hfan.greenriverdev.com/328/JobApplications/
$f3->route('GET /', function (){
//    echo '<h1>Hello Fat_free!</h1>';
    //render a view page
    $view =new Template();

    echo $view->render('views/home.html');
});

//run fat-free
$f3->run();