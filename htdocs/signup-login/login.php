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

// Set up session tracking if not already set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = null;
}

$lockout_duration = 60; // seconds
$max_attempts = 3;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = trim($_POST["email"]); // can be email or username
    $password = $_POST["password"];

    // Check if user is locked out
    if ($_SESSION['login_attempts'] >= $max_attempts) {
        $elapsed = time() - $_SESSION['lockout_time'];
        if ($elapsed < $lockout_duration) {
            $remaining = $lockout_duration - $elapsed;
            $error = "Too many failed attempts. Please try again after $remaining seconds.";
        } else {
            // Lockout expired — reset
            $_SESSION['login_attempts'] = 0;
            $_SESSION['lockout_time'] = null;
        }
    }

    // Proceed only if not locked out
    if ($_SESSION['login_attempts'] < $max_attempts && !$error) {
        if (!filter_var($input, FILTER_VALIDATE_EMAIL) && !preg_match('/^\w{3,}$/', $input)) {
            $error = "Enter a valid email or username.";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters.";
        } else {
            $stmt = $conn->prepare("SELECT password FROM users WHERE email = ? OR username = ?");
            $stmt->bind_param("ss", $input, $input);
            $stmt->execute();
            $stmt->bind_result($hashed_password);

            if ($stmt->fetch() && password_verify($password, $hashed_password)) {
                // Login success
                $_SESSION['login_attempts'] = 0;
                $_SESSION['lockout_time'] = null;
                $stmt->close();
                header("Location: example-webpage.php");
                exit();
            } else {
                $_SESSION['login_attempts']++;
                if ($_SESSION['login_attempts'] >= $max_attempts) {
                    $_SESSION['lockout_time'] = time();
                    $error = "Too many failed attempts. Please wait $lockout_duration seconds.";
                } else {
                    $remaining_attempts = $max_attempts - $_SESSION['login_attempts'];
                    $error = "Invalid credentials. You have $remaining_attempts attempt(s) left.";
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
          <input type="text" name="email" required><br><br>

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
    <div class="placeholder">
</div>

    <script>
      function toggleLoginPassword() {
        var password = document.getElementById("login_password");
        password.type = (password.type === "password") ? "text" : "password";
      }
    </script>
    
  </body>
</html>