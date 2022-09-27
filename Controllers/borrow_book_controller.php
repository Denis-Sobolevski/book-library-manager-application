<?php
require_once "../Models/Database.php";
require_once "../Models/Book.php";
require_once "../Models/User.php";
// the following file contains a function for building the list of forms for the books:
require_once "../Models/build_books_forms.php";

$database = Database::getInstance();
$books = $database->getAllAvailableBooksToBorrow();

// we get the name of the current user:
session_start();
$email_of_current_user = $_SESSION['user']->getEmail();
session_abort();

// build and display a set of forms representing all the available
// books to borrow in the library:
$forms = build_books_forms($books, "borrow", "../Views/borrow_book.php");
if(count($books) > 0) {
    echo "<p>all the available books to borrow:</p>";
}
echo $forms;

//prepare a paragraph message to display to the user:
$message = "<p class='message'>";
$message_content = "";

// the user has choosen to borrow a book:
if ($database->connect()) {
    if (isset($_POST['borrow'])) {
        // we get the name of the book and its author's name:
        // index 0 - have the book name
        // index 1 - have the author's name
        $book_to_borrow = explode('*', $_POST['borrow']);
        $bookName = $book_to_borrow[0];
        $author = $book_to_borrow[1];

        // try to borrow the book for the current user:
        if ($database->borrowBook($email_of_current_user, $bookName, $author)) {
            // alert the user that he was successful in borrowing the book:
            $message_content = "Book: $bookName was successfully borrowed</p>";
            // and refresh the page after 2 seconds so the form's list will refresh as well:
            header("Refresh:2");
        } else {
            $message_content = "Book: $bookName was failed to be borrowed</p>";
        }
    }
} else {
    $message_content = "Database is temporarily unavailable</p>";
}

echo $message . $message_content;
