<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>

<?php
// Show success message after signup
if (isset($_GET['signup']) && $_GET['signup'] === 'success') {
    echo "<p style='color: green;'>Account created successfully. You can now log in.</p>";
}
?>

<h2>Welcome to the Home Page</h2>

<a href="signup.php"><button>Sign Up</button></a>
<a href="login.php"><button>Log In</button></a>

</body>
</html>
