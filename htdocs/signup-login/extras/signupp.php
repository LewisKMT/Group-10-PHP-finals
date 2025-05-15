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

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (empty($username)) {
        $error = "Username is required.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $error = "Password must meet complexity requirements.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Signup</title>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap");

    :root {
      --brown: #993300;
      --grey: #333333;
      --black: #000000;
      --yellow: #ffcc66;
      --white: #ffffff;
      --blue: #006699;
      --blue-hover: #008799;
      --blue-active: #00adb3;
    }

    * {
      padding: 0;
      margin: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--brown), var(--blue));
      padding: 20px;
    }

    .container {
      width: 100%;
      max-width: 500px;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(20px);
      border: 2px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
      padding: 2rem;
      animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    form {
      display: flex;
      flex-direction: column;
    }

    h1 {
      color: var(--yellow);
      text-align: center;
      margin-bottom: 1.5rem;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      background: rgba(255, 255, 255, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: var(--white);
      padding: 0.75rem 1rem;
      border-radius: 12px;
      margin-bottom: 1rem;
      transition: background 0.3s, box-shadow 0.3s;
    }

    input::placeholder {
      color: #ddd;
    }

    input:focus {
      outline: none;
      background: rgba(255, 255, 255, 0.3);
      box-shadow: 0 0 0 3px var(--blue);
    }

    .show_pass {
      display: flex;
      align-items: center;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
      color: var(--white);
    }

    .show_pass input {
      margin-right: 8px;
      accent-color: var(--yellow);
    }

    .login_button {
      background: var(--blue);
      color: var(--white);
      border: none;
      padding: 0.75rem;
      border-radius: 20px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s;
    }

    .login_button:hover {
      background: var(--blue-hover);
      transform: translateY(-2px);
    }

    .login_button:active {
      background: var(--blue-active);
      transform: scale(0.97);
    }

    .tooltip {
      background-color: rgba(0, 0, 0, 0.8);
      color: var(--white);
      border-radius: 10px;
      padding: 1rem;
      font-size: 0.85rem;
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      z-index: 10;
      margin-top: 0.5rem;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.4);
      display: none;
    }

    .tooltip ul {
      padding-left: 1.25rem;
    }

    .tooltip li {
      margin-bottom: 0.25rem;
    }

    .tooltip.show {
      display: block;
      animation: fadeIn 0.3s ease;
    }

    .tooltip li.valid {
      color: #00e676;
    }

    .tooltip li.invalid {
      color: #ff5252;
    }

    .error-msg {
      color: #ff6b6b;
      text-align: center;
      margin-bottom: 1rem;
      font-weight: 500;
    }

    @media screen and (max-width: 600px) {
      .container {
        padding: 1.5rem;
      }

      input {
        font-size: 1rem;
      }

      .tooltip {
        font-size: 0.75rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <form method="post">
      <h1>Sign Up</h1>

      <?php if ($error) echo "<p class='error-msg'>$error</p>"; ?>

      <input type="text" name="username" placeholder="Username" required />
      <input type="email" name="email" placeholder="Email" required />

      <div style="position: relative;">
        <input type="password" id="password" name="password" placeholder="Password" required
          onfocus="showTooltip()" onblur="hideTooltip()" oninput="validatePassword()" />
        <div class="tooltip" id="passwordTooltip">
          <ul>
            <li id="length" class="invalid">At least 8 characters</li>
            <li id="uppercase" class="invalid">One uppercase letter</li>
            <li id="lowercase" class="invalid">One lowercase letter</li>
            <li id="number" class="invalid">One number</li>
            <li id="special" class="invalid">One special character</li>
          </ul>
        </div>
      </div>

      <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required />

      <div class="show_pass">
        <input type="checkbox" onclick="togglePassword()" />
        <label>Show Password</label>
      </div>

      <button class="login_button" type="submit">Sign Up</button>
    </form>
  </div>

  <script>
    function togglePassword() {
      const pw = document.getElementById("password");
      const cpw = document.getElementById("confirm_password");
      const isText = pw.type === "text";
      pw.type = isText ? "password" : "text";
      cpw.type = isText ? "password" : "text";
    }

    function showTooltip() {
      document.getElementById("passwordTooltip").classList.add("show");
    }

    function hideTooltip() {
      document.getElementById("passwordTooltip").classList.remove("show");
    }

    function validatePassword() {
      const pw = document.getElementById("password").value;
      document.getElementById("length").className = pw.length >= 8 ? "valid" : "invalid";
      document.getElementById("uppercase").className = /[A-Z]/.test(pw) ? "valid" : "invalid";
      document.getElementById("lowercase").className = /[a-z]/.test(pw) ? "valid" : "invalid";
      document.getElementById("number").className = /\d/.test(pw) ? "valid" : "invalid";
      document.getElementById("special").className = /[\W_]/.test(pw) ? "valid" : "invalid";
    }
  </script>
</body>
</html>
