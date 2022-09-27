<?php
require_once "../Models/Database.php";
require_once "../Models/Book.php";
require_once "../Models/User.php";
// the following file contains a function for building the list of forms for the books:
require_once "../Models/build_books_forms.php";

$database = Database::getInstance();
$books = $database->getAllBooks();

session_start();
// we greet the user:
echo "<h1>Welcome back Admin: {$_SESSION['user']->getLastName()}, {$_SESSION['user']->getFirstName()}</h1>";
// Using session_destory() here will make so
// if the user has refreshed the page, the new session will not have the key 'user'
// we use session_abort() instead
session_abort();

// build and display a set of forms representing all the books in the library:
$forms = build_books_forms($books, "remove", "../Views/index_admin.php");
if(count($books) > 0) {
    echo "<p>all the books in the library:</p>";
}
echo $forms;

// the user has choosen to remove a book:
if ($database->connect()) {
    if (isset($_POST['remove'])) {
        // we get the name of the book and its author's name:
        // index 0 - have the book name
        // index 1 - have the author's name
        $book_to_remove = explode('*', $_POST['remove']);
        $bookName = $book_to_remove[0];
        $author = $book_to_remove[1];

        // only if no body is borrowing the book right now, we can remove it from the book table:
        if (!$database->isBorrowed($bookName, $author)) {

            if ($database->removeBook($bookName, $author)) {
                // alert the user that the book was successfuly removed            
                echo "<p class='message'>Book: $bookName was successfully removed</p>";
                // and refresh the page after 2 seconds so the form's list will refresh as well:
                header("Refresh:2");
            } else {
                echo "<p class='message'>Book: $bookName failed to be removed</p>";
            }
        } else {
            echo "<p class='message'>Book: $bookName is currently borrowed</p>";
        }
    }
} else {
    echo "<p class ='message'>Database is temporarily unavailable</p>";
}
