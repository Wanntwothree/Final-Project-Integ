<?php
session_start();

// Prevent browser from caching this page
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Protect dashboard
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$firstName = $_SESSION['firstName'];
$lastName  = $_SESSION['lastName'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
</head>
<body>
  <nav class="navbar">
    <ul>
      <li><a href="">Home</a></li>
      <li><a href="">Products</a></li>
      <li><a href="">Posts</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>
  <h1>Welcome, <?php echo htmlspecialchars($firstName . " " . $lastName); ?>!</h1>
</body>
</html>
