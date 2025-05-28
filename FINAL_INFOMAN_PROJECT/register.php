<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="registers.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="icon" type = "image/x-icon" href="nuexlogo.png">
</head>
<body>

  <div class="wrapper">
    <form action="register.php" method="post">
      <h1>Registration</h1>
      <div class="input-box">
        <input type="text" name="firstName" placeholder="First Name">
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="text" name="lastName" placeholder="Last Name">
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="text" name="email" placeholder="Email">
        <i class='bx bx-envelope'></i>
      </div>
      <div class="input-box">
        <input type="text" name="username" placeholder="Username">
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Password">
        <i class='bx bxs-lock-alt'></i>
      </div>
      <div class="input-box">
        <input type="password" name="repeatPassword" placeholder="Repeat Password">
        <i class='bx bxs-lock-alt'></i>
      </div>

      <?php
        if(isset($_POST["submit"])){
          $firstName = $_POST["firstName"];
          $lastName = $_POST["lastName"];
          $email = $_POST["email"];
          $username = $_POST["username"];
          $password = $_POST["password"];
          $passwordRepeat = $_POST["repeatPassword"];
                   
          $passwordHash = password_hash($password, PASSWORD_DEFAULT);

          $errors = array();

          if(empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password) || empty($passwordRepeat)){
            array_push($errors,"All fields are required");
          } else {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
              array_push($errors, "Invalid email address");
            }
            if(strlen($password) < 8){
              array_push($errors, "Password must be at least 8 characters long");
            }
            if($password != $passwordRepeat){
              array_push($errors, "Passwords do not match");
            }
          }

          require_once "database.php"; // START DATABASE CONNECTION

          $sql = "SELECT * FROM users WHERE email = '$email'"; // unique email
          $result = mysqli_query($conn, $sql);
          $rowCount = mysqli_num_rows($result);

          if($rowCount > 0){
            array_push($errors, "Email already exists");
          }

          $sql = "SELECT * FROM users WHERE username = '$username'"; // unique username
          $result = mysqli_query($conn, $sql);
          $rowCount = mysqli_num_rows($result);

          if($rowCount > 0){
            array_push($errors, "Username already exists");
          }

          if(count($errors) > 0){
            foreach($errors as $error){
              echo "<div class='alert alert-danger'>$error</div>";
            }
          } else {
 
            $sql = "INSERT INTO users (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)"; // ? placeholder
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
            if($prepareStmt){
              mysqli_stmt_bind_param($stmt, "sssss", $firstName, $lastName, $email, $username, $passwordHash);
              mysqli_stmt_execute($stmt);
              echo "<div class='success-box'>";
              echo "<p>Registration Successful</p>";
              echo "</div>";
            }
            
            else {
             die("Error: ".mysqli_error($conn));
            }   
          }  
        }  
      ?>

      <button type="submit" class="btn" name="submit">Register</button>
      <div class="register-link">
        <p>Already have an account? <a href="login.php">Log In</a></p>
      </div>
    </form>
  </div>
</body>
</html>