<?php
$host = 'localhost';
$db = 'accounts';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $bio = trim($_POST["bio"]);

    // Required field validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all required fields.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $error = "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
    }
    elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    }
    else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, first_name, last_name, bio) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $email, $hashed_password, $first_name, $last_name, $bio);

        if ($stmt->execute()) {
            header("Location: index.php?signup=success");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles/signup-style.css">
  </head>
  <body>
    
    <div class="container">
      <div class="cell">
        <form method="post">
          <h1>Sign Up</h1>
          <small class="notice">Fields with <span style="color: red;">*</span> requires input.</small><br><br>
          <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

          <label>Username <span style="color: red;">*</span>:</label><br>
          <input type="text" name="username" required><br><br>

          <label>Email <span style="color: red;">*</span>:</label><br>
          <input type="email" name="email" required><br><br>

          <label>Password <span style="color: red;">*</span>:</label><br>
          <input type="password" id="password" name="password" required><br>
          <small>Password must be at least 8 characters and include uppercase, lowercase, number, and special character.</small><br><br>

          <label>Confirm Password <span style="color: red;">*</span>:</label><br>
          <input type="password" id="confirm_password" name="confirm_password" required><br><br>

          <div class="show_pass">
            <input type="checkbox" onclick="togglePassword()" placeholder="*">
            <p>Show Password</p>
          </div>

          <!-- Optional Fields -->
          <label>First Name (optional):</label><br>
          <input type="text" name="first_name"><br><br>

          <label>Last Name (optional):</label><br>
          <input type="text" name="last_name"><br><br>

          <label>Bio (optional):</label><br>
          <textarea name="bio" rows="3" cols="30"></textarea><br><br>

          <button class="signup_button" type="submit">Sign Up</button>
        </form>
      </div>
      <div class="placeholder"></div>
    </div>
    

    <script>
    function togglePassword() {
        var pass = document.getElementById("password");
        var confirm = document.getElementById("confirm_password");
        pass.type = (pass.type === "password") ? "text" : "password";
        confirm.type = (confirm.type === "password") ? "text" : "password";
    }
    </script>
  </body>
</html>