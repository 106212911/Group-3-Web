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

        // Function to auto-unlock expired accounts
        $unlock_query = "UPDATE managers SET failed_attempts = 0, locked_until = NULL WHERE locked_until IS NOT NULL AND locked_until < NOW()";

        mysqli_query($conn, $unlock_query);

        // Get user data including login attempts and lock status
        $stmt = mysqli_prepare($conn, "SELECT id, password, failed_attempts, locked_until FROM managers WHERE username=?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            
            // Check if account is locked
            if ($row['locked_until'] && strtotime($row['locked_until']) > time()) {
                $remaining_time = strtotime($row['locked_until']) - time();
                $minutes = floor($remaining_time / 60);
                $seconds = $remaining_time % 60;
                $error = "Account locked. Try again in $minutes minutes and $seconds seconds.";
            } else {
                // Verify password (REMOVED PLAINTEXT FALLBACK - SECURITY RISK)
                if (password_verify($password, $row['password'])) {
                    // Successful login - reset attempts and update session
                    $_SESSION['manager_logged_in'] = true;
                    $_SESSION['manager_username'] = $username;
                    $_SESSION['manager_id'] = $row['id'];
                    
                    // Reset login attempts
                    $reset_stmt = mysqli_prepare($conn, "UPDATE managers SET failed_attempts = 0, locked_until = NULL WHERE id = ?");
                    mysqli_stmt_bind_param($reset_stmt, "i", $row['id']);
                    mysqli_stmt_execute($reset_stmt);
                    mysqli_stmt_close($reset_stmt);
                    
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    header('Location: manage.php');
                    exit();
                } else {
                    // Failed login - increment attempts
                    $new_attempts = $row['failed_attempts'] + 1;
                    
                    if ($new_attempts >= 3) {
                        // Lock account for 30 minutes
                        $lock_time = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                        $update_stmt = mysqli_prepare($conn, "UPDATE managers SET failed_attempts = ?, locked_until = ? WHERE id = ?");
                        mysqli_stmt_bind_param($update_stmt, "isi", $new_attempts, $lock_time, $row['id']);
                        $error = "Too many failed attempts. Account locked for 30 minutes.";
                    } else {
                        // Just increment attempts
                        $update_stmt = mysqli_prepare($conn, "UPDATE managers SET failed_attempts = ? WHERE id = ?");
                        mysqli_stmt_bind_param($update_stmt, "ii", $new_attempts, $row['id']);
                        $remaining_attempts = 3 - $new_attempts;
                        $error = "Invalid username or password. Attempts: $new_attempts/3 ($remaining_attempts remaining)";
                    }
                    
                    mysqli_stmt_execute($update_stmt);
                    mysqli_stmt_close($update_stmt);
                }
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
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="manager_register.php" style="color: #3498db; text-decoration: none;">Need an account? Register here</a>
        </div>
    </div>
</body>
</html>