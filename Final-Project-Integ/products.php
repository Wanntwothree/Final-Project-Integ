<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
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

<?php
// Fetch products from DummyJSON
$url = "https://dummyjson.com/products";
$response = file_get_contents($url);
$data = json_decode($response, true);

// Display product list
foreach ($data['products'] as $product) {
    echo "<div class='product-card'>";
    echo "<img src='" . $product['thumbnail'] . "' alt='Product image' width='150'><br>";
    echo "<h3>" . $product['title'] . "</h3>";
    echo "<p>Category: " . $product['category'] . "</p>";
    echo "<p>Price: $" . $product['price'] . "</p>";
    echo "<p>Stock: " . $product['stock'] . "</p>";   // ✅ here’s the stock
    echo "</div><br>";
}
?>
<script src="script.js"></script>
</body>
</html>