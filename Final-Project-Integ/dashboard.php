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
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <nav class="navbar">
    <ul>
      <li><a href="dashboard.php">Home</a></li>
      <li><a href="products.php">Products</a></li>
      <li><a href="users.php">Users</a></li>
      <li><a href="posts.php">Posts</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>
  <h1 class="welcome-message">Welcome, <?php echo htmlspecialchars($firstName . " " . $lastName); ?>!</h1>
    <button><a href="products.php">View Products</a></button>
    <button><a href="users.php">View Users</a></button>
    <button><a href="carts.php">View Carts</a></button>
    <button><a href="posts.php">View Posts</a></button>
  <script src="script.js"></script>
</body>
</html>
