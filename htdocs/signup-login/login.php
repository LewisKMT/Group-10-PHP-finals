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
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }
    // Password validation
    elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    }
    else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($hashed_password);

        if ($stmt->fetch() && password_verify($password, $hashed_password)) {
            header("Location: example-webpage.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<form method="post">
    <h2>Log In</h2>
    <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
    Email: <input type="email" name="email" required><br><br>
    Password (min 8 chars): <input type="password" name="password" required><br><br>
    <button type="submit">Log In</button>
</form>
