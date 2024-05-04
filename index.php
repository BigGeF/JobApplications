<?php
session_start();

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('model/validate.php'); // Correct path to the validation script
require_once('vendor/autoload.php');

$f3 = Base::instance();

// Home page route
$f3->route('GET /', function () {
    $view = new Template();
    echo $view->render('views/home.html');
});

// Route for collecting personal information
$f3->route('GET|POST /info', function ($f3) {
    $error = []; // Array to hold validation errors

    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        // Retrieve the form data from the POST request
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $state = $_POST['state'];
        $phone = $_POST['phone'];

        // Validate data
        if (!validFirstName($firstName)) {
            $error['firstName'] = "Invalid first name.";
        }
        if (!validLastName($lastName)) {
            $error['lastName'] = "Invalid last name.";
        }
        if (!validEmail($email)) {
            $error['email'] = "Invalid email.";
        }
        if (!validPhone($phone)) {
            $error['phone'] = "Invalid phone number.";
        }

        // Check for errors before setting session variables and rerouting
        if (empty($error)) {
            $f3->set('SESSION.firstName', $firstName);
            $f3->set('SESSION.lastName', $lastName);
            $f3->set('SESSION.email', $email);
            $f3->set('SESSION.state', $state);
            $f3->set('SESSION.phone', $phone);
            $f3->reroute('/experience');
        } else {
            $f3->set('errors', $error);
        }
    }
    $view = new Template();
    echo $view->render('views/info.html');
});

$f3->set('experienceOptions', [
    ['id' => 'experience0-2', 'value' => '0-2', 'label' => '0-2 years'],
    ['id' => 'experience2-4', 'value' => '2-4', 'label' => '2-4 years'],
    ['id' => 'experience4plus', 'value' => '4+', 'label' => '4+ years']
]);

// Route for experience details
$f3->route("GET|POST /experience", function ($f3){
    // Display current session information, usually for debugging, should be removed in production
    var_dump($_SESSION);

    $errors = []; // Use $errors as the error array

    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        $biography = $_POST['biography'];
        $githubLink = $_POST['githubLink'];
        $yearsExperience = $_POST['yearsExperience'];
        $relocate = $_POST['relocate'];

        // Validate years of experience
        if (!validExperience($yearsExperience, $f3->get('experienceOptions'))) {
            $errors['yearsExperience'] = "Please select a valid option for years of experience.";
        }

        // If there are no errors, set session variables and redirect
        if (empty($errors)) {
            $f3->set('SESSION.biography', $biography);
            $f3->set('SESSION.githubLink', $githubLink);
            $f3->set('SESSION.yearsExperience', $yearsExperience);
            $f3->set('SESSION.relocate', $relocate);
            $f3->reroute('/jobLists'); // Ensure the redirection path is correct
        } else {
            // Pass errors to the view
            $f3->set('errors', $errors);
            // Example: if (!validRelocate($relocate)) {$errors['relocate'] = "Invalid relocation option.";}
        }
    }

    // Render the experience view
    $view = new Template();
    echo $view->render('views/experience.html');
});

// Route for job list selection
$f3->route('GET|POST /jobLists', function ($f3) {
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        $_SESSION['selectedJobs'] = $_POST['homeDecorJobs'];
        $f3->reroute('/summary');
    } else {
        $view = new Template();
        echo $view->render('views/jobLists.html');
    }
});

// Route for summary page
$f3->route('GET /summary', function ($f3) {
    if (empty($_SESSION['selectedJobs'])) {
        $f3->reroute('/jobLists');
    }

    $f3->set('SESSION.selectedJobs', $_SESSION['selectedJobs']);
    $view = new Template();
    echo $view->render('views/summary.html');
});

// Run the Fat-Free Framework
$f3->run();
