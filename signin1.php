<?php
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
    <title>Signin</title>
    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    

    <link rel="stylesheet" href="style1.css">
    
</head>
<body>
    <div class="container">

    <?php
     if (isset($_POST["submit"])) {
      $name= $_POST["name"];
      $email =$_POST["email"];
      $password =$_POST["password"];
      $repassword =$_POST["repassword"];

      // For encrypting password
      $passwordHash= password_hash($password,PASSWORD_DEFAULT);

      $errors= array();

      if (empty($name) OR empty($email) OR empty($password) OR empty($repassword)){
        array_push($errors, "All fields are required");
      }
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
      }
      if (strlen($password)<8){
        array_push($errors, "Password must be atleast 8 characters long");
      }
      if($password!==$repassword){
        array_push($errors,"Password does not match");
      }

      //check for already existed email
      $sql="SELECT * FROM signin WHERE Email= '$email' ";
      $result= mysqli_query($conn, $sql);
      $token = bin2hex(random_bytes(15));
      $rowCount = mysqli_num_rows($result);
      if ($rowCount > 0){
        array_push($errors,"Email already exists");
      }

      if(count($errors)>0){
        foreach($errors as $error){
          echo "<div class='alert alert-danger'>$error</div>";
        }
      }else{
        $sql= "INSERT INTO signin (Name,Email,Password,token) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt,$sql);

        if($prepareStmt) {
          mysqli_stmt_bind_param($stmt,"ssss",$name, $email, $passwordHash, $token);

          mysqli_stmt_execute($stmt);
          echo "<div class='alert alert-success'>You are registered successfully.</div>";
        }else{
          die("Something went wrong");
        }
      }
     }
    ?>
    <form action="signin1.php" method="post">
        <div class="form-group">
          <input type="text" class="form-control" name="name" placeholder="Enter your name" autocomplete="off">
        </div>
  
        <div class="form-group">
          <input type="email"  class="form-control" name="email" placeholder="Enter your email id" autocomplete="off">
        </div>
  
        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="Enter your password" autocomplete="off">
        </div>
  
        <div class="form-group">
          <input type="password" class="form-control" name="repassword" placeholder="Confirm your password" autocomplete="off">
        </div>
        <div class="form-btn">
            <input type="submit" class="btn btn-primary" value="Register" name="submit">
        </div>

        
        
      </form>
      <div style="padding-top:3%;" class="register">Already have an account? 
        <a href="login.php" style="padding-left: 1%;">Log in</a>
    </div>

   
    
    
</body>
</html>