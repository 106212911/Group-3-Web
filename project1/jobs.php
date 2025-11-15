<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solid Tech Inc. - Career Opportunities</title>
    <link rel="Stylesheet" href="styles/styles.css">
</head>
<body>
 
<?php include 'header.inc'; ?>
<?php include 'nav.inc'; ?>
<?php include 'config.php'; ?>

<main class="container">
    <h2>Current Job Opportunities</h2>
    <p>Join our innovative team and work on exciting projects with cutting-edge technologies.</p>
    
    <div class="job-listings">
        <?php
        // Fetch jobs from database
        $sql = "SELECT * FROM jobs ORDER BY created_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                // Convert pipe-separated strings to arrays
                $responsibilities = explode('|', $row["key_responsibilities"]);
                $essential_quals = explode('|', $row["essential_qualifications"]);
                $preferable_quals = explode('|', $row["preferable_qualifications"]);
                
                echo '
                <article class="job">
                    <div class="job-header">
                        <h2 id="job-' . htmlspecialchars($row["job_ref"]) . '">' . htmlspecialchars($row["job_title"]) . '</h2>
                        <div class="job-meta">
                            <div><i>🔖</i> Ref: ' . htmlspecialchars($row["job_ref"]) . '</div>
                            <div><i>💰</i> Salary: ' . htmlspecialchars($row["salary_range"]) . '</div>
                            <div><i>📊</i> Reports to: ' . htmlspecialchars($row["reports_to"]) . '</div>
                        </div>
                        <p>' . htmlspecialchars($row["job_description"]) . '</p>
                    </div>
                    
                    <section class="job-section">
                        <h3>Key Responsibilities</h3>
                        <ol>';
                
                foreach ($responsibilities as $responsibility) {
                    echo '<li>' . htmlspecialchars($responsibility) . '</li>';
                }
                
                echo '
                        </ol>
                    </section>
                    
                    <section class="job-section">
                        <h3>Qualifications & Requirements</h3>
                        <h4>Essential:</h4>
                        <ul>';
                
                foreach ($essential_quals as $qual) {
                    echo '<li>' . htmlspecialchars($qual) . '</li>';
                }
                
                echo '
                        </ul>
                        
                        <h4>Preferable:</h4>
                        <ul>';
                
                foreach ($preferable_quals as $qual) {
                    echo '<li>' . htmlspecialchars($qual) . '</li>';
                }
                
                echo '
                        </ul>
                    </section>
                    
                    <div class="job-apply">
                        <a href="apply.php?job_ref=' . htmlspecialchars($row["job_ref"]) . '&job_title=' . urlencode($row["job_title"]) . '" class="apply-button">Apply for this Position</a>
                    </div>
                </article>';
            }
        } else {
            echo '<div class="no-jobs">
                    <h3>No Current Openings</h3>
                    <p>There are no job openings at the moment. Please check back later or <a href="contact.php">contact us</a> for future opportunities.</p>
                  </div>';
        }

        $conn->close();
        ?>
    </div>
    
    <!-- Benefits Section -->
    <aside>
        <h3>Why Join SolidTech Inc.?</h3>
        <p>We offer more than just a job - we provide an environment where you can grow professionally while working on meaningful projects.</p>
        
        <div class="benefits-list">
            <div class="benefit-item">
                <h4>🔄 Flexible Working</h4>
                <p>Remote work options and flexible hours to support work-life balance</p>
            </div>
            <div class="benefit-item">
                <h4>📚 Learning & Development</h4>
                <p>Annual budget for conferences, courses, and certifications</p>
            </div>
            <div class="benefit-item">
                <h4>🏥 Health & Wellness</h4>
                <p>Comprehensive health insurance and wellness programs</p>
            </div>
            <div class="benefit-item">
                <h4>🚀 Cutting-Edge Technology</h4>
                <p>Work with the latest tools and technologies on innovative projects</p>
            </div>
        </div>
        
        <p>Ready to apply? Submit your application through our online portal or email your resume to careers@solidtech.com</p>
    </aside>
</main>

<?php include 'footer.inc'; ?>
</body>
</html>