<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add a book</title>

    <link rel="stylesheet" href="../css/normalize.css" />
    <link rel="stylesheet" href="../css/userForms.css" />
    <script defer src="../js/message_handler.js"></script>
</head>

<body>
    <main>
        <form method='post' action="<?= $_SERVER['PHP_SELF'] ?>">
            <h1>Add a book to the library:</h1>

            <div>
                <div>
                    <label>book name:</label>
                    <input name="bookName" type="text" required minlength="2" maxlength="50" autofocus placeholder="name of book" />
                </div>

                <div>
                    <label>author:</label>
                    <input name="author" type="text" required minlength="2" maxlength="50" placeholder="name of author" />
                </div>

                <div>
                    <label>year:</label>
                    <input name="year" type="number" max=<?= date("Y") ?> required length="4" placeholder="release year of book" />
                </div>
                <!--max is the current year--->

            </div>
            <button type="submit">add book</button>
            
            <a href="./index_admin.php">Dashboard</a>

            <?php
            require_once "../Controllers/add_book_form_controller.php";
            ?>

            
        </form>



    </main>
</body>

</html>