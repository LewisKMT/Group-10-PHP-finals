<?php
$conn = new mysqli('localhost', 'root', '', 'accounts');

$token = $_GET['token'] ?? '';
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_pass = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Validate password match
    if ($new_pass !== $confirm) {
        $error = "Passwords do not match!";
    }
    // Validate strong password
    elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $new_pass)) {
        $error = "Password must be at least 8 characters and include uppercase, lowercase, number, and symbol.";
    } else {
        // Check token validity and expiry
        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND token_expiry > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $userId = $row['id'];
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE id = ?");
            $stmt->bind_param("si", $hashed, $userId);
            $stmt->execute();

            $success = "Password has been reset. <a href='index.php'>Click here to login</a>.";
        } else {
            $error = "Invalid or expired token.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link rel="stylesheet" href="styles/resetPass-style.css">
</head>
<body>
  <form method="post">
    <h2>Reset Password</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

    New Password:<br>
    <input type="password" id="new_password" name="password" required><br><br>

    Confirm Password:<br>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>

    <div class="show_pass">
      <input type="checkbox" onclick="toggleLoginPassword()" placeholder="*">
      <p>Show Password</p>
    </div>

    <button type="submit">Reset Password</button>
  </form>

  <script>
      function togglePassword() {
          var pw1 = document.getElementById("new_password");
          var pw2 = document.getElementById("confirm_password");
          pw1.type = pw1.type === "password" ? "text" : "password";
          pw2.type = pw2.type === "password" ? "text" : "password";
      }
  </script>
</body>
</html>

