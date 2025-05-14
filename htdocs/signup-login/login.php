<?php
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

// Track login attempts using session
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION['login_attempts'] >= 3 && time() < $_SESSION['lockout_time']) {
        $error = "Too many failed attempts. Try again later.";
    } else {
        $input = trim($_POST["email"]);  // Can be email or username
        $password = $_POST["password"];

        if (empty($input) || empty($password)) {
            $error = "All fields are required.";
        } else {
            // Check if input is email or username
            if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
            } else {
                $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
            }

            $stmt->bind_param("s", $input);
            $stmt->execute();
            $stmt->bind_result($hashed_password);

            if ($stmt->fetch() && password_verify($password, $hashed_password)) {
                // Reset attempts and redirect
                $_SESSION['login_attempts'] = 0;
                $_SESSION['lockout_time'] = null;
                header("Location: example-webpage.php");
                exit();
            } else {
                $_SESSION['login_attempts']++;
                if ($_SESSION['login_attempts'] >= 3) {
                    $_SESSION['lockout_time'] = time() + 60; // 1 minute lockout
                    $error = "Too many failed attempts. Try again in 1 minute.";
                } else {
                    $error = "Invalid login credentials.";
                }
            }

            $stmt->close();
        }
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
          <h1>Login</h1><br><br>
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
    <div class="placeholder"></div>

    <script>
      function toggleLoginPassword() {
        var password = document.getElementById("login_password");
        password.type = (password.type === "password") ? "text" : "password";
      }
    </script>
    
  </body>
</html>