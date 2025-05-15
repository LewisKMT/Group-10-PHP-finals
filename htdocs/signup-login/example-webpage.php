<?php
$host = 'localhost';
$db = 'accounts';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = null;
if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $stmt = $conn->prepare("SELECT username, email, first_name, last_name, bio FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Example Landing Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFF;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: #006699;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #FFCC66;
            border: 5px solid #993300;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #33333388;
        }

        .label {
            font-weight: bold;
            color: #000000;
        }

        .value {
            margin-bottom: 15px;
            display: block;
            color: #000000;
        }

        .logout-btn {
            background-color: #006699;
            color: #FFFFFF;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #333333;
        }
    </style>
</head>
<body>

<header>
    <h1>Example Landing Page</h1>
</header>

<div class="container">
    <?php if ($user): ?>
        <p><span class="label">Username:</span> <span class="value"><?= htmlspecialchars($user['username']) ?></span></p>
        <p><span class="label">Email:</span> <span class="value"><?= htmlspecialchars($user['email']) ?></span></p>
        <p><span class="label">First Name:</span> <span class="value"><?= htmlspecialchars($user['first_name']) ?></span></p>
        <p><span class="label">Last Name:</span> <span class="value"><?= htmlspecialchars($user['last_name']) ?></span></p>
        <p><span class="label">Bio:</span> <span class="value"><?= nl2br(htmlspecialchars($user['bio'])) ?></span></p>
    <?php elseif (isset($_GET['email'])): ?>
        <p>User not found.</p>
    <?php else: ?>
        <p>No user specified.</p>
    <?php endif; ?>

    <form action="index.php" method="get">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>

</body>
</html>
