<?php
require_once "../Models/Database.php";
require_once "../Models/User.php";

//prepare a paragraph messag to display to the user:
$message = "<p class='message'>";
$message_content = "";

$database = Database::getInstance();
// we check first if we are connected to the database before
// we initiate any activity
if ($database->connect()) {
    if (!empty($_POST)) {
        // valid input:
        if (validateUserInput()) {
            // the email does not exist in the database:
            if (!$database->isExistUserByEmail($_POST['userEmail'])) {
                $user = new User();

                $user->setEmail(trim($_POST['userEmail']));
                $user->setType(0);
                $user->setFirstName(trim($_POST['userFirstName']));
                $user->setLastName(trim($_POST['userLastName']));
                $user->setPassword(trim($_POST['userPassword']));

                if ($database->registerUserWithEncryptedPassword($user)) {
                    $message_content = "user has been registered successfully</p>";
                } else {
                    $message_content = "user failed to be registered</p>";
                }
            } else {
                $message_content = "this email is already associated with an account</p>";
            }
        }
    }
} else {
    $message_content = "Database is temporarily unavailable, please try again later</p>";
}

// display a message about the result of this activity:
echo $message . $message_content;

/**
 * Function validates the email, firstname, lastname, and passwords
 * that is recieved from the user
 * will display a message if something is invalid and return false,
 * otherwise will return true
 * @return boolean
 */
function validateUserInput(): bool
{
    global $message_content;
    $first_name = trim($_POST['userFirstName']);
    $last_name = trim($_POST['userLastName']);
    $password = trim($_POST['userPassword']);
    $confirm_password = trim($_POST['userConfirmPassword']);

    // check if the firstName or last name are only english letters
    if (!ctype_alpha($first_name) || str_contains($first_name, " ")) {
        $message_content = "first name should only contain a-z or A-Z letters</p>";
        return false;
    }

    if (!ctype_alpha($last_name)  || str_contains($last_name, " ")) {
        $message_content = "last name should only contain a-z or A-Z letters</p>";
        return false;
    }

    if (str_contains($password, " ")) {
        $message_content = "password should not contain any whitespaces between letters</p>";
        return false;
    }

    if ($password !== $confirm_password) {
        $message_content = "confirmation password is incorrect</p>";
        return false;
    }

    return true;
}
