<?php
require_once "../Models/Database.php";
require_once "../Models/Book.php";

//prepare a paragraph message to display to the user:
$message = "<p class='message'>";
$message_content = "";

$database = Database::getInstance();
// we check first if we are connected to the internet before
// we initiate any activity
if ($database->connect()) {
    if (!empty($_POST)) {

        $bookName = trim($_POST['bookName']);
        $author = trim($_POST['author']);

        if (!$database->isExistBook($bookName, $author)) {
            // the book does not exist:
            if (validateBookInput()) {
                //the given input from the user is valid:
                $book = new Book();

                $book->setBookName($bookName);
                $book->setAuthor($author);
                $book->setYear(intval(trim($_POST['year'])));

                if ($database->addBook($book)) {
                    $message_content = "Book successfully added to the Library</p>";
                } else {
                    $message_content = "Book failed to be added to the Library</p>";
                }
            }
        } else {
            $message_content = "Book already exists inside the Library</p>";
        }
    }
} else {
    $message_content = "Database is temporarily unavailable</p>";
}

// display a message about the result of this activity:
echo $message . $message_content;

/**
 * this function validates the given input from the admin user
 * which is the name of the book and name of the author
 * and returns true if they are both valid, else will return false and echo and display
 * a paragraph to the user with the error
 * @return boolean true if author name and book name are valid, false otherwise
 */
function validateBookInput(): bool
{
    global $message_content;
    $words_in_bookName = explode(' ', trim($_POST['bookName']));
    $words_in_author   = explode(' ', trim($_POST['author']));

    // check if the book name or the author name has unnecessary spaces between each word:
    if (in_array('', $words_in_bookName)) {
        $message_content = "book name should only have one space character between each word</p>";
        return false;
    }

    if (in_array('', $words_in_author)) {
        $message_content = "author name should only have one space character between each word</p>";
        return false;
    }

    // check if all the letters in authors name are A-Z or a-z:
    foreach ($words_in_author as $value)
        if (!ctype_alpha($value)) {
            $message_content = "author name should only contain a-z or A-Z letters</p>";
            return false;
        }

    // check if the bookName has the special * character that we use to seperate the value 
    // we get when we want to remove a book:
    $bookName = join('', $words_in_bookName);
    if (str_contains($bookName, '*')) {
        $message_content = "book name should not contain special character *</p>";
        return false;
    }

    return true;
}
