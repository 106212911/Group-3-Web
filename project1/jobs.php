<<<<<<< HEAD
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Solid Tech  Inc. - Career Opportunities</title>
        <link rel="Stylesheet" href="styles/styles.css">
    
    </head>
    <body>
    
    <?php include 'header.inc'; ?>
    <?php include 'nav.inc'; ?>

        
        <main class="container">
            <h2>Current Job Opportunities</h2>
            <p>Join our innovative team and work on exciting projects with cutting-edge technologies.</p>
            
            <div class="job-listings">
                <article class="job">
                    <div class="job-header">
                        <h2 id="job-listing">Senior Data Analyst</h2>
                        <div class="job-meta" style="display: flex; flex-direction: row; justify-content: center; align-items: center; gap: 20px; flex-wrap: wrap; background-color: yellow;">
    <div><i>🔖</i> Ref: DA289</div>
    <div><i>💰</i> Salary: RM210,000 - RM230,000</div>
    <div><i>📊</i> Reports to: Human Resources Department</div>
</div>
                        <p>We're seeking an experienced Senior Data Analyst</p>
=======
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
>>>>>>> 06847a301e5cb7383c299d27f8f1ebd3b2ea8e80
                    </div>
                    
                    <section class="job-section">
                        <h3>Key Responsibilities</h3>
<<<<<<< HEAD
                        <ol>
                            <li>Data Collection & Management: Identify, gather, and organize data from various sources and systems.</li>
                            <li>Data cleaning & Quality: Identify and adddress data quality issues and implement controls to ensure data accuracy.</li>
                            <li>Analysis & Interpretation: Use mathematical and statistical models to analyze datasets, identify patterns, and extract actionable insights.</li>
                            <li>Reporting & Visualization: Create clear reports, dashboards, and findings to stakeholders.</li>
                            <li>Collaboration: Work with other data proffesionals and business units to understands data need and support decision-making processes.</li>
                
                            
=======
                        <ol>';
                
                foreach ($responsibilities as $responsibility) {
                    echo '<li>' . htmlspecialchars($responsibility) . '</li>';
                }
                
                echo '
>>>>>>> 06847a301e5cb7383c299d27f8f1ebd3b2ea8e80
                        </ol>
                    </section>
                    
                    <section class="job-section">
                        <h3>Qualifications & Requirements</h3>
                        <h4>Essential:</h4>
<<<<<<< HEAD
                        <ul>
                            <li>5+ years of professional data analyst experience</li>
                            <li>Proficiency in JavaScript/TypeScript, HTML5, and CSS3</li>
                            <li>Able to think critically, indentify patterns, and use data to find solutions to business challenges.</li>
                            <li>Strong knowledge of database systems (SQL and NoSQL)</li>
                            <li>Udersand how data relates to broader business goals and how insights can drive growth and efficiency. </li>
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
                
                <article class="job">
                    <div class="job-header">
                        <h2>AI/ML Solutions Architect</h2>
                        <div class="job-meta">
                            <div><i><mid>🔖</i>Ref: AIP42</div>
                            <div><i>💰</i> Salary: RM230,000 - RM250,000</div>
                            <div><i>📊</i> Reports to: Chief Technology Officer</div>
                        </div>
                        <p>Join our innovation team to design and implement cutting-edge AI and machine learning solutions for diverse industry applications.</p>
                    </div>
                    
                    <section class="job-section">
                        <h3>Key Responsibilities</h3>
                        <ol>
                            <li>Design end-to-end machine learning systems and AI solutions</li>
                            <li>Research and implement appropriate ML algorithms and tools</li>
                            <li>Develop machine learning applications according to requirements</li>
                            <li>Select appropriate datasets and data representation methods</li>
                            <li>Train and retrain systems when necessary</li>
                            <li>Optimize solutions for performance and scalability</li>
                            <li>Collaborate with data engineers to improve data quality</li>
                        </ol>
                    </section>
                    
                    <section class="job-section">
                        <h3>Qualifications & Requirements</h3>
                        <h4>Essential:</h4>
                        <ul>
                            <li>Advanced degree in Computer Science, AI, Machine Learning or related field</li>
                            <li>4+ years of experience in designing and implementing AI solutions</li>
                            <li>Proficiency in Python and ML frameworks like TensorFlow, PyTorch, or Keras</li>
                            <li>Experience with natural language processing and computer vision</li>
                            <li>Strong knowledge of data structures, data modeling, and software architecture</li>
                            <li>Familiarity with cloud AI services (AWS SageMaker, Google AI, Azure ML)</li>
                        </ul>
                        
                        <h4>Preferable:</h4>
                        <ul>
                            <li>PhD in Machine Learning, AI, or related quantitative field</li>
                            <li>Publications in recognized AI conferences or journals</li>
                            <li>Experience with MLOps practices and tools</li>
                            <li>Knowledge of big data tools like Hadoop, Spark, or Kafka</li>
                            <li>Experience with deep learning architectures (CNN, RNN, Transformers)</li>
                        </ul>
                    </section>
                </article>
            </div>
            
            <aside>
                <h3>Why Join SolidTech Inc.?</h3>
                <p>We offer more than just a job - we provide a environment where you can grow professionally while working on meaningful projects.</p>
                
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
=======
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
>>>>>>> 06847a301e5cb7383c299d27f8f1ebd3b2ea8e80

    <?php include 'footer.inc'; ?>
    </body>
    </html>