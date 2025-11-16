<!--By Daya-->

<?php
session_start();
require_once("settings.php");

// For login - if already logged in, redirect to manage.php
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
    $email = trim($_POST['email'] ?? '');
    
    // Enhanced validation
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 4) {
        $error = "Username must be at least 4 characters long.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $error = "Username can only contain letters, numbers, and underscores.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $error = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $error = "Password must contain at least one number.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        $conn = mysqli_connect($host, $user, $pwd, $sql_db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Check if username already exists
        $stmt = mysqli_prepare($conn, "SELECT username FROM managers WHERE username=?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $error = "This username is already in use. Kindly try another combination.";
            mysqli_stmt_close($stmt); 
        } else {
            mysqli_stmt_close($stmt);

            // Register new manager with enhanced security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            if (!empty($email)) {
                $stmt = mysqli_prepare($conn, "INSERT INTO managers (username, password, email) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sss", $username, $hashed_password, $email);
            } else {
                $stmt = mysqli_prepare($conn, "INSERT INTO managers (username, password) VALUES (?, ?)");
                mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);
            }
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "You have successfully registered as a manager.";
            } else {
                $error = "Registration failed: " . mysqli_error($conn);
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
    <style>
        .password-requirements {
            font-size: 12px;
            color: #666;
            margin: 5px 0 15px 0;
            text-align: left;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 4px solid #3498db;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: #3498db;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-page">
        <?php if ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="manager_login.php"><button style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Go to Login Page</button></a>
            </div>
        <?php else: ?>
            <form method="post" action="">
                <h2>Welcome to the Manager Registration Portal</h2>

                <?php if ($error): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <input type="text" name="username" placeholder="Username (minimum 4 characters)" required minlength="4" maxlength="50">
                
                <input type="email" name="email" placeholder="Email (optional)" maxlength="100">
                
                <input type="password" name="password" placeholder="Password (minimum 8 characters)" required minlength="8">
                
                <div class="password-requirements">
                    <strong>Password must contain:</strong><br>
                    • At least 8 characters<br>
                    • At least one uppercase letter (A-Z)<br>
                    • At least one number (0-9)
                </div>
                
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                
                <button type="submit">Register</button>
            </form>
            
            <div class="login-link">
                <a href="manager_login.php">Already have an account? Login here</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>