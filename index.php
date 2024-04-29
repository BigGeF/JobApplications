<?php
session_start();

//add the following PHP code to turn on error reporting:
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('model/validate.php'); // Make sure the path is correct relative to the location of this file
require_once ('vendor/autoload.php');

$f3 = Base::instance();
//get home page
$f3->route('GET /', function (){
    $view = new Template();
    echo $view ->render('views/home.html');
});

//Route fo rcollecting personal infomation
$f3->route('GET|POST /info', function ($f3) {
    //check if the form has been posted
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        // Retrieve the form data from the POST request
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $state = $_POST['state'];
        $phone = $_POST['phone'];


        // Set the session variables using the Fat-Free Framework's set method
        $f3->set('SESSION.firstName', $firstName);
        $f3->set('SESSION.lastName', $lastName);
        $f3->set('SESSION.email', $email);
        $f3->set('SESSION.state', $state);
        $f3->set('SESSION.phone', $phone);
        $f3->reroute("/experience");
    }
    $view = new Template();
    echo $view->render('views/info.html');
});

$f3->route("GET|POST /experience", function ($f3){
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        $biography = $f3->get('POST.biography');
        $githubLink = $f3->get('POST.githubLink');
        $yearsExperience = $f3->get('POST.yearsExperience');
        $relocate = $f3->get('POST.relocate');

        // Store data in session using F3's set method
        $f3->set('SESSION.biography', $biography);
        $f3->set('SESSION.githubLink', $githubLink);
        $f3->set('SESSION.yearsExperience', $yearsExperience);
        $f3->set('SESSION.relocate', $relocate);
        $f3->reroute('jobLists');

    }
    $view = new Template();
    echo $view->render('views/experience.html');
});

$f3->route('GET|POST /jobLists', function ($f3) {


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve and store the selected jobs from POST into session
        $_SESSION['selectedJobs'] = $f3->get('POST.homeDecorJobs');

        // Redirect to summary page
        $f3->reroute('summary');
    } else {
        // If GET, show the form again or another appropriate action
        $view = new Template();
        echo $view->render('views/jobLists.html');
    }
});

$f3->route('GET /summary', function ($f3) {


    if (!isset($_SESSION['selectedJobs']) || empty($_SESSION['selectedJobs'])) {
        $f3->reroute('/jobLists');  // Redirect if no data is present
    }

    // Make session data available to the template
    $f3->set('SESSION.selectedJobs', $_SESSION['selectedJobs']);

    $view = new Template();
    echo $view->render('views/summary.html');
});



//run fat-free
$f3->run();