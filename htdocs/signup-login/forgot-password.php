<?php
$conn = new mysqli('localhost', 'root', '', 'accounts');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(16));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        echo "Reset token generated!<br>";
        echo "Copy and paste this link into your browser:<br>";
        echo "<a href='reset-password.php?token=$token'>reset-password.php?token=$token</a>";
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
}
?>

<form method="post">
    <h2>Forgot Password</h2>
    Enter your email:<br>
    <input type="email" name="email" required><br><br>
    <button type="submit">Generate Reset Link</button>
</form>
