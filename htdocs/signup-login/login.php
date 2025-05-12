<?php
$host = 'localhost';
$db = 'accounts';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $stmt->bind_result($hashed_password);
    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        // Redirect on successful login
        header("Location: example-webpage.php");
        exit();
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
}
$conn->close();
?>

<form method="post">
    <h2>Log In</h2>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Log In</button>
</form>
