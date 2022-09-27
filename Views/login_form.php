<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>

  <link rel="stylesheet" href="../css/normalize.css" />
  <link rel="stylesheet" href="../css/userForms.css" />
  <script defer src="../js/message_handler.js"></script>
</head>

<body>
  <main>
    <form method='post' action="<?= $_SERVER['PHP_SELF'] ?>">
      <h1>login:</h1>

      <div>
        <div>
          <label>email address:</label>
          <input value="<?php if (isset($_POST['userEmail']))
                          echo $_POST['userEmail']; ?>" name="userEmail" type="email" required minlength="6" maxlength="70" autofocus alt="enter your email address" placeholder="email address" />
        </div>

        <div>
          <label>password: </label>
          <input name="userPassword" type="password" required minlength="8" alt="enter your user password" placeholder="password" />
        </div>
      </div>
      <button type="submit">Login</button>
      <a href="./register_form.php">dont have an account ? register</a>

      <?php
      require_once "../Controllers/login_form_controller.php";
      ?>

    </form>
  </main>
</body>

</html>