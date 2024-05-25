<?php

class UserController {

    // Home view
    public function home($f3) {
        $view = new Template();
        echo $view->render('views/home.html');
    }

    // Personal info page
    public function personalInfo($f3) {
        $errors = []; // Array for storing validation errors

        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            // Get and validate form data
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $state = $_POST['state'];
            $phone = $_POST['phone'];
            $mailingListOptIn = isset($_POST['mailingListOptIn']) ? 'checked' : 'unchecked';

            // Validation code
            if (!validFirstName($firstName)) {
                $errors['firstName'] = "Invalid first name.";
            }
            if (!validLastName($lastName)) {
                $errors['lastName'] = "Invalid last name.";
            }
            if (!validEmail($email)) {
                $errors['email'] = "Invalid email.";
            }
            if (!validPhone($phone)) {
                $errors['phone'] = "Invalid phone number.";
            }

            // If no errors
            if (empty($errors)) {
                // Instantiate the appropriate class based on the checkbox value
                if ($mailingListOptIn === 'checked') {
                    $applicant = new Applicant_SubscribedToLists($firstName, $lastName, $email, $state, $phone);
                } else {
                    $applicant = new Applicant($firstName, $lastName, $email, $state, $phone);
                }

                // Store the object in the session
                $f3->set('SESSION.applicant', $applicant);
                $f3->reroute('/experience');
            } else {
                $f3->set('errors', $errors);
            }
        }

        $view = new Template();
        echo $view->render('views/info.html');
    }

    // Experience details page
    public function experienceDetails($f3) {
        $errors = [];
        $applicant = $f3->get('SESSION.applicant'); // Get the object from the session
        var_dump($applicant); // Debug output to confirm the object

        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            // Get and validate form data
            $biography = $_POST['biography'];
            $githubLink = $_POST['githubLink'];
            $yearsExperience = $_POST['yearsExperience'];
            $relocate = $_POST['relocate'];

            // Validation code
            if (!validExperience($yearsExperience, $f3->get('experienceOptions'))) {
                $errors['yearsExperience'] = "Please select a valid experience option.";
            }

            if (empty($errors)) {
                // Use setter methods to update the object's properties
                $applicant->setGithub($githubLink);
                $applicant->setExperience($yearsExperience);
                $applicant->setRelocate($relocate);
                $applicant->setBio($biography);

                // Update the object in the session
                $f3->set('SESSION.applicant', $applicant);

                // Determine the redirection path based on the object type
                if ($applicant instanceof Applicant_SubscribedToLists) {
                    var_dump('Rerouting to jobLists'); // Debug output
                    $f3->reroute('/jobLists');
                } else {
                    var_dump('Rerouting to summary'); // Debug output
                    $f3->reroute('/summary');
                }
            } else {
                $f3->set('errors', $errors);
            }
        }

        $view = new Template();
        echo $view->render('views/experience.html');
    }

    // Job list page
    public function jobList($f3) {
        $applicant = $f3->get('SESSION.applicant'); // Get the object from the session
        var_dump($applicant); // Debug output to confirm the object

        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            // Use setter methods to update the object's properties
            $applicant->setSelectionsJobs($_POST['homeDecorJobs']);
            $applicant->setSelectionsVerticals($_POST['verticals']);

            // Update the object in the session
            $f3->set('SESSION.applicant', $applicant);

            $f3->reroute('/summary');
        } else {
            // Check `mailingListOptIn` status
            if ($applicant->getSelectionsJobs() === 'unchecked') {
                var_dump('Rerouting to summary directly from jobList'); // Debug output
                $f3->reroute('/summary'); // Skip job list selection
            } else {
                $view = new Template();
                echo $view->render('views/jobLists.html');
            }
        }
    }

    // Summary page
    public function summary($f3) {
        $applicant = $f3->get('SESSION.applicant'); // Get the object from the session
        var_dump($applicant); // Debug output to confirm the object

        $f3->set('applicant', $applicant); // Pass the object to the view

        $view = new Template();
        echo $view->render('views/summary.html');
        session_destroy();
    }
}
