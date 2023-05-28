<?php

session_start();

if(empty($_SESSION['logged_in'])){
  header('location: login.php');
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <div class="">
      <h3 class="text-center">
        Welcome <?=$_SESSION['users']['first_name'] . ' ' . $_SESSION['users']['last_name']?>
      </h3>
    </div>
    <div class="text-center">
    <a href="./logout.php" class="btn btn-dark">Log out</a>
  </div>
  </div>
</body>

</html>