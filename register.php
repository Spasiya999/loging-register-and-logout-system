<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$validation_failed = false;

$first_name = $last_name = $username = $password = $confirm_password = '';
$first_name_err = $last_name_err = $username_err = $password_err = $confirm_password_err = '';

//get user deatils from the form and save them in database
if($_SERVER['REQUEST_METHOD'] == 'POST') {

  require_once 'db-config.php';
  //get firstname from the form and is this empty echo enter your first name
  if(empty(trim($_POST['first_name']))){
    $first_name_err = 'Please enter your first name';
    $validation_failed = true;
  }else{
    $first_name = trim($_POST['first_name']);
  }

  //get lastname from the form and is this empty echo enter your last name
  if(empty(trim($_POST['last_name']))){
    $last_name_err = 'Please enter your last name';
    $validation_failed = true;
  }else{
    $last_name = trim($_POST['last_name']);
  }
  
  //get username from the form and is this empty echo enter your username
  if(empty(trim($_POST['username']))){
    $username_err = 'Please enter your username';
    $validation_failed = true;
  }else{
    $username = trim($_POST['username']);
  }

  //check if username already exists
  $sql1 = "SELECT * FROM users WHERE username='".$username . "'";
  $result = $conn->query($sql1);
  if($result->num_rows > 0){
    $username_err = 'Username already exists';
    $validation_failed = true;
  }

  //get password from the form and is this empty echo enter your password and if this is less than 6 characters echo password must be at least 6 characters
  if(empty(trim($_POST['password']))){
    $password_err = 'Please enter your password';
    $validation_failed = true;
  }elseif(strlen(trim($_POST['password'])) < 6){
    $password_err = 'Password must be at least 6 characters';
    $validation_failed = true;
  }else{
    $password = trim($_POST['password']);
  }

  //get confirm password from the form and is this empty echo confirm your password and if this is not equal to password echo passwords do not match
  if(empty(trim($_POST['confirm_password']))){
    $confirm_password_err = 'Please confirm your password';
    $validation_failed = true;
  }else{
    $confirm_password = trim($_POST['confirm_password']);
    if($password != $confirm_password){
      $confirm_password_err = 'Passwords do not match';
      $validation_failed = true;
    }
  }

  //get this all data and save to database with sha1 password and register date = current date and if this is empty echo error
  if (!$validation_failed) {

    $stm = $conn->prepare("INSERT INTO users (first_name, last_name, username, password, reg_date, status) VALUES (?, ?, ?, ?, ?, ?)");

    $status = 'active';
    $reg_date = time();

    $hashed_password = sha1($password);
    $stm->bind_param('ssssis',  $first_name, $last_name, $username, $hashed_password, $reg_date, $status);

    $result = $stm->execute();

    if ($result) {
        header('location: login.php');
    } else {
        $error = 'Somthing wen wrong';
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  <title>Register</title>
</head>
<body>
  <!-- make register form -->
  <div class="container">
    <div class="row">
      <div class="col-6 offset-3 card mt-3">
        <h3 class="text-center">Register</h3>
        <div class="card-body">
        <form action="" method="POST">
          <div class="mb-2">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name">
            <?php if(!empty($first_name_err)): ?>
              <div class="alert alert-danger mt-1" role="alert">
                <?=$first_name_err?>
              </div>
            <?php endif; ?>
          </div>
          <div class="mb-2">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name">
            <?php if(!empty($last_name_err)): ?>
              <div class="alert alert-danger mt-1" role="alert">
                <?=$last_name_err?>
              </div>
            <?php endif; ?>
          </div>
          <div class="mb-2">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username">
            <?php if(!empty($username_err)): ?>
              <div class="alert alert-danger mt-1" role="alert">
                <?=$username_err?>
              </div>
            <?php endif; ?>
          </div>
          <div class="mb-2">
            <label for="password" class="form-label">Password</label>
            <input type="text" class="form-control" id="password" name="password">
            <?php if(!empty($password_err)): ?>
              <div class="alert alert-danger mt-1" role="alert">
                <?=$password_err?>
              </div>
            <?php endif; ?>
          </div>
          <div class="mb-2">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="text" class="form-control" id="confirm_password" name="confirm_password">
            <?php if(!empty($confirm_password_err)): ?>
              <div class="alert alert-danger mt-1" role="alert">
                <?=$confirm_password_err?>
              </div>
            <?php endif; ?>
          </div>
          <div class="mb-2">
            <button type="submit" class="btn btn-primary">Register</button>
          </div>
        </form>
        <P>
          Already have an account? <a href="./login.php">Login</a>
        </P>
        </div>
      </div>
    </div>
  </div>
</body>
</html>