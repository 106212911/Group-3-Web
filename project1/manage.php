<?php 
session_start(); 


require_once("settings.php"); 
$conn = mysqli_connect($host, $user, $pwd, $sql_db); 

if (!$conn) { 
    die("Database connection failed: " . mysqli_connect_error()); 
} 

// Logging oUT 
if (isset($_GET['logout']) && $_GET['logout'] === 'true') { 
    session_unset(); 
    session_destroy(); 
    header('Location: manager_login.php'); 
    exit(); 
} 

?>

<link rel="stylesheet" href="styles/manager_login_page_styles.css">
<?php

// Logging in
if (!isset($_SESSION['manager_logged_in']) || $_SESSION['manager_logged_in'] !== true) { 
    header('Location: manager_login.php'); 
    exit(); 
} 


//  Delete job section
if (isset($_POST['delete_jobref']) && !empty($_POST['delete_jobref'])) {
    $jobreference = $_POST['delete_jobref'];
    $stmt = $conn->prepare("DELETE FROM eoi WHERE JobReference=?");
    $stmt->bind_param("s", $jobreference);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "Deleted EOIs for $jobreference<br>";
    } else {
        echo "No EOIs found for that job reference<br>";
    }
    $stmt->close();
}


//  Update status section
if (isset($_POST['update_status_eoi']) && isset($_POST['new_status'])) {
    $eoinum = intval($_POST['update_status_eoi']);
    $status = $_POST['new_status'];
    $stmt = $conn->prepare("UPDATE eoi SET Status=? WHERE EOInumber=?");
    $stmt->bind_param("si", $status, $eoinum);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo "Updated EOI #$eoinum to $status<br>";
    } else {
        echo "EOI not found. Try again<br>";
    }
    $stmt->close();
}


$firstName = $_GET['first_name'] ?? '';
$lastName = $_GET['last_name'] ?? '';
$jobreference = $_GET['job_reference'] ?? '';  

$allowedSort = ['EOInumber','JobReference','FirstName','LastName','Status'];
$sortField = in_array($_GET['sorting_field'] ?? '', $allowedSort) ? $_GET['sorting_field'] : 'EOInumber';
 


 
$sql = "SELECT * FROM eoi WHERE 1=1"; 

#if for all the stated variables basically 
if ($firstName !== '') {
    $sql .= " AND FirstName LIKE '%" . mysqli_real_escape_string($conn, $firstName) . "%'";
}
if ($lastName !== '') {
    $sql .= " AND LastName LIKE '%" . mysqli_real_escape_string($conn, $lastName) . "%'";
}
if ($jobreference !== '') {
    $sql .= " AND JobReference LIKE '%" . mysqli_real_escape_string($conn, $jobreference) . "%'";
} 

#For Sorting basically 
$sql .= " ORDER BY $sortField ASC"; 

#To run basically 
$result = mysqli_query($conn, $sql);
 

if (!$result) { 
    die("Query failed. Sorry: " . mysqli_error($conn)); 
} 

 

if (mysqli_num_rows($result) > 0) { 
    echo "<table border='1' cellpadding='5' cellspacing='0'>"; 
    echo "<tr> 
        <th>EOI Number</th> 
        <th>Job Reference</th> 
        <th>First Name</th> 
        <th>Last Name</th> 
        <th>DOB</th> 
        <th>Gender</th> 
        <th>Street Address</th> 
        <th>Suburb</th> 
        <th>State</th> 
        <th>Postcode</th> 
        <th>Email</th> 
        <th>Phone</th> 
        <th>Skill 1</th> 
        <th>Skill 2</th> 
        <th>Skill 3</th> 
        <th>Other Skills</th> 
        <th>Status</th> 
    </tr>"; 

while ($row = mysqli_fetch_assoc($result)) { 

    $skills = explode(", ", $row['Skills']); 

    $skill1 = $skills[0] ?? '';
    $skill2 = $skills[1] ?? '';
    $skill3 = $skills[2] ?? '';

    echo "<tr> 
    <td>" . htmlspecialchars($row['EOInumber']) . "</td> 
    <td>" . htmlspecialchars($row['JobReference']) . "</td> 
    <td>" . htmlspecialchars($row['FirstName']) . "</td> 
    <td>" . htmlspecialchars($row['LastName']) . "</td> 
    <td>" . htmlspecialchars($row['DOB']) . "</td> 
    <td>" . htmlspecialchars($row['Gender']) . "</td> 
    <td>" . htmlspecialchars($row['StreetAddress']) . "</td> 
    <td>" . htmlspecialchars($row['Suburb']) . "</td> 
    <td>" . htmlspecialchars($row['State']) . "</td> 
    <td>" . htmlspecialchars($row['Postcode']) . "</td> 
    <td>" . htmlspecialchars($row['Email']) . "</td> 
    <td>" . htmlspecialchars($row['Phone']) . "</td> 
    <td>" . htmlspecialchars($skill1) . "</td> 
    <td>" . htmlspecialchars($skill2) . "</td> 
    <td>" . htmlspecialchars($skill3) . "</td> 
    <td>" . htmlspecialchars($row['OtherSkills']) . "</td> 
    <td>" . htmlspecialchars($row['Status']) . "</td> 
</tr>";
}

    echo "</table>"; 
} else { 
    echo "<p>No EOI's found.</p>"; 
} 

mysqli_close($conn);
?> 
 

<p class="logout-link"> 
    <a href="?logout=true" class="logout-button">Logout</a> 
</p>

 
<form method="get" action=""> 
    Your First Name: <input type="text" name="first_name"> 
    Your Last Name: <input type="text" name="last_name"> 
    Your Job Reference: <input type="text" name="job_reference"> 
    Sorting 
    <select name="sorting_field"> 
        <option value="EOInumber">EOI Number</option> 
        <option value="JobReference">Job Reference</option> 
        <option value="FirstName">First Name</option> 
        <option value="LastName">Last Name</option> 
        <option value="Status">Status</option> 
    </select> 
    <input type="submit" name="action" value="Search"> 
    <input type="submit" name="action" value="Sort"> 
</form>

<form method="post" action="">
    Delete by Job Reference:
    <input type="text" name="delete_jobref" required>
    <input type="submit" value="Delete">
</form>


<form method="post" action="">
    Update EOI's Status:
    Enter EOI Number: <input type="number" name="update_status_eoi" required>
    New Status:
    <select name="new_status">
        <option value="New">New</option>
        <option value="Current">Current</option>
        <option value="Final">Final</option>
    </select>
    <input type="submit" value="Update Status">
</form>