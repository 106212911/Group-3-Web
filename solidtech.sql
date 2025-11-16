-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2025 at 02:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `solidtech`
--

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(11) NOT NULL,
  `JobReference` varchar(5) NOT NULL,
  `FirstName` varchar(20) NOT NULL,
  `LastName` varchar(20) NOT NULL,
  `DOB` date NOT NULL,
  `Gender` enum('Male','Female','Others') NOT NULL,
  `StreetAddress` varchar(40) NOT NULL,
  `Suburb` varchar(40) NOT NULL,
  `State` enum('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  `Postcode` char(4) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Phone` varchar(15) NOT NULL,
  `Skills` text NOT NULL,
  `OtherSkills` text NOT NULL,
  `Status` enum('New','Current','Final') NOT NULL DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `JobReference`, `FirstName`, `LastName`, `DOB`, `Gender`, `StreetAddress`, `Suburb`, `State`, `Postcode`, `Email`, `Phone`, `Skills`, `OtherSkills`, `Status`) VALUES
(11, 'DA289', 'John', 'Doe', '2025-10-31', 'Male', '27 Riverside Grove, Parramatta NSW 2150', 'Parramatta', 'NSW', '2150', 'johndoe@gmail.com', '34567812', 'Python, PowerBI', 'Coder', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `job_ref` varchar(20) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `salary_range` varchar(100) NOT NULL,
  `reports_to` varchar(255) NOT NULL,
  `job_description` text NOT NULL,
  `key_responsibilities` text NOT NULL,
  `essential_qualifications` text NOT NULL,
  `preferable_qualifications` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `job_ref`, `job_title`, `salary_range`, `reports_to`, `job_description`, `key_responsibilities`, `essential_qualifications`, `preferable_qualifications`) VALUES
(1, 'DA289', 'Senior Data Analyst', 'RM210,000 - RM230,000', 'Human Resources Department', 'We\'re seeking an experienced Senior Data Analyst', 'Data Collection & Management: Identify, gather, and organize data from various sources and systems.|Data cleaning & Quality: Identify and address data quality issues and implement controls to ensure data accuracy.|Analysis & Interpretation: Use mathematical and statistical models to analyze datasets, identify patterns, and extract actionable insights.|Reporting & Visualization: Create clear reports, dashboards, and findings to stakeholders.|Collaboration: Work with other data professionals and business units to understand data needs and support decision-making processes.', '5+ years of professional data analyst experience|Proficiency in JavaScript/TypeScript, HTML5, and CSS3|Able to think critically, identify patterns, and use data to find solutions to business challenges|Strong knowledge of database systems (SQL and NoSQL)|Understand how data relates to broader business goals and how insights can drive growth and efficiency|Familiarity with Git and agile development methodologies|Bachelor\'s degree in Computer Science or related field', 'Experience with Big Data technologies (such as Hadoop, Spark, or similar distributed computing frameworks)|Advanced statistical modeling expertise (including predictive modeling, regression analysis, and experimental design)|Proficiency in data visualization tools beyond basic charting (such as Tableau, Power BI, or advanced D3.js)|Domain knowledge in our specific industry (such as finance, healthcare, e-commerce, or marketing analytics)|Experience with cloud data platforms (such as AWS Redshift, Google BigQuery, Azure Synapse, or Snowflake)'),
(2, 'AIP42', 'AI/ML Solutions Architect', 'RM230,000 - RM250,000', 'Chief Technology Officer', 'Join our innovation team to design and implement cutting-edge AI and machine learning solutions for diverse industry applications.', 'Design end-to-end machine learning systems and AI solutions|Research and implement appropriate ML algorithms and tools|Develop machine learning applications according to requirements|Select appropriate datasets and data representation methods|Train and retrain systems when necessary|Optimize solutions for performance and scalability|Collaborate with data engineers to improve data quality', 'Advanced degree in Computer Science, AI, Machine Learning or related field|4+ years of experience in designing and implementing AI solutions|Proficiency in Python and ML frameworks like TensorFlow, PyTorch, or Keras|Experience with natural language processing and computer vision|Strong knowledge of data structures, data modeling, and software architecture|Familiarity with cloud AI services (AWS SageMaker, Google AI, Azure ML)', 'PhD in Machine Learning, AI, or related quantitative field|Publications in recognized AI conferences or journals|Experience with MLOps practices and tools|Knowledge of big data tools like Hadoop, Spark, or Kafka|Experience with deep learning architectures (CNN, RNN, Transformers)');

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`id`, `username`, `password`, `failed_attempts`, `locked_until`) VALUES
(5, 'Jun Hao', '123', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
