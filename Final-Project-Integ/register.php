<?php
session_start();
require_once 'config.php';

// Default active form
$activeForm = $_SESSION['active_form'] ?? 'login';

// Helper function
function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastName     = trim($_POST['lastName'] ?? '');
    $firstName    = trim($_POST['firstName'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $username     = trim($_POST['username'] ?? '');
    $password     = $_POST['password'] ?? '';
    $confPassword = $_POST['confPassword'] ?? '';

    // Basic validation
    if ($password !== $confPassword) {
        $errorMessage = 'Passwords do not match';
    } else {
        // Check if email or username already exists
        $checkUser = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $checkUser->bind_param("ss", $email, $username);
        $checkUser->execute();
        $result = $checkUser->get_result();

        if ($result->num_rows > 0) {
            $errorMessage = 'Email or Username is already registered!';
        } else {
            // Insert new user
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (lastName, firstName, email, username, password) VALUES (?, ?, ?, ?, ?)");

            if ($stmt) {
                $stmt->bind_param("sssss", $lastName, $firstName, $email, $username, $hashedPassword);
                if ($stmt->execute()) {
                    header("Location: login.php");
                    exit();
                } else {
                    $errorMessage = 'Registration failed: ' . $stmt->error;
                }
                $stmt->close();
            } else {
                $errorMessage = 'Prepare failed: ' . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="form-box <?php echo isActiveForm('register', $activeForm); ?>" id="register-form">
  <form action="register.php" method="post">
    <h2>Register</h2>
    <?php if (!empty($errorMessage)): ?>
      <p class="error-message" style="color:red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <input type="text" name="lastName" placeholder="Last Name" required>
    <input type="text" name="firstName" placeholder="First Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confPassword" placeholder="Confirm Password" required>

    <button type="submit" name="register">Register</button>
    <p>Already have an account? <a href="login.php">Login</a></p>
  </form>
</div>

  </div>
</body>
</html>
