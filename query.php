<?php
require '/../adminPanel/core/init.php';

// Admin already logged in
if($session->adminAlreadyLoggedIn() === true){
    echo header('location: dashboard.php');
}

if (empty($_POST) === false) {
    // Set variables to grab form fields from form
    echo $username = trim($_POST['username']);// Get username
    echo $password = trim($_POST['password']);// Get password
    die();
    // Validate form inputs
    if (empty($username) === true || empty($password) === true) {
        // Check if form fields are not empty and continue with form
        // Processing
        echo $errors[] = 'Please provide username and password.';
    } else if ($user->userExists($username) === false) {
        // Check for user exist and return an error message,
        // Username and password combination is invalid
        echo $errors[] = 'Invalid username/password combination';
    } else if ($user->emailConfirmed($username) === false) {
        // Check if account has been verified for spam registrations
        echo $errors[] = 'Please check your email to verify your account.';
    }else if($user->accountSuspended($username) === false){
        // Return a suspended account message to the user
        // If query amount to true and continue to perform a
        // A final login process
        $errors[] = 'Your account has been suspended. Please contact
                    ' . '<a class="loginErrors" href="../contact_us.php"
                    title="Contact us">customer support</a>';
    } else {
        // User successfully gone through all validation process and
        // Can be granted access.
        $login = $user->login($username, $password);
        if($login && $user->isAdmin($username)){
            // Admin logged in Set session to true
            $_SESSION['id'] =  $login;
            header('Location: account_summary.php');
            exit();
        }else{
            echo $errors[] = 'Invalid username/password combination';
        }
    }
}
?>