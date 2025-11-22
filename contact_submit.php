<?php
include 'db.php';
session_start();



$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = htmlspecialchars(trim($_POST['name']));
  $email = htmlspecialchars(trim($_POST['email']));
  $message = htmlspecialchars(trim($_POST['message']));

  if (!empty($name) && !empty($email) && !empty($message)) {

    $sql = "INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // ✅ Check if prepare() failed
    if (!$stmt) {
      die("SQL prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
      echo "<script>alert('✅ Thank you for your feedback!'); window.location.href='Contact.php';</script>";
    } else {
      echo "<script>alert('❌ Failed to submit feedback: " . $stmt->error . "'); window.location.href='Contact.php';</script>";
    }

    $stmt->close();
  } else {
    echo "<script>alert('⚠️ Please fill all fields.'); window.location.href='Contact.php';</script>";
  }
}

$conn->close();
?>
