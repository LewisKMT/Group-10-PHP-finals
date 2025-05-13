<?php
session_start();

session_start();

$host = 'localhost';
$db = 'accounts';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

// Initialize lockout session variables
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = null;
}

// Check if user is locked out
if ($_SESSION['login_attempts'] >= 3) {
    $lockout_duration = 60; // 1 minute in seconds
    $time_since_lockout = time() - $_SESSION['lockout_time'];

    if ($time_since_lockout < $lockout_duration) {
        $remaining = $lockout_duration - $time_since_lockout;
        $error = "Too many failed attempts. Try again in $remaining seconds.";
    } else {
        // Reset attempts after timeout
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lockout_time'] = null;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['login_attempts'] < 3) {
    $identifier = trim($_POST["identifier"]);
// Initialize lockout session variables
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = null;
}

// Check if user is locked out
if ($_SESSION['login_attempts'] >= 3) {
    $lockout_duration = 60; // 1 minute in seconds
    $time_since_lockout = time() - $_SESSION['lockout_time'];

    if ($time_since_lockout < $lockout_duration) {
        $remaining = $lockout_duration - $time_since_lockout;
        $error = "Too many failed attempts. Try again in $remaining seconds.";
    } else {
        // Reset attempts after timeout
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lockout_time'] = null;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['login_attempts'] < 3) {
    $identifier = trim($_POST["identifier"]);
    $password = $_POST["password"];

    // Validation
    if (strlen($password) < 8) {
    // Validation
    if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        // Allow login via email or username
        // Allow login via email or username
        $stmt = $conn->prepare("SELECT password FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $stmt->bind_result($hashed_password);

        if ($stmt->fetch() && password_verify($password, $hashed_password)) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_time'] = null;
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_time'] = null;
            header("Location: example-webpage.php");
            exit();
        } else {
            $_SESSION['login_attempts'] += 1;
            if ($_SESSION['login_attempts'] >= 3) {
                $_SESSION['lockout_time'] = time();
                $error = "Too many failed attempts. You are locked out for 1 minute.";
            } else {
                $remaining = 3 - $_SESSION['login_attempts'];
                $error = "Invalid email/username or password. Attempts left: $remaining.";
            }
        }
            $_SESSION['login_attempts'] += 1;
            if ($_SESSION['login_attempts'] >= 3) {
                $_SESSION['lockout_time'] = time();
                $error = "Too many failed attempts. You are locked out for 1 minute.";
            } else {
                $remaining = 3 - $_SESSION['login_attempts'];
                $error = "Invalid email/username or password. Attempts left: $remaining.";
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/login-style.css">
  </head>
  <body>
    <div class="container">
      <div class="cell">
        <form method="post">
          <h1>Login</h1>
          <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

          Username or Email:<br>
          <input type="text" name="identifier" required><br><br>

          Password:<br>
          <input type="password" id="login_password" name="password" required><br><br>

          <!-- Toggle Password Visibility -->
          <div class="show_pass">
            <input type="checkbox" onclick="toggleLoginPassword()" placeholder="*">
            <p>Show Password</p>
          </div>

          <button class="login_button" type="submit">Login</button>
        </form>

        <!-- Forgot Password Button -->
        <form class="forgot_pass" action="forgot-password.php" method="get">
          <button class="forgot_pass_button" type="submit">Forgot Password?</button>
        </form>
      </div>
    </div>
    <div>
      <img class="alden" src="alden.jpg" alt="">
    </div>

    <script>
      function toggleLoginPassword() {
        var password = document.getElementById("login_password");
        password.type = (password.type === "password") ? "text" : "password";
      }
    </script>
    
  </body>
</html>