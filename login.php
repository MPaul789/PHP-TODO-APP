<?php

session_start();

if(isset($_SESSION ["user"])){
    header("location: index.php");
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <link rel="stylesheet" href="style1.css">
</head>
<body>
<div class="container">
<?php
if (isset($_POST["login"])) {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM signin WHERE Email= '$email'";
    $result= mysqli_query($conn, $sql);

    $user= mysqli_fetch_array($result, MYSQLI_ASSOC);

    if($user){
        if(password_verify($password, $user["Password"])){
            session_start();
            $_SESSION["user"] = "yes";
            header("Location: index.php");
            die();
        }else{
            echo "<div class= 'alert alert-danger'>Password does not match</div>";
        }
    }else{
        echo "<div class= 'alert alert-danger'>Email does not exist
</div>";
    }
}

?>
<form action="login.php" method="post">
<div class=form-group>
<input type="email" placeholder="Enter your Email id" name="email" class="form-control" autocomplete="off">
</div>

<div class=form-group>
<input type="password" placeholder="Enter your Password" name="password" class="form-control" autocomplete="off">
</div>

<div class=form-btn>
<input type="submit" value="Login" name="login" class="btn btn-primary">
</div>
</form>

<div style="padding-top:3%;" class="register">Don't have an account yet ?
<a href="signin1.php" style="padding-left: 1%;">Create an account </a>
</div>






</body>
</html>