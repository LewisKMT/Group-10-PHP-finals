<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}
?>

$host = "localhost";
$dbname = "accounts";
$username = "root";
$password = "";

$data = json_decode(file_get_contents("php://input"), true);

$city = $data['city'];
$country = $data['country'];
$activities = implode(", ", $data['activities']);
$info = implode(", ", $data['info']);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $stmt = $pdo->prepare("INSERT INTO trips (city, country, activities, info) VALUES (?, ?, ?, ?)");
    $stmt->execute([$city, $country, $activities, $info]);
    echo "Trip saved successfully!";
} catch (PDOException $e) {
    http_response_code(500);
    echo "Database error: " . $e->getMessage();
}
?>
