<?php
session_start();

// ✅ Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$mode = $_GET['mode'] ?? 'users';
$userId = $_GET['user_id'] ?? null;

// Fetch users
$usersResponse = file_get_contents("https://dummyjson.com/users");
$usersData = json_decode($usersResponse, true);

// If mode=carts and user_id is clicked, fetch their cart + info
$cartData = null;
$user = null;
if ($mode === 'carts' && $userId) {
    $cartResponse = @file_get_contents("https://dummyjson.com/carts/user/" . $userId);
    if ($cartResponse !== false) {
        $cartData = json_decode($cartResponse, true);
    }

    $userResponse = @file_get_contents("https://dummyjson.com/users/" . $userId);
    if ($userResponse !== false) {
        $user = json_decode($userResponse, true);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users & Carts</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- 🔹 Navigation Bar -->
    <nav class="navbar">
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="users.php?mode=users">Users</a></li>
            <li><a href="users.php?mode=carts">Carts</a></li>
            <li><a href="posts.php">Posts</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <?php if ($mode === 'users'): ?>
        <h2>Users List</h2>
        <?php
        foreach ($usersData['users'] as $u) {
            echo "<div class='user-card'>";
            echo "<img src='" . $u['image'] . "' alt='Profile' width='100'><br>";
            echo "<h3>" . $u['firstName'] . " " . $u['lastName'] . "</h3>";
            echo "<p>Email: " . $u['email'] . "</p>";
            echo "<p>Age: " . $u['age'] . "</p>";
            echo "<p>Phone: " . $u['phone'] . "</p>";
            echo "<a href='users.php?mode=carts&user_id=" . $u['id'] . "' class='btn'>View Cart</a>";
            echo "</div><br>";
        }
        ?>
    <?php elseif ($mode === 'carts'): ?>
    <?php if ($userId && $user && $cartData && isset($cartData['carts'][0])): ?>
        <h2>Cart for <?php echo $user['firstName'] . " " . $user['lastName']; ?></h2>
        <?php
        $cart = $cartData['carts'][0];
        echo "Cart ID: " . $cart['id'] . "<br>";
        echo "Total Items: " . $cart['totalProducts'] . "<br>";
        echo "Total Amount: $" . $cart['total'] . "<br><br>";

        echo "<h3>Products:</h3>";
        foreach ($cart['products'] as $product) {
            $totalItem = $product['price'] * $product['quantity'];
            echo "- " . $product['title'] .
                 " | Qty: " . $product['quantity'] .
                 " | Price: $" . $product['price'] .
                 " | Total: $" . $totalItem . "<br>";
        }
        ?>
    <?php elseif ($userId): ?>
        <p>No cart data available or API limit reached.</p>
    <?php else: ?>
        <h2>All Carts</h2>
        <?php
        $allCartsResponse = @file_get_contents("https://dummyjson.com/carts");
        if ($allCartsResponse !== false) {
            $allCartsData = json_decode($allCartsResponse, true);
            foreach ($allCartsData['carts'] as $cart) {
                echo "<div class='cart-card'>";
                echo "<p><strong>Cart ID:</strong> " . $cart['id'] . "</p>";
                echo "<p><strong>User ID:</strong> " . $cart['userId'] . "</p>";
                echo "<p><strong>Total Items:</strong> " . $cart['totalProducts'] . "</p>";
                echo "<p><strong>Total Amount:</strong> $" . $cart['total'] . "</p>";
                echo "<h4>Products:</h4>";
                foreach ($cart['products'] as $product) {
                    echo "- " . $product['title'] .
                         " | Qty: " . $product['quantity'] .
                         " | Price: $" . $product['price'] . "<br>";
                }
                echo "</div><hr>";
            }
        } else {
            echo "<p>Unable to fetch carts at the moment.</p>";
        }
        ?>
    <?php endif; ?>
<?php endif; ?>
<script src="script.js"></script>
</body>
</html>
