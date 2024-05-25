<?php

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('model/validate.php'); // Validation script
require_once('vendor/autoload.php'); // Composer autoload
require_once('controllers/controller.php'); // Assuming Controller class is defined here
require_once('classes/Applicant.php');
require_once('classes/Applicant_SubscribedToLists.php');

$f3 = Base::instance();
$con = new UserController($f3); // Assuming the Controller class uses F3 instance

// Define routes using methods from the Controller

// Home page route
$f3->route('GET /', [$con, 'home']); // Assuming there's a home method

// Route for collecting personal information
$f3->route('GET|POST /info', [$con, 'personalInfo']); // Assuming there's a personalInfo method

// Route for experience details
$f3->route('GET|POST /experience', [$con, 'experienceDetails']); // Assuming there's an experienceDetails method

$f3->route('GET|POST /jobLists', [$con, 'jobList']); // Assuming there's a jobList method

// Route for summary page
$f3->route('GET /summary', [$con, 'summary']); // Assuming there's a summary method

// Set experience options in the Fat-Free framework
$f3->set('experienceOptions', [
    ['id' => 'experience0-2', 'value' => '0-2', 'label' => '0-2 years'],
    ['id' => 'experience2-4', 'value' => '2-4', 'label' => '2-4 years'],
    ['id' => 'experience4plus', 'value' => '4+', 'label' => '4+ years']
]);

// Run the Fat-Free Framework
$f3->run();
