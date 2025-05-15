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
     <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap");

:root {
  --brown: #993300;
  --grey: #333333;
  --black: #000000;
  --yellow: #ffcc66;
  --white: #ffffff;
  --blue: #006699;
  --blue-hover: #008799;
  --blue-active: #00adb3;
}

* {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, var(--brown), var(--blue));
  padding: 20px;
}

.container {
  width: 100%;
  max-width: 500px;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(20px);
  border: 2px solid rgba(255, 255, 255, 0.2);
  border-radius: 20px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
  padding: 2rem;
  animation: fadeIn 0.6s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

form {
  display: flex;
  flex-direction: column;
}

h1 {
  color: var(--yellow);
  text-align: center;
  margin-bottom: 1.5rem;
}

input[type="text"],
input[type="email"],
input[type="password"] {
  background: rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.3);
  color: var(--white);
  padding: 0.75rem 1rem;
  border-radius: 12px;
  margin-bottom: 1rem;
  transition: background 0.3s, box-shadow 0.3s;
}

input::placeholder {
  color: #ddd;
}

input:focus {
  outline: none;
  background: rgba(255, 255, 255, 0.3);
  box-shadow: 0 0 0 3px var(--blue);
}

.show_pass {
  display: flex;
  align-items: center;
  margin-bottom: 1.5rem;
  font-size: 0.9rem;
  color: var(--white);
}

.show_pass input {
  margin-right: 8px;
  accent-color: var(--yellow);
}

.login_button {
  background: var(--blue);
  color: var(--white);
  border: none;
  padding: 0.75rem;
  border-radius: 20px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.2s;
}

.login_button:hover {
  background: var(--blue-hover);
  transform: translateY(-2px);
}

.login_button:active {
  background: var(--blue-active);
  transform: scale(0.97);
}

.tooltip {
  background-color: rgba(0, 0, 0, 0.8);
  color: var(--white);
  border-radius: 10px;
  padding: 1rem;
  font-size: 0.85rem;
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  z-index: 10;
  margin-top: 0.5rem;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.4);
  display: none;
}

.tooltip ul {
  padding-left: 1.25rem;
}

.tooltip li {
  margin-bottom: 0.25rem;
}

.tooltip.show {
  display: block;
  animation: fadeIn 0.3s ease;
}

.tooltip li.valid {
  color: #00e676;
}

.tooltip li.invalid {
  color: #ff5252;
}

.error-msg {
  color: #ff6b6b;
  text-align: center;
  margin-bottom: 1rem;
  font-weight: 500;
}

@media screen and (max-width: 600px) {
  .container {
    padding: 1.5rem;
  }

  input {
    font-size: 1rem;
  }

  .tooltip {
    font-size: 0.75rem;
  }
}

.forgot_pass_button {
  background: transparent;
  border: none;
  color: var(--yellow);
  font-weight: 600;
  cursor: pointer;
  padding: 0;
  margin-top: 1rem;
  text-align: center;
  font-size: 0.9rem;
  transition: color 0.3s ease;

}

.forgot_pass_button:hover {
  color: var(--blue-active);
  text-decoration: none;
}

     </style>
  </head>
  <body>
    <div class="container">
      <div class="cell">
        <form method="post">
          <h1>Login</h1><br>
          <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

          
          <input type="text" placeholder = "Username or Email" name="email" required><br>

  
          <input type="password" placeholder = "Password" id="login_password" name="password" required><br>

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