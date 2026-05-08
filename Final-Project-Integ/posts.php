<?php
session_start();

// ✅ Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$skip = ($page - 1) * $limit;

// Fetch posts with pagination
$postsResponse = @file_get_contents("https://dummyjson.com/posts?limit=$limit&skip=$skip");
$postsData = json_decode($postsResponse, true);

// 🔹 Option 1: Loop back if no posts
if (!$postsData || empty($postsData['posts'])) {
    $page = 1; // reset to first page
    $skip = 0;
    $postsResponse = @file_get_contents("https://dummyjson.com/posts?limit=$limit&skip=$skip");
    $postsData = json_decode($postsResponse, true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Posts</title>
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

    <h2>Posts</h2>
    <?php
    if ($postsData && isset($postsData['posts'])) {
        foreach ($postsData['posts'] as $post) {
            echo "<div class='post-card'>";
            echo "<h3>" . htmlspecialchars($post['title']) . "</h3>";

            // Body preview (safe length)
            $preview = strlen($post['body']) > 120 ? substr($post['body'], 0, 120) . "..." : $post['body'];
            echo "<p>" . htmlspecialchars($preview) . "</p>";

            // Tags
            echo "<p><strong>Tags:</strong> ";
            foreach ($post['tags'] as $tag) {
                echo "<span class='tag'>" . htmlspecialchars($tag) . "</span> ";
            }
            echo "</p>";

            // Reactions fix — handle both number and array
            echo "<p><strong>Reactions:</strong> ";
            if (is_array($post['reactions'])) {
                echo implode(", ", $post['reactions']);
            } else {
                echo htmlspecialchars($post['reactions']);
            }
            echo "</p>";

            echo "</div><hr>";
        }

        // Pagination controls (infinite loop style)
        echo "<div class='pagination'>";
        echo "<a href='posts.php?page=" . ($page - 1) . "' class='btn'>Previous</a> ";
        echo "<a href='posts.php?page=" . ($page + 1) . "' class='btn'>Next</a>";
        echo "</div>";
    } else {
        echo "<p>No posts available or API limit reached.</p>";
    }
    ?>
</body>
</html>
