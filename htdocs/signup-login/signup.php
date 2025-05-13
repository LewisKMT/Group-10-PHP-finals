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

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }
    // Username validation
    elseif (empty($username)) {
        $error = "Username is required.";
    }
    // Strong password validation
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $error = "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
    }
    // Confirm password validation
    elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    }
    else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

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

<form method="post">
    <h2>Sign Up</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    
    Username: <input type="text" name="username" required><br><br>
    
    Email: <input type="email" name="email" required><br><br>
    
    Password: <input type="password" id="password" name="password" required><br>
    <small>Password must be at least 8 characters and include:</small><br>
    <ul style="margin-top: 5px; margin-bottom: 10px;">
        <li>At least one uppercase letter</li>
        <li>At least one lowercase letter</li>
        <li>At least one number</li>
        <li>At least one special character</li>
    </ul>
    
    Confirm Password: <input type="password" id="confirm_password" name="confirm_password" required><br><br>
    
    <!-- Toggle Password Visibility -->
    <input type="checkbox" onclick="togglePassword()"> Show Password<br><br>
    
    <button type="submit">Sign Up</button>
</form>

<script>
function togglePassword() {
    var password = document.getElementById("password");
    var confirmPassword = document.getElementById("confirm_password");
    password.type = password.type === "password" ? "text" : "password";
    confirmPassword.type = confirmPassword.type === "password" ? "text" : "password";
}
</script>
