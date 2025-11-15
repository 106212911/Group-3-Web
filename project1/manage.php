<?php 
session_start(); 

require_once("settings.php"); 

$conn = mysqli_connect($host, $user, $pwd, $sql_db); 

if (!$conn) { 
    die("Database connection failed: " . mysqli_connect_error()); 
} 


if (isset($_GET['logout']) && $_GET['logout'] === 'true') { 
    session_unset(); 
    session_destroy(); 
    header('Location: manager_login.php'); 
    exit(); 
} 


if (!isset($_SESSION['manager_logged_in']) || $_SESSION['manager_logged_in'] !== true) { 
    header('Location: manager_login.php'); 
    exit(); 
} 

$firstName = trim($_GET['first_name'] ?? ''); 
$lastName = trim($_GET['last_name'] ?? ''); 
$jobRef = trim($_GET['job_reference'] ?? ''); 

$sortingField = $_GET['sorting_field'] ?? 'EOInumber'; 
$allowedSort = ['EOInumber','JobReference','FirstName','LastName','Status']; 
$sortField = $_GET['sorting_field'] ?? 'EOInumber'; 

if (!in_array($sortField, $allowedSort)) { 
    $sortField = 'EOInumber'; 
} 

$sql = "SELECT * FROM eoi"; 
$whereAdded = false; 


if (!empty($firstName)) { 
    $sql .= $whereAdded ? " AND " : " WHERE "; 
    $sql .= "FirstName LIKE '%" . mysqli_real_escape_string($conn, $firstName) . "%'"; 
    $whereAdded = true; 
} 

if (!empty($lastName)) { 
    $sql .= $whereAdded ? " AND " : " WHERE "; 
    $sql .= "LastName LIKE '%" . mysqli_real_escape_string($conn, $lastName) . "%'"; 
    $whereAdded = true; 
} 

if (!empty($jobRef)) { 
    $sql .= $whereAdded ? " AND " : " WHERE "; 
    $sql .= "JobReference LIKE '%" . mysqli_real_escape_string($conn, $jobRef) . "%'"; 
} 


$sql .= " ORDER BY $sortField ASC"; 

 
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
        echo "<tr> 
            <td>{$row['EOInumber']}</td> 
            <td>{$row['JobReference']}</td> 
            <td>{$row['FirstName']}</td> 
            <td>{$row['LastName']}</td> 
            <td>{$row['DOB']}</td> 
            <td>{$row['Gender']}</td> 
            <td>{$row['StreetAddress']}</td> 
            <td>{$row['Suburb']}</td> 
            <td>{$row['State']}</td> 
            <td>{$row['Postcode']}</td> 
            <td>{$row['Email']}</td> 
            <td>{$row['Phone']}</td> 
            <td>{$row['Skill1']}</td> 
            <td>{$row['Skill2']}</td> 
            <td>{$row['Skill3']}</td> 
            <td>{$row['OtherSkills']}</td> 
            <td>{$row['Status']}</td> 
        </tr>"; 
    } 

    echo "</table>"; 
} else { 
    echo "<p>No EOI's found.</p>"; 
} 
?> 
 

<p class="logout-link"> 
    <a href="?logout=true" class="logout-button">Logout</a> 
</p>

<?php 

?> 

<form method="get" action=""> 
    Your First Name: <input type="text" name="first_name" placeholder="First Name"> 
    Your Last Name: <input type="text" name="last_name" placeholder="Last Name"> 
    Your Job Reference: <input type="text" name="job_reference" placeholder="DJJJ-891010, DJJ891010"> 
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