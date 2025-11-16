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

<main class="container">
    <h2>Current Job Opportunities</h2>
    <p>Join our innovative team and work on exciting projects with cutting-edge technologies.</p>
    
    <div class="job-listings">
        <?php
        // DATABASE CONFIGURATION - Directly in file
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "jobs";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            // If connection fails, show static content
            echo "<p style='color: red;'>Database connection failed. Showing sample jobs.</p>";
            ?>
            
            <!-- STATIC JOB LISTINGS AS FALLBACK -->
            <article class="job">
                <div class="job-header">
                    <h2 id="job-listing">Senior Data Analyst</h2>
                    <div class="job-meta">
                        <div><i>🔖</i> Ref: DA289</div>
                        <div><i>💰</i> Salary: RM210,000 - RM230,000</div>
                        <div><i>📊</i> Reports to: Human Resources Department</div>
                    </div>
                    <p>We're seeking an experienced Senior Data Analyst</p>
                </div>
                
                <section class="job-section">
                    <h3>Key Responsibilities</h3>
                    <ol>
                        <li>Data Collection & Management: Identify, gather, and organize data from various sources and systems.</li>
                        <li>Data cleaning & Quality: Identify and address data quality issues and implement controls to ensure data accuracy.</li>
                        <li>Analysis & Interpretation: Use mathematical and statistical models to analyze datasets, identify patterns, and extract actionable insights.</li>
                        <li>Reporting & Visualization: Create clear reports, dashboards, and findings to stakeholders.</li>
                        <li>Collaboration: Work with other data professionals and business units to understand data needs and support decision-making processes.</li>
                    </ol>
                </section>
                
                <section class="job-section">
                    <h3>Qualifications & Requirements</h3>
                    <h4>Essential:</h4>
                    <ul>
                        <li>5+ years of professional data analyst experience</li>
                        <li>Proficiency in JavaScript/TypeScript, HTML5, and CSS3</li>
                        <li>Able to think critically, identify patterns, and use data to find solutions to business challenges.</li>
                        <li>Strong knowledge of database systems (SQL and NoSQL)</li>
                        <li>Understand how data relates to broader business goals and how insights can drive growth and efficiency.</li>
                        <li>Familiarity with Git and agile development methodologies</li>
                        <li>Bachelor's degree in Computer Science or related field</li>
                    </ul>
                    
                    <h4>Preferable:</h4>
                    <ul>
                        <li>Experience with Big Data technologies (such as Hadoop, Spark, or similar distributed computing frameworks)</li>
                        <li>Advanced statistical modeling expertise (including predictive modeling, regression analysis, and experimental design)</li>
                        <li>Proficiency in data visualization tools beyond basic charting (such as Tableau, Power BI, or advanced D3.js)</li>
                        <li>Domain knowledge in our specific industry (such as finance, healthcare, e-commerce, or marketing analytics)</li>
                        <li>Experience with cloud data platforms (such as AWS Redshift, Google BigQuery, Azure Synapse, or Snowflake)</li>
                    </ul>
                </section>
            </article>
            
            <?php
        } else {
            // Connection successful - show dynamic content
            $sql = "SELECT * FROM jobs";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $responsibilities = explode('|', $row["key_responsibilities"]);
                    $essential_quals = explode('|', $row["essential_qualifications"]);
                    $preferable_quals = explode('|', $row["preferable_qualifications"]);
                    ?>
                    
                    <article class="job">
                        <div class="job-header">
                            <h2 id="job-listing"><?php echo htmlspecialchars($row["job_title"]); ?></h2>
                            <div class="job-meta">
                                <div><i>🔖</i> Ref: <?php echo htmlspecialchars($row["job_ref"]); ?></div>
                                <div><i>💰</i> Salary: <?php echo htmlspecialchars($row["salary_range"]); ?></div>
                                <div><i>📊</i> Reports to: <?php echo htmlspecialchars($row["reports_to"]); ?></div>
                            </div>
                            <p><?php echo htmlspecialchars($row["job_description"]); ?></p>
                        </div>
                        
                        <section class="job-section">
                            <h3>Key Responsibilities</h3>
                            <ol>
                                <?php foreach ($responsibilities as $responsibility): ?>
                                    <li><?php echo htmlspecialchars($responsibility); ?></li>
                                <?php endforeach; ?>
                            </ol>
                        </section>
                        
                        <section class="job-section">
                            <h3>Qualifications & Requirements</h3>
                            <h4>Essential:</h4>
                            <ul>
                                <?php foreach ($essential_quals as $qual): ?>
                                    <li><?php echo htmlspecialchars($qual); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            
                            <h4>Preferable:</h4>
                            <ul>
                                <?php foreach ($preferable_quals as $qual): ?>
                                    <li><?php echo htmlspecialchars($qual); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    </article>
                    <?php
                }
            } else {
                echo "<p>No jobs found in database.</p>";
            }
            $conn->close();
        }
        ?>
    </div>
    

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