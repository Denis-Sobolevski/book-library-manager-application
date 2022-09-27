<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin dashboard - remove books from library</title>

  <link rel="stylesheet" href="../css/normalize.css" />
  <link rel="stylesheet" href="../css/index.css" />
  <script defer src="../js/message_handler.js"></script>

</head>

<body>

  <header>
    <nav class="header-nav">
      <ul>
        <li>
          <div>
            <a href="./login_form.php">logout</a>
          </div>
        </li>
      </ul>
      <ul>
        <li>
          <div>
            <a href="./add_book_form.php">Add a book</a>
          </div>
        </li>
      </ul>
    </nav>
  </header>

  <main>
    <?php
    require_once "../Controllers/index_admin_controller.php";
    ?>
  </main>

  <footer>
    <div>
      <p>© Denis 2022</p>
      <p>
        <a href="http://jigsaw.w3.org/css-validator/check/referer">
          <!-- This is not an inline-style CSS that i added, the W3C certification came that way... -->
          <img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss-blue" alt="Valid CSS!" />
          <!-- This is not an inline-style CSS that i added, the W3C certification came that way... -->
        </a>
      </p>
    </div>
  </footer>



</body>

</html>