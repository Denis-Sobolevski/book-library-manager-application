<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>

  <link rel="stylesheet" href="../css/normalize.css" />
  <link rel="stylesheet" href="../css/userForms.css" />
  <script defer src="../js/message_handler.js"></script>
</head>

<body>
  <main>
    <form method='post' action="<?= $_SERVER['PHP_SELF'] ?>">
      <h1>Register:</h1>

      <div>
        <div>
          <label>email address:</label>
          <input value="<?php if (isset($_POST['userEmail']))
                          echo $_POST['userEmail']; ?>" name="userEmail" type="email" required minlength="6" maxlength="70" autofocus alt="enter your email address" placeholder="email address" />
        </div>

        <div>
          <label>first name:</label>
          <input value="<?php if (isset($_POST['userFirstName']))
                          echo $_POST['userFirstName']; ?>" name="userFirstName" type="text" required minlength="2" maxlength="40" alt="enter your first name" placeholder="first name" />
        </div>

        <div>
          <label>last name:</label>
          <input value="<?php if (isset($_POST['userLastName']))
                          echo $_POST['userLastName']; ?>" name="userLastName" type="text" required minlength="2" maxlength="40" alt="enter your last name" placeholder="last name" />
        </div>

        <div>
          <label>password: </label>
          <input value="<?php if (isset($_POST['userPassword']))
                          echo $_POST['userPassword']; ?>" name="userPassword" type="password" required minlength="8" alt="enter your user password" placeholder="password" />
        </div>

        <div>
          <label>confirm password: </label>
          <input name="userConfirmPassword" type="password" required minlength="8" alt="confirm your user password" placeholder="confirm password" />
        </div>

      </div>
      <button type="submit">Register</button>
      
      <a href="./login_form.php">already have an account ? login</a>

      <?php
      require_once "../Controllers/register_form_controller.php";
      ?>

    </form>



  </main>
</body>

</html>