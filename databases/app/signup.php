<?php 
  require_once 'init.php';

  if (isset($_POST['create_account'])) {
    // confirm user doesn't exist 
    $userExist = $db->prepare("SELECT * FROM users WHERE email = :email OR username = :username LIMIT 1");
    $userExist->execute([
      ":email" => $_POST['email'],
      ":username" => $_POST['username'],
    ]);
    if (!$userExist) {
      // user doesn't exist, create new one
      $newUser  = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
      $newUser->execute([
        'username' => $_POST['username'],
        ':email' => $_POST['email'],
        ':password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
      ]);
      if ($newUser) {
        // user created, redirect to login page
        header('Location: login.php');
      } else {
        header('Location: signup.php?created=false');
        return;
      }
    } else {
      header('Location: signup.php?userExist=true');
      return;
    }
  }
?>

<!Doctype html>
<html>
  <head>
    <title>PHP/MySQL</title>
    <style>
      form, input {
        display: block;
      }
    </style>
  </head>
  <body>
    <h1>Signup New Account</h1>
    <?php if(isset($_GET['created']) && $_GET['created'] == 'false'):?>
      <h3 style="color: red"><i>An error occurred, while creating your account</i></h3>
    <?php elseif(isset($_GET['userExist']) && $_GET['created'] == 'true'):?>
      <h3 style="color: red"><i>An error occurred, while creating your account</i></h3>
    <?php endif;?>
    <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">
      <label>Fullname<label>
      <input type="text" name="fullname">
      <label>Username</label>
      <input type="text" name="username">
      <label>Password</label>
      <input type="Password" name="password">
      <br>
      <input tye="submit" name="create_account" value="Create Account">
    </form>
  </body>
</html>
