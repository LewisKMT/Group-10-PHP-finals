<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <style>

  * {
	padding: 0;
	margin: 0;
	text-decoration: none;
	list-style-type: none;
	font-family: "Poppins", sans-serif;
	box-sizing: border-box;
	scroll-behavior: smooth;
  }

  body {
    height: 100vh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #333333;
  }

  header {
        background-color: aliceblue;
        width: 100%;
        height: 4rem;
        position: fixed;
        top: 0;
        display: flex;
        align-items: center;
        padding: 0 1em;
        z-index: 100;
      }
  
  nav {
    margin-left: auto;
    display: flex;
    height: 100%;
    align-content: center;
  }

  nav a {
    padding-inline: 1rem;
    align-content: center;
    font-weight: 400;
    font-size: 1.5rem;
    height: 100%;
  }

  nav a:hover {
    background-color: #00adb5;
    transition: ease-in 0.1s;
  }

  nav a:active {
    background-color: #0098a0;
    transition: ease-in 0.1s;
  }
  </style>
</head>
<body>
  <header>
    <h2 style="text-transform: uppercase;">COMPASS</h2>
      <nav>
        <a>Home</a>
        <a>Trip PLanner</a>
        <a>Destination</a>
        <a>Travel Logs</a>
        <a href="logout.php" style="background-color: lightblue;">Log Out</a>
      </nav>
  </header>
  <div class="logo">
    <img src="images/logo.png" alt="">
  </div>
 
</body>
</html>
