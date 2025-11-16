<?php
session_start();
require_once("settings.php");

if (isset($_SESSION['manager_logged_in']) && $_SESSION['manager_logged_in'] === true) {
    header('Location: manage.php');
    exit();
}

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset();
    session_destroy();
    header('Location: manager_login.php');
    exit();
}

// for error message
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // establish connection
        $conn = mysqli_connect($host, $user, $pwd, $sql_db);
        if (!$conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        $stmt = mysqli_prepare($conn, "SELECT password FROM managers WHERE username=?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            
            if (password_verify($password, $row['password']) || $password === $row['password']) {
                $_SESSION['manager_logged_in'] = true;
                $_SESSION['manager_username'] = $username;
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header('Location: manage.php');
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Login Page</title>
    <link rel="stylesheet" href="styles/manager_login_page_styles.css">
</head>
<body>
    <div class="login-page">
        <form method="post" action="">
            <h2>Welcome back, Kindly Login</h2>

            <?php if ($error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>