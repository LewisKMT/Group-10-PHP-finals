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
    $identifier = trim($_POST["identifier"]);  // Can be email or username
    $password = $_POST["password"];

    // Basic format check
    if (empty($identifier) || empty($password)) {
        $error = "Please fill in all fields.";
    }
    elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $stmt->bind_result($hashed_password);

        if ($stmt->fetch() && password_verify($password, $hashed_password)) {
            header("Location: example-webpage.php");
            exit();
        } else {
            $error = "Invalid username/email or password.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<form method="post">
    <h2>Login</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

    Username or Email: <input type="text" name="identifier" required><br><br>

    Password: <input type="password" id="login_password" name="password" required><br><br>

    <!-- Toggle Password Visibility -->
    <input type="checkbox" onclick="toggleLoginPassword()"> Show Password<br><br>

    <button type="submit">Login</button>
</form>

<!-- Forgot Password Button -->
<form action="forgot-password.php" method="get">
    <button type="submit">Forgot Password?</button>
</form>

<script>
function toggleLoginPassword() {
    var password = document.getElementById("login_password");
    password.type = (password.type === "password") ? "text" : "password";
}
</script>
