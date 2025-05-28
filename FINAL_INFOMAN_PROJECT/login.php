<?php

session_start();
if(isset($_SESSION["user"])){
  header("Location: main_page.php"); // if an account is logged in, redirect to main_page.php
  die();
}

// Define a key for encryption and decryption
$key = 'your-encryption-key-here'; // Use a secure key

// encryption and decryption functions
function encrypt($data, $key) {
    $encryption_key = base64_decode($key);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decrypt($data, $key) {
    $encryption_key = base64_decode($key);
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}

$error_message = '';

if(isset($_POST["login"])){
  $username = $_POST["username"];
  $password = $_POST["password"];
  $remember = isset($_POST["remember"]);

  require_once "database.php";

  $sql = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
      $passwordHash = $row["password"];
      if(password_verify($password, $passwordHash)){
        $_SESSION["user"] = "yes";
        $_SESSION["user_id"] = $row["id"]; // Store user ID in session
        
        if($remember){
          setcookie("username", $username, time() + (86400 * 30), "/"); // 30 days
          $encryptedPassword = encrypt($password, $key);
          setcookie("password", $encryptedPassword, time() + (86400 * 30), "/"); // 30 days
        } else {
          if(isset($_COOKIE["username"])){
            setcookie("username", "", time() - 3600, "/");
          }
          if(isset($_COOKIE["password"])){
            setcookie("password", "", time() - 3600, "/"); // Clear the password cookie if "Remember Me" is not checked
          }
        }

        header("Location: main_page.php"); // Redirect to main_page.php after successful login
        die(); // Terminate the script to ensure no further code is executed
      } else {
        $error_message = "Incorrect Password"; // Set error message for incorrect password
      }
    }
  } else {
    $error_message = "User Not Found"; // Set error message for user not found
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="logins.css"> <!-- Link to external CSS file -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap"> <!-- Link to Google Fonts -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- Link to Boxicons CSS -->
  <link rel="icon" type = "image/x-icon" href="nuexlogo.png">
</head>
<body>

  <div class="wrapper">
    <form action="login.php" method="post"> 
      <h1>NU Bulldogs Exchange</h1>
      <div class="input-box">
        <input type="text" name="username" placeholder="Username" value="<?php if(isset($_COOKIE["username"])) echo $_COOKIE["username"]; ?>" required>
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Password" value="<?php if(isset($_COOKIE["password"])) echo decrypt($_COOKIE["password"], $key); ?>" required>
        <i class='bx bxs-lock-alt'></i>
      </div>

      <?php
      if(!empty($error_message)){
        echo "<div class='alert alert-danger'>$error_message</div>";
      }
      ?>

      <div class="remember-forgot">
        <label><input type="checkbox" name="remember" <?php if(isset($_COOKIE["username"])) echo "checked"; ?>> Remember me</label>
      </div>
      <button type="submit" class="btn" name="login">Login</button>
      <div class="register-link">
        <p>Don't have an account? <a href="register.php">Register</a></p>
      </div>
    </form>
  </div>

</body>
</html>