<?php

// Validate first name: should be alphabetic
function validFirstName($firstName) {
    return ctype_alpha($firstName);
}

// Validate last name: should be alphabetic
function validLastName($lastName) {
    return ctype_alpha($lastName);
}


function validExperience($yearsExperience, $experienceOptions) {
    $validValues = array_column($experienceOptions, 'value');
    return in_array($yearsExperience, $validValues);
}

// Validate phone number: ensure it contains only numbers and specific symbols like dashes or spaces
function validPhone($phone) {
    // Example format: 123-456-7890 or (123) 456-7890, adjust regex as necessary
    return preg_match('/^\(?\d{3}\)?[- ]?\d{3}[- ]?\d{4}$/', $phone);
}

// Validate email address: should be a valid email
function validEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


