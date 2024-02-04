<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminsignin.php");
    exit();
}

include('../config.php'); // Adjust the path if needed

// Analyze movement form submissions for the current month and year by default
$selectedMonth = isset($_POST['selected_month']) ? $_POST['selected_month'] : date('n');
$selectedYear = isset($_POST['selected_year']) ? $_POST['selected_year'] : date('Y');

// Get total submissions for all staff in the selected month and year
$totalSubmissionsQuery = "SELECT COUNT(*) AS total_submissions 
                          FROM tblmovement 
                          WHERE MONTH(SubmitDate) = $selectedMonth 
                          AND YEAR(SubmitDate) = $selectedYear";
$totalSubmissionsResult = $conn->query($totalSubmissionsQuery);

if (!$totalSubmissionsResult) {
    echo '<script>alert("Error retrieving total submissions: ' . $conn->error . '");</script>';
}

// Retrieve the total submissions for all staff
$totalSubmissions = 0;
if ($totalSubmissionsResult->num_rows > 0) {
    $totalSubmissionsRow = $totalSubmissionsResult->fetch_assoc();
    $totalSubmissions = $totalSubmissionsRow['total_submissions'];
}

// Get staff details for the most submissions
$movementAnalysisQuery = "SELECT s.staff_id, s.StaffFullName, COUNT(*) AS total_submissions 
                          FROM tblmovement m
                          JOIN tblstaff s ON m.staff_id = s.staff_id
                          WHERE MONTH(m.SubmitDate) = $selectedMonth 
                          AND YEAR(m.SubmitDate) = $selectedYear 
                          GROUP BY m.staff_id 
                          ORDER BY total_submissions DESC 
                          LIMIT 1";

$movementAnalysisResult = $conn->query($movementAnalysisQuery);

if (!$movementAnalysisResult) {
    echo '<script>alert("Error analyzing movement form submissions: ' . $conn->error . '");</script>';
}

// Retrieve the staff details for the most submissions
$mostSubmissionsStaff = [];
if ($movementAnalysisResult->num_rows > 0) {
    $mostSubmissionsStaff = $movementAnalysisResult->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Analytics</title>
    <!-- Add your stylesheet link here -->
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

        body {
            background-color: #c6e2e9;
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
        }

        main {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #4a7bed;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        select {
            width: 48%; /* Adjust the width to your preference */
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            border-radius: 4px;
        }

        p {
            margin-top: 10px;
        }

        input[type="analize"] {
            background-color: #4a7bed;
            color: #fff;
            width: 85px;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            transition: background-color 0.3s;
        }

        input[type="analize"]:hover {
            background-color: #3254a8;
        }
    </style>
</head>
<body>
    <main>
        <h2>Admin Analytics</h2>
        <?php include('includeadmin/leftnavbar.php'); ?>
        
        <form action="adminanalytic.php" method="POST">
            <label for="selected_month">Select Month:</label>
            <select id="selected_month" name="selected_month">
                <?php
                // Generate options for months
                for ($month = 1; $month <= 12; $month++) {
                    $selected = ($selectedMonth == $month) ? 'selected' : '';
                    echo "<option value=\"$month\" $selected>" . date("F", mktime(0, 0, 0, $month, 1)) . "</option>";
                }
                ?>
            </select>

            <label for="selected_year">Select Year:</label>
            <select id="selected_year" name="selected_year">
                <?php
                // Generate options for years
                $currentYear = date('Y');
                for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
                    $selected = ($selectedYear == $year) ? 'selected' : '';
                    echo "<option value=\"$year\" $selected>$year</option>";
                }
                ?>
            </select>

            <input type="analize" value="Analize">
        </form>

        <h3>Analysis Results for <?php echo date("F", mktime(0, 0, 0, $selectedMonth, 1)) . ' ' . $selectedYear; ?></h3>
        <?php if (!empty($mostSubmissionsStaff)) : ?>
            <p>Staff with most submissions:   <?php echo $mostSubmissionsStaff['StaffFullName']; ?> with <?php echo $mostSubmissionsStaff['total_submissions'];?> submissions</p>
        <?php else : ?>
            <p>No records in this month and year.</p>
        <?php endif; ?>

        <h3>Total Submissions for All Staff: <?php echo $totalSubmissions; ?></h3>
    </main>
</body>
</html>
