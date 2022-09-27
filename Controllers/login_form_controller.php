<?php
require_once "../Models/Database.php";
require_once "../Models/User.php";

// when the user logout he is redirected here, we should destroy his session information
// because it is not relevant anymore:
session_start();
session_destroy();

//prepare a paragraph message to display to the user:
$message = "<p class='message'>";
$message_content = "";

$database = Database::getInstance();
// we check first if we are connected to the database before
// we initiate any activity
if ($database->connect()) {
    if (!empty($_POST)) {

        $password = trim($_POST['userPassword']);
        $email = trim($_POST['userEmail']);

        if ($database->isExistUserByEmail($email)) {
            // the email given is associated to a user, now try to login the user:
            if ($database->login_user($email, $password)) {
                $user = $database->getUserByEmail($email);
                if ($user !== null) {
                    // we start a session
                    // and we save the currently logged in user:
                    session_start();
                    $_SESSION['user'] = $user;

                    if ($user->getType() == 1) {
                        // we redirect the admin to the index_admin page:
                        header("Location:../Views/index_admin.php");
                        exit();
                    } else {
                        // we redirect the user to the index_user page:
                        header("Location:../Views/index_user.php");
                        exit();
                    }
                }
            } else {
                $message_content = "login attempt failed, please try again</p>";
            }
        } else {
            $message_content = "this email is not associated to any user</p>";
        }
    }
} else {
    $message_content = "Database is temporarily unavailable</p>";
}

// display a message about the result of the login:
echo $message . $message_content;
