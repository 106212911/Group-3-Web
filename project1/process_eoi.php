<?php
    // Prevent direct access and ensures only access it through the form (POST method)
    if ($_SERVER["REQUEST_METHOD"] != "POST"){
        header("location: apply.php");
        exit();
    }

    // Database connection details
    require_once("settings.php"); 

    // First connect to MySQL server without selecting database
    $conn = @mysqli_connect($host, $user, $pwd);
    
    // If connection fails, show error message and stop execution
    if (!$conn){
        die("<p>Database connection failed: " . mysqli_connect_error() . "</p>");
    }

    // Create database if it doesn't exist
    $create_db = "CREATE DATABASE IF NOT EXISTS $sql_db";
    if (mysqli_query($conn, $create_db)) {
        // Select the database
        mysqli_select_db($conn, $sql_db);
        
        // Create EOI table if it doesn't exist
        $create_table = "CREATE TABLE IF NOT EXISTS eoi (
            EOInumber INT AUTO_INCREMENT PRIMARY KEY,
            JobReference VARCHAR(10) NOT NULL,
            FirstName VARCHAR(20) NOT NULL,
            LastName VARCHAR(20) NOT NULL,
            DOB DATE NOT NULL,
            Gender VARCHAR(10) NOT NULL,
            StreetAddress VARCHAR(40) NOT NULL,
            Suburb VARCHAR(40) NOT NULL,
            State VARCHAR(3) NOT NULL,
            Postcode VARCHAR(4) NOT NULL,
            Email VARCHAR(50) NOT NULL,
            Phone VARCHAR(12) NOT NULL,
            Skills TEXT,
            OtherSkills TEXT,
            Status VARCHAR(10) DEFAULT 'New',
            ApplyDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if (!mysqli_query($conn, $create_table)) {
            die("<p>Table creation failed: " . mysqli_error($conn) . "</p>");
        }

        // Create managers table if it doesn't exist (for Enhancements #2, #3, #4)
        $create_managers_table = "CREATE TABLE IF NOT EXISTS managers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            login_attempts INT DEFAULT 0,
            locked_until TIMESTAMP NULL
        )";
        
        if (!mysqli_query($conn, $create_managers_table)) {
            die("<p>Managers table creation failed: " . mysqli_error($conn) . "</p>");
        }
    } else {
        die("<p>Database creation failed: " . mysqli_error($conn) . "</p>");
    }

    // Sanitises and escapes user input to prevent HTML and SQL injection attacks
    function sanitise_input($conn, $data){
        $data = trim($data);                // removes extra spaces
        $data = stripslashes($data);        // remove slashes
        $data = htmlspecialchars($data);    // converts HTML to safe text
        $data = mysqli_real_escape_string($conn, $data);   // escape input for SQL
        return $data;
    }

    // Collect and clean form data 
    $jobReference = sanitise_input($conn, $_POST["jobref"]);
    $firstName = sanitise_input($conn, $_POST["fname"]);
    $lastName = sanitise_input($conn, $_POST["lname"]);
    $dob = sanitise_input($conn, $_POST["dob"]);
    $gender = sanitise_input($conn, $_POST["gender"]);
    $streetAddress = sanitise_input($conn, $_POST["street"]);
    $suburb = sanitise_input($conn, $_POST["suburb"]);
    $state =  sanitise_input($conn, $_POST["state"]);
    $postcode = sanitise_input($conn, $_POST["postcode"]);
    $emailAddress = sanitise_input($conn, $_POST["email"]);
    $phoneNumber = sanitise_input($conn, $_POST["phone"]);
    
    // Collect skills checkboxes into one string for database storage
    $skills = isset($_POST["skills"]) ? $_POST["skills"] : [];
    
    // FIX: Ensure $skills is always an array
    if (!is_array($skills)) {
        $skills = [$skills];
    }
   
    // "Python, Excel, MySQL"
    $skillsList = implode(", ", $skills); 
    $skillsList = sanitise_input($conn, $skillsList);

    // Extra free‑text field for skills not in the checkbox list
    $otherSkills = sanitise_input($conn, $_POST["otherskills"]);

    // Default status for new EOIs (assignment requirement)
    $status = "New";

    // --- Validation section to ensure data integrity --- 
    // Collects all error message
    $error_message = "";

    if ($jobReference == ""){
        $error_message .= "<p>Job reference is required.</p>";
    }

    if ($firstName == "" || !preg_match("/^[a-zA-Z\s]{1,20}$/", $firstName)){      // only letters, 20 max
        $error_message .= "<p>First name must contain only letters (max 20 characters).</p>";
    }

    if ($lastName == "" || !preg_match("/^[a-zA-Z\s]{1,20}$/", $lastName)){        // only letters, 20 max
        $error_message .= "<p>Last name must contain only letters (max 20 characters).</p>";
    }

    if ($dob == ""){
        $error_message .= "<p>Date of birth is required.</p>";
    }

    if ($gender == ""){
        $error_message .= "<p>Please select your gender.</p>";
    }

    if ($streetAddress == "" || strlen($streetAddress) > 40){           // max 40 characters
        $error_message .= "<p>Street address is required (max 40 characters).</p>";
    }

    if ($suburb == "" || strlen($suburb) > 40){             // max 40 characters
        $error_message .= "<p>Suburb/Town is required (max 40 characters).</p>";
    }

    $validStates = ["VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT"];
    if (!in_array($state, $validStates)){               // state dropdown
        $error_message .= "<p>Invalid state selection.</p>";
    } 

    if (!preg_match("/^[0-9]{4}$/", $postcode)){        // postcode format
        $error_message .= "<p>Postcode must be exactly 4 digits.</p>";
    }

    // Run this if postcode and state is valid 
    if (in_array($state, $validStates) && preg_match("/^[0-9]{4}$/", $postcode)){

       $statePostcodeRange = [
        "VIC" => ["3", "8"],
        "NSW" => ["1", "2"],
        "QLD" => ["4", "9"],
        "NT"  => ["0"],
        "WA"  => ["6"],
        "SA"  => ["5"],
        "TAS" => ["7"],
        "ACT" => ["0"]
        ];

        // Extracts the first digit of the postcode
        $firstDigit = substr($postcode, 0, 1);

        // Check if the first digit matches the selected state
        if (!in_array($firstDigit, $statePostcodeRange[$state])) {
        $error_message .= "<p>Postcode does not match the selected state.</p>";
        }
    }

    if ($emailAddress == "" || !filter_var($emailAddress, FILTER_VALIDATE_EMAIL)){      // email format check
        $error_message .= "<p>Invalid email format.</p>";
    }

    if ($phoneNumber == "" || !preg_match("/^[0-9 ]{8,12}$/", $phoneNumber)){       //phone format 
        $error_message .= "<p>Phone number must be 8-12 digits or spaces.</p>";
    }

    // Skills required
    if (empty($skills))
        $error_message .= "<p>Please select at least one technical skill.</p>";

    if ($error_message != ""){              // If there's errors, displays following sentence
        echo "<h3 style='color:red;'>Please fix the following errors:</h3>";
        echo "<div style='color:red;'>$error_message</div>";
        echo "<p><a href='apply.php'>Go back to the form</a></p>";
        mysqli_close($conn);
        exit();
    }   

    // Insert to Database   
    $stmt = mysqli_prepare($conn, "
        INSERT INTO eoi
        (JobReference, FirstName, LastName, DOB, Gender, StreetAddress, Suburb, State, Postcode, Email, Phone, Skills, OtherSkills, Status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    // Bind parameters to placeholders (s = string)
    mysqli_stmt_bind_param($stmt, "ssssssssssssss",
        $jobReference, $firstName, $lastName, $dob, $gender, $streetAddress, $suburb, $state, $postcode,
        $emailAddress, $phoneNumber, $skillsList, $otherSkills, $status
    );

    // Execute the SQL Query
    $result = mysqli_stmt_execute($stmt);   

    if (!$result){
        echo "<p>Query error: " . mysqli_error($conn) . "</p>";
    } else {
        // Get the auto-generated EOInumber
        $eoiNumber = mysqli_insert_id($conn);
        // Show confirmation message 
        echo "<h3>Application submitted successfully!</h3>";
        echo "<p>Your unique EOI number is: <strong>$eoiNumber</strong></p>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
?>