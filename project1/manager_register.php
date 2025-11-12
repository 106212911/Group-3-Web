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

    $conn = mysqli_connect($host, $user, $pwd, $sql_db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

// Performs a check basically
    $sql_check = "SELECT * FROM managers WHERE username='$username'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        $error = "This username is already in use. Kindly try another combination.";
    } else {


        // For registering new managers
        $sql_insert = "INSERT INTO managers (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($conn, $sql_insert)) {
            $success = "You have successfully registered as a manager.";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
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

                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Register</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>