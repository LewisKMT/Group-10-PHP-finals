<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    :root {
      --primary: #006699;
      --accent: #993300;
      --text-dark: #000000;
      --text-light: #FFFFFF;
      --bg-main: #FFCC66;
      --bg-card: rgba(255, 255, 255, 0.85);
      --border-dark: #333333;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, var(--primary), var(--bg-main));
      display: flex; align-items: center; justify-content: center;
      padding: 20px; min-height: 100vh; color: var(--text-dark);
      flex-direction: column;
    }

    .card {
      background-color: var(--bg-card);
      backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
      border-radius: 16px;
      padding: 30px 25px; width: 100%; max-width: 600px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
      animation: fadeIn 0.5s ease-in-out;
      margin-bottom: 30px;
    }

    h2, h3 {
      text-align: center;
      margin-bottom: 20px;
    }

    h3 { color: var(--text-light); }

    label {
      font-weight: bold;
      margin-top: 15px;
      display: block;
      color: var(--border-dark);
    }

    input[type="text"] {
      width: 100%; padding: 12px; margin-top: 6px;
      border: 1px solid var(--border-dark); border-radius: 6px;
      font-size: 1em; transition: border-color 0.3s;
    }

    input[type="text"]:focus {
      border-color: var(--primary); outline: none;
    }

    .error {
      font-size: 0.9em; color: var(--accent); margin-top: 4px; height: 16px;
    }

    input[type="submit"],
    .button-link {
      background-color: var(--primary); color: var(--text-light);
      border: none; padding: 14px; font-size: 1em; border-radius: 6px;
      cursor: pointer; margin-top: 20px; width: 100%;
      transition: background 0.3s; text-decoration: none; display: inline-block;
      text-align: center;
    }

    input[type="submit"]:hover,
    .button-link:hover {
      background-color: var(--accent);
    }

    .success-card {
      background: var(--primary);
      padding: 40px 30px; border-radius: 14px; color: var(--text-light);
      margin-top: 20px; animation: fadeIn 0.4s ease-in-out;
      font-size: 1.2em; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    .success-card p {
      font-family: monospace; font-size: 1.1em; margin: 6px 0;
    }

    .table-card {
      width: 100%; max-width: 800px;
      background-color: var(--bg-card);
      padding: 20px; border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
      overflow-x: auto;
      animation: fadeIn 0.3s ease-in-out;
    }

    table {
      width: 100%; border-collapse: collapse;
    }

    th, td {
      border: 1px solid var(--border-dark);
      padding: 10px; text-align: left;
    }

    th {
      background-color: var(--primary);
      color: var(--text-light);
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 480px) {
      input[type="text"], input[type="submit"], .button-link {
        font-size: 0.95em;
      }

      .success-card, .table-card {
        padding: 20px;
        font-size: 1em;
      }

      .success-card h3 {
        font-size: 1.5em;
      }
    }
  </style>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const form = document.forms["regForm"];
      const fields = ["id", "sname", "mname", "fname"];
      fields.forEach(field => {
        const input = form[field];
        const errorSpan = document.getElementById(field + "Err");
        input.addEventListener("input", function () {
          const value = input.value.trim();
          if (!value) {
            errorSpan.textContent = "* This field is required";
            return;
          }
          if (field === "id" && !/^[\\d-]+$/.test(value)) {
            errorSpan.textContent = "* Only numbers and dashes allowed";
          } else if (["sname", "mname", "fname"].includes(field) && !/^[a-zA-Z-' ]+$/.test(value)) {
            errorSpan.textContent = "* Only letters and spaces allowed";
          } else {
            errorSpan.textContent = "";
          }
        });
      });
    });
  </script>
</head>
<body>
<?php
$idErr = $snameErr = $mnameErr = $fnameErr = "";
$id = $sname = $mname = $fname = "";
$success = false;
$showStudents = isset($_GET['view']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
  }

  $id = test_input($_POST["id"]);
  $sname = test_input($_POST["sname"]);
  $mname = test_input($_POST["mname"]);
  $fname = test_input($_POST["fname"]);

  if (!preg_match("/^[\\d-]+$/", $id)) $idErr = "* Only numbers and dashes allowed";
  if (!preg_match("/^[a-zA-Z-' ]*$/", $sname)) $snameErr = "* Invalid surname";
  if (!preg_match("/^[a-zA-Z-' ]*$/", $mname)) $mnameErr = "* Invalid middle name";
  if (!preg_match("/^[a-zA-Z-' ]*$/", $fname)) $fnameErr = "* Invalid first name";

  if (empty($idErr) && empty($snameErr) && empty($mnameErr) && empty($fnameErr)) {
    $conn = new mysqli("localhost", "root", "", "accounts");
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
    $stmt = $conn->prepare("INSERT INTO students (id, sname, mname, fname) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $id, $sname, $mname, $fname);
    if ($stmt->execute()) $success = true;
    $stmt->close(); $conn->close();
  }
}
?>

<div class="card">
<?php if (!$success): ?>
  <h2>Student Registration</h2>
  <form name="regForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <label>ID:</label>
    <input type="text" name="id" value="<?php echo $id; ?>">
    <div class="error" id="idErr"><?php echo $idErr; ?></div>

    <label>Surname:</label>
    <input type="text" name="sname" value="<?php echo $sname; ?>">
    <div class="error" id="snameErr"><?php echo $snameErr; ?></div>

    <label>Middle Name:</label>
    <input type="text" name="mname" value="<?php echo $mname; ?>">
    <div class="error" id="mnameErr"><?php echo $mnameErr; ?></div>

    <label>First Name:</label>
    <input type="text" name="fname" value="<?php echo $fname; ?>">
    <div class="error" id="fnameErr"><?php echo $fnameErr; ?></div>

    <input type="submit" name="submit" value="Submit">
  </form>
<?php else: ?>
  <div class="success-card">
    <h3>Registration Successful!</h3>
    <p><strong>ID:</strong> <?php echo $id; ?></p>
    <p><strong>Surname:</strong> <?php echo $sname; ?></p>
    <p><strong>Middle Name:</strong> <?php echo $mname; ?></p>
    <p><strong>First Name:</strong> <?php echo $fname; ?></p>
    <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="button-link">Register Another Student</a>
  </div>
<?php endif; ?>
  <a href="?view=true" class="button-link">View All Registered Students</a>
</div>

<?php if ($showStudents): ?>
<div class="table-card">
  <h2>Registered Students</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>First Name</th>
      <th>Middle Name</th>
      <th>Last Name</th>
    </tr>
    <?php
    $conn = new mysqli("localhost", "root", "", "accounts");
    if ($conn->connect_error) {
      echo "<tr><td colspan='4'>Connection failed</td></tr>";
    } else {
      $result = $conn->query("SELECT id, fname, mname, sname FROM students ORDER BY id ASC");
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>" . htmlspecialchars($row["id"]) . "</td>
                  <td>" . htmlspecialchars($row["fname"]) . "</td>
                  <td>" . htmlspecialchars($row["mname"]) . "</td>
                  <td>" . htmlspecialchars($row["sname"]) . "</td>
                </tr>";
        }
      } else {
        echo "<tr><td colspan='4'>No students found.</td></tr>";
      }
      $conn->close();
    }
    ?>
  </table>
</div>
<?php endif; ?>
</body>
</html>
