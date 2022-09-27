<?php

/**
 * function gets an array of book objects, an string representing the button activity
 * and a action for each form that will be created
 * function builds and returns a string representing an list of forms representing the book objects
 * @param array $books array of book objects
 * @param string $button the name of the activity the button will represent
 * @param string $action the path that each form inside the list should have as an action
 * @return string a string representing a list of forms representing the book objects
 */
function build_books_forms(array $books, string $button, string $action): string
{
    // if ther are no books:
    if (count($books) === 0) {
        return "<p>There is currently no books to display</p>";
    }

    $forms = "<div class='books'>";

    foreach ($books as $book) {
        $bookName = $book->getBookName();
        $author = $book->getAuthor();
        $year = $book->getYear();

        $forms .= "<form method='POST' action=" . $action . " class='" . "$bookName" . "'>
        <p>Book name: $bookName</p>
        <p>Author's name: $author</p>
        <p>Year of publish: $year</p>";

        $forms .= "<Button type='submit' name='" . "$button" . "' value='" . $bookName . "*" . $author . "'>$button</Button>";
        $forms .= "
    </form>";
    }
    $forms .= "</div>";

    return $forms;
}
