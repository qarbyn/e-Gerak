<?php
session_start();

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

include('..\config.php'); // Adjust the path if needed

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_movement"])) {
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $place = $_POST["place"];
    $program = $_POST["program"];
    $user_id = $_SESSION['user_id'];
    $staff_fullname = $_SESSION['user_fullname']; // Assuming 'user_fullname' is the session variable for full name

    // Handle picture upload
    $target_dir = "uploads/"; // Specify the directory where you want to store uploaded pictures
    $uploaded_picture = $target_dir . basename($_FILES["picture"]["name"]);

    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $uploaded_picture)) {
        echo '<script>alert("The file ' . htmlspecialchars(basename($_FILES["picture"]["name"])) . ' has been uploaded.");</script>';
    } else {
        echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
    }

    // Perform the SQL insertion for the movement record using a prepared statement
    $stmt = $conn->prepare("INSERT INTO tblmovement (staff_id, StaffFullName, StartDate, EndDate, Place, Program, MovementPicture) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $user_id, $staff_fullname, $start_date, $end_date, $place, $program, $uploaded_picture);

    if ($stmt->execute()) {
        echo '<script>alert("Movement record added successfully!");</script>';

        // Redirect to another page to avoid form resubmission
        header("Location: staffmovement.php");
        exit();
    } else {
        echo '<script>alert("Error: ' . $stmt->error . '");</script>';
    }

    $stmt->close();
}

// Fetch and display the records
$user_id = $_SESSION['user_id']; // Define $user_id here
$sql = "SELECT * FROM tblmovement WHERE staff_id = '$user_id'";
$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Movement Form</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border: 1px solid #ddd; /* Added border property */
        }

        th {
            background-color: #4a7bed;
            color: #fff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        /* Additional styles for staffmovement.php */
        label {
            display: block;
            margin-top: 10px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            border-radius: 4px;
        }

        input[type="button"] {
            background-color: #4a7bed;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="button"]:hover {
            background-color: #3564ad;
        }

        input[type="submit"] {
            background-color: #4a7bed;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #3564ad;
        }

        img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>
    <?php include('includestaff\staffheader.php'); ?>
    <?php include('includestaff\leftnavbar.php'); ?>
    <main>
        <h2>Staff Movement Form</h2>
        <form action="staffmovement.php" method="POST" enctype="multipart/form-data">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required><br>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required><br>

            <label for="place">Place:</label>
            <input type="text" id="place" name="place" required><br>

            <label for="program">Program:</label>
            <input type="text" id="program" name="program" required><br>

            <label for="picture">Upload Picture:</label>
            <input type="file" id="picture" name="picture" accept="image/*" required><br>

            <input type="submit" value="Submit" name="submit_movement">
        </form>

        <h3>Submitted Records:</h3>
        <table border="1">
            <tr>
                <th>Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Place</th>
                <th>Program</th>
                <th>Movement Picture</th>
            </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["StaffFullName"] . "</td>";
                echo "<td>" . $row["StartDate"] . "</td>";
                echo "<td>" . $row["EndDate"] . "</td>";
                echo "<td>" . $row["Place"] . "</td>";
                echo "<td>" . $row["Program"] . "</td>";
                echo "<td><img src='" . $row["MovementPicture"] . "' alt='Movement Picture' style='max-width: 100px; max-height: 100px;'></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No records found</td></tr>";
        }
        ?>
        </table>

    </main>

    <!-- Add your JavaScript file link here -->
</body>
</html>
