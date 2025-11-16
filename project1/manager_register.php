<!--By Daya-->

<?php
session_start();
require_once("settings.php");

// For login
if (isset($_SESSION['manager_logged_in']) && $_SESSION['manager_logged_in'] === true) {
    header('Location: manage.php');
    exit();
}

// For the Logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    session_unset();
    session_destroy();
    header('Location: manager_register.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    
    // For validation
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 4) {
        $error = "Username must be at least 4 characters long.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $conn = mysqli_connect($host, $user, $pwd, $sql_db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Performs a check basically 
        $stmt = mysqli_prepare($conn, "SELECT username FROM managers WHERE username=?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $error = "This username is already in use. Kindly try another combination.";
            mysqli_stmt_close($stmt); 
        } else {
            mysqli_stmt_close($stmt);

            // For registering new manager
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO managers (username, password) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "You have successfully registered as a manager.";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conn); 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration For Manager</title>
    <link rel="stylesheet" href="styles/manager_login_page_styles.css">
</head>
<body>
    <div class="login-page">
        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
            <a href="manager_login.php"><button>Login Page</button></a>
        <?php else: ?>
            <form method="post" action="">
                <h2>Welcome to the Manager Registration Portal.</h2>

                <?php if ($error): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <input type="text" name="username" placeholder="Username (minimum 4 characters required)" required>
                <input type="password" name="password" placeholder="Password (minimum 8 characters required)" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required> 
                <button type="submit">Register</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>