<?php
$conn = new mysqli('localhost', 'root', '', 'accounts');

$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_pass = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $token = $_POST['token'];

    if ($new_pass !== $confirm) {
        $error = "Passwords do not match!";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $new_pass)) {
        $error = "Password must be strong (8+ chars, upper/lower/digit/special)";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND token_expiry > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE id = ?");
            $stmt->bind_param("si", $hashed, $row['id']);
            $stmt->execute();
            echo "Password has been reset. You may <a href='index.php'>log in now</a>.";
        } else {
            $error = "Invalid or expired token.";
        }
    }
}
?>

<form method="post">
    <h2>Reset Password</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    New Password: <input type="password" name="password" required><br><br>
    Confirm Password: <input type="password" name="confirm_password" required><br><br>
    <button type="submit">Reset Password</button>
</form>
