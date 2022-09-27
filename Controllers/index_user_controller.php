<?php
require_once "../Models/Database.php";
require_once "../Models/Book.php";
require_once "../Models/User.php";
// the following file contains a function for building the list of forms for the books:
require_once "../Models/build_books_forms.php";

$database = Database::getInstance();

session_start();
$email_of_current_user = $_SESSION['user']->getEmail();
// we greet the user:
echo "<h1>Welcome back: {$_SESSION['user']->getLastName()}, {$_SESSION['user']->getFirstName()}</h1>";
// Using session_destory() here will make so
// if the user has refreshed the page, the new session will not have the key 'user'
// we use session_abort() instead
session_abort();

// we get all the books that the current user is borrowing right now:
$books = $database->getAllCurrentUserBorrowedBooks($email_of_current_user);
$forms = build_books_forms($books, "retrieve", "../Views/index_user.php");

if (count($books) > 0) {
    echo "<p>all the books you are currently borrowing:</p>";
}
// we display those books as a list of forms:
echo $forms;


// user has choosen to retrieve a borrowed book:
if ($database->connect()) {
    if (isset($_POST['retrieve'])) {
        // we get the name of the book and its author's name:
        // index 0 - have the book name
        // index 1 - have the author's name
        $book_to_retrieve = explode('*', $_POST['retrieve']);
        $bookName = $book_to_retrieve[0];
        $author = $book_to_retrieve[1];

        if ($database->retrieveBorrowedBook($bookName, $author)) {
            echo "<p class ='message'>Book: $bookName was successfully retrieved</p>";
            // and refresh the page after 2 seconds so the form's list will refresh as well:
            header("Refresh:2");
        } else {
            echo "<p class ='message'>Book: $bookName was failed to be retrieved</p>";
        }
    }
} else {
    echo "<p class ='message'>Database is temporarily unavailable</p>";
}
