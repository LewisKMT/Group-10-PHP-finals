<!DOCTYPE HTML>  
<html>
<head>
  <meta charset="UTF-8">
  <title>PHP Form Validation</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 40px;
      display: flex;
      justify-content: center;
    }

    .container {
      background: #ffffff;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 500px;
    }

    h2 {
      text-align: center;
      color: #333;
    }

    .error {
      color: #e74c3c;
      font-size: 0.9em;
    }

    input[type="text"],
    textarea {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      margin-bottom: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
      font-size: 1em;
    }

    textarea {
      resize: vertical;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 10px;
    }

    .radio-group {
      display: flex;
      gap: 15px;
      margin-top: 8px;
    }

    input[type="submit"] {
      background-color: #3498db;
      color: white;
      border: none;
      padding: 10px 15px;
      font-size: 1em;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
      width: 100%;
    }

    input[type="submit"]:hover {
      background-color: #2980b9;
    }

    .output {
      background-color: #ecf0f1;
      padding: 15px;
      margin-top: 20px;
      border-radius: 5px;
      font-family: monospace;
    }
  </style>
</head>
<body>  

<div class="container">
<?php
// define variables and set to empty values
$idErr = $snameErr = $mnameErr = $fnameErr = "";
$id = $sname = $mname = $fname = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["id"])) {
    $idErr = "ID is required";
  } else {
    $id = test_input($_POST["id"]);
    if (preg_match("/^[a-zA-Z-' ]*$/", $id)) {
      $idErr = "* Only numbers allowed";
    }
  }

  if (empty($_POST["sname"])) {
    $snameErr = "* Surname is required";
  } else {
    $sname = test_input($_POST["sname"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/", $sname)) {
      $snameErr = "* Only letters and white space allowed";
    }
  }

  if (empty($_POST["mname"])) {
    $mnameErr = "* Middle Name is required";
  } else {
    $mname = test_input($_POST["mname"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/", $mname)) {
      $mnameErr = "Only letters and white space allowed";
    }
  }

  if (empty($_POST["fname"])) {
    $fnameErr = "* First Name is required";
  } else {
    $fname = test_input($_POST["fname"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
      $fnameErr = "Only letters and white space allowed";
    }
  }

  // If there are no validation errors, insert into database
  if (empty($idErr) && empty($snameErr) && empty($mnameErr) && empty($fnameErr)) {
    // Connect to database
    $conn = new mysqli("localhost", "root", "", "form");

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO students (id, sname, mname, fname) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $id, $sname, $mname, $fname);

    if ($stmt->execute()) {
      echo "New record inserted successfully";
    } else {
      echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
  }
}

function test_input($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}
?>

<h2>PHP Form Validation</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  <label>ID:</label>
  <input type="text" name="id" value="<?php echo $id;?>">
  <span class="error"><?php echo $idErr;?></span>

  <label>Surname:</label>
  <input type="text" name="sname" value="<?php echo $sname;?>">
  <span class="error"><?php echo $snameErr;?></span>

  <label>Middle Name:</label>
  <input type="text" name="mname" value="<?php echo $mname;?>">
  <span class="error"><?php echo $mnameErr;?></span>

  <label>First Name:</label>
  <input type="text" name="fname" value="<?php echo $fname;?>">
  <span class="error"><?php echo $fnameErr;?></span>

  <input type="submit" name="submit" value="Submit">  
</form>
</div>

</body>
</html>
