<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>Compass</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      background-color: rgb(241, 239, 235);
      display: flex;
      min-height: 100vh;
      flex-direction: row;
      overflow: auto;
    }

    .image-section {
      width: 60%;
      background-color: #ccc;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    .image-section img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .right-section {
      width: 40%;
      background: linear-gradient(to bottom right, white, rgba(255, 204, 102, 0.62));
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 1rem;
      position: relative;
      padding-top: 0;
    }

    .logo {
      width: 100%;
      max-width: 700px;
      text-align: center;
      margin-bottom: 0.5rem;
      margin: 0;
    }

    .logo img {
      max-width: 700px;
      align-items: center;
      position: relative;
    }

    .welcome-box-wrapper {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding-top: 0.5rem;
      height: auto;
      flex-grow: 1;
      width: 100%;
    }

    .welcome-box {
      background-color: #000;
      padding: 2rem;
      border-radius: 15px;
      width: 100%;
      max-width: 450px;
      height: 400px;
      text-align: center;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    h2 {
      margin-bottom: 1rem;
      color: #ffcc66;
      font-size: 2rem;
    }

    .message {
      margin-bottom: 1rem;
      color: #00cc66;
      font-weight: bold;
    }

    .btn {
      background-color: rgb(51, 51, 51);
      color: white;
      padding: 1rem;
      margin: 0.5rem 0;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      cursor: pointer;
      width: 100%;
      transition: background 0.3s ease;
      box-shadow: 0 0 5px rgb(201, 200, 200);
    }

    .btn:hover {
      background-color: #005580;
    }

    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 1rem 0;
      color: #ccc;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid #555;
    }

    .divider:not(:empty)::before {
      margin-right: 0.75em;
    }

    .divider:not(:empty)::after {
      margin-left: 0.75em;
    }

    .info-text {
      color: #ccc;
      margin-top: 1rem;
      font-size: 0.95rem;
    }

    a {
      text-decoration: none;
    }

    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }

      .image-section,
      .right-section {
        width: 100%;
      }

      .image-section {
        height: 200px;
      }

      .logo,
      .welcome-box {
        max-width: 100%;
      }

      .welcome-box-wrapper {
        height: auto;
        padding: 2rem 0;
      }
    }
  </style>
</head>
<body>

  <div class="image-section">
     <img src="images/sidecompass.jpg" alt="Side Image">
  </div>

  <div class="right-section">
    <div class="logo">
      <img src="images/logo.png" alt="Logo">
    </div>

    <div class="welcome-box-wrapper">
      <div class="welcome-box">
        <?php
        // Show success message after signup
        if (isset($_GET['signup']) && $_GET['signup'] === 'success') {
            echo "<p class='message'>Account created successfully. You can now log in.</p>";
        }
        ?>

        <h2>Welcome!</h2>
        <a href="login.php"><button class="btn">Log In</button></a>

        <div class="divider">OR</div>

        <p class="info-text">Don't have an account?</p>
        <a href="signup.php"><button class="btn">Sign Up</button></a>
      </div>
    </div>
  </div>

</body>
</html>
