<?php
session_start();
require_once 'config.php';

// Default active form
$activeForm = $_SESSION['active_form'] ?? 'login';

// Helper function
function isActiveForm($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['login_error'] = "";
            $_SESSION['active_form'] = "login";

            // Store user details in session
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['firstName']  = $user['firstName'];
            $_SESSION['lastName']   = $user['lastName'];
            $_SESSION['email']      = $user['email'];

            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Incorrect email or password";
            $_SESSION['active_form'] = "login";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Incorrect email or password";
        $_SESSION['active_form'] = "login";
        header("Location: index.php");
        exit();
    }
}

// helper for showing error
function showError($error){
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

$errors = [
    'login' => $_SESSION['login_error'] ?? ''
];
session_unset();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="form-box <?php echo isActiveForm('login', $activeForm); ?>" id="login-form">
  <form action="login.php" method="post">
    <h2>Login</h2>
    <?php echo showError($errors['login']); ?>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
    <p>Don't have an account? <a href="register.php">Register</a></p>
  </form>
</div>

  </div>
</body>
</html>
