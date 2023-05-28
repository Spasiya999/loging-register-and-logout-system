<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$username = $password = '';
$username_err = $password_err = '';

if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
  header('location: index.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {

  require_once 'db-config.php';
  if(empty(trim($_POST['username']))){
    $username_err = 'Please enter your username';
  }else{
    $username = trim($_POST['username']);
  }

  if(empty(trim($_POST['password']))){
    $password_err = 'Please enter your password';
  }else{
    $password = trim($_POST['password']);
  }

  //check if there are no errors
  if(empty($username_err) && empty($password_err)){
    $sql1 = "SELECT * FROM users WHERE username='".$username . "'";

    $result = $conn->query($sql1);

    if($result->num_rows > 0){
      $user = $result->fetch_assoc();

      //password match
      if($user['password'] == sha1($password)){
        $_SESSION['logged_in'] = true;
        $_SESSION['users'] = array(
          'first_name' => $user['first_name'],
          'last_name' => $user['last_name'],
          'id' => $user['id']
        );

        header('location: index.php');
        exit;

      }else{
        //password mismatch
        $password_err = 'Passwords do not match';
      }

    }else{
      $username_err = 'Invalid Username';
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <title>Login</title>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-6">
        <div class="card mt-3">
          <div class="card-body">
            <div class="text-center">
            <h3 class="card-title">Login</h3>
            <p class="card-text">Login to your account</p>
            </div>
            <form method="post">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control <?=empty($username_err) ? '' : 'is-invalid'?>" id="username" name="username" value="<?=$username?>">
                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                  <?=$username_err?>
                </div>
              </div>
              <div class="form-group mb-2">
                <label for="password">Password</label>
                <input type="password" class="form-control <?=empty($password_err) ? '' : 'is-invalid'?>" id="password" name="password" value="<?=$password?>">
                <div id="validationServerPasswordFeedback" class="invalid-feedback">
                  <?=$password_err?>
                </div>
              </div>
              <button type="submit" class="btn btn-primary mb-2">Login</button>
            </form>
            <p>I am not registered: <a href="register.php">Register</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
