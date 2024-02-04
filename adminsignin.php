<?php
session_start();

include('config.php');

// Handle admin registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = $_POST["password"];

    // Validate username to be in uppercase
    if ($username !== strtoupper($username)) {
        echo '<script>alert("Username must be in uppercase!");</script>';
        exit();
    }

    // Validate password to contain both symbols and numbers
    if (!preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
        echo '<script>alert("Password must contain both symbols and numbers!");</script>';
        exit();
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    // Perform the SQL insertion for admin registration using prepared statement
    $stmt = $conn->prepare("INSERT INTO tbladmin (AdminUsername, AdminPassword) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        echo '<script>alert("Admin registration successful!");</script>';
    } else {
        echo '<script>alert("Error! ' . $stmt->error . '");</script>';
    }

    $stmt->close();
}

// Handle admin sign in
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signin"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = $_POST["password"];

    // Retrieve admin information from the database using prepared statement
    $stmt = $conn->prepare("SELECT * FROM tbladmin WHERE AdminUsername = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["AdminPassword"])) {
            // Admin login successful, store admin information in session
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['admin_username'] = $row['AdminUsername'];

            // Redirect to admindashboard.php
            header("Location: admin/admindashboard.php");
            exit();
        } else {
            echo '<script>alert("Incorrect password!");</script>';
        }
    } else {
        echo '<script>alert("Admin not found!");</script>';
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <title>Admin Sign In and Registration</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

        *,
        *::before,
        *::after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body,
        input {
            font-family: "Poppins", sans-serif;
        }

        main {
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
            background-color: #c6e2e9;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .box {
            position: relative;
            width: 100%;
            max-width: 1020px;
            height: 640px;
            background-color: #fff;
            border-radius: 3.3rem;
            box-shadow: 0 60px 40px -30px rgba(0, 0, 0, 0.27);
        }

        .inner-box {
            position: absolute;
            width: calc(100% - 4.1rem);
            height: calc(100% - 4.1rem);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .forms-wrap {
            position: absolute;
            height: 100%;
            width: 45%;
            top: 0;
            left: 0;
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 1fr;
            transition: 0.8s ease-in-out;
        }

        form {
            max-width: 260px;
            width: 100%;
            margin: 0 auto;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            grid-column: 1 / 2;
            grid-row: 1 / 2;
            transition: opacity 0.02s 0.4s;
        }

        form.sign-up-form {
            opacity: 0;
            pointer-events: none;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 35px;
            margin-right: 0.3rem;
        }

        .logo h4 {
            font-size: 1.5rem;
            margin-top: -9px;
            letter-spacing: -0.5px;
            color: #151111;
        }

        .heading h2 {
            font-size: 2.1rem;
            font-weight: 600;
            color: #151111;
        }

        .heading h6 {
            color: #bababa;
            font-weight: 400;
            font-size: 0.75rem;
            display: inline;
        }

        .toggle {
            color: #151111;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 500;
            transition: 0.3s;
            cursor: pointer;
        }

        .toggle:hover {
            color: #8371fd;
        }

        .input-wrap {
            position: relative;
            height: 37px;
            margin-bottom: 2rem;
        }

        .input-field {
            position: absolute;
            width: 100%;
            height: 100%;
            background: none;
            border: none;
            outline: none;
            border-bottom: 1px solid #bbb;
            padding: 0;
            font-size: 0.95rem;
            color: #151111;
            transition: 0.4s;
        }

        label {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.95rem;
            color: #bbb;
            pointer-events: none;
            transition: 0.4s;
        }

        .input-field.active {
            border-bottom-color: #151111;
        }

        .input-field.active + label {
            font-size: 0.75rem;
            top: -2px;
        }

        .sign-btn {
            display: inline-block;
            width: 100%;
            height: 43px;
            background-color: #151111;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 0.8rem;
            font-size: 0.8rem;
            margin-bottom: 2rem;
            transition: 0.3s;
        }

        .sign-btn:hover {
            background-color: #8371fd;
        }

        .text {
            color: #bbb;
            font-size: 0.7rem;
        }

        .text a {
            color: #bbb;
            transition: 0.3s;
        }

        .text a:hover {
            color: #8371fd;
        }

        main.sign-up-mode form.sign-in-form {
            opacity: 0;
            pointer-events: none;
        }

        main.sign-up-mode form.sign-up-form {
            opacity: 1;
            pointer-events: all;
        }

        main.sign-up-mode .forms-wrap {
            left: 55%;
        }

        main.sign-up-mode .carousel {
            left: 0%;
        }

        .carousel {
            position: absolute;
            height: 100%;
            width: 55%;
            left: 45%;
            top: 0;
            background-color: #daeaf6;
            border-radius: 2rem;
            display: grid;
            grid-template-rows: auto 1fr;
            padding-bottom: 2rem;
            overflow: hidden;
            transition: 0.8s ease-in-out;
        }

        .images-wrapper {
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 1fr;
        }

        .image {
            width: 100%;
            grid-column: 1/2;
            grid-row: 1/2;
            opacity: 0;
            transition: opacity 0.3s, transform 0.5s;
        }

        .img-1 {
            transform: translate(0, -50px);
        }

        .img-2 {
            transform: scale(0.4, 0.5);
        }

        .img-3 {
            transform: scale(0.3) rotate(-20deg);
        }

        .image.show {
            opacity: 1;
            transform: none;
        }

        .text-slider {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .text-wrap {
            max-height: 2.2rem;
            overflow: hidden;
            margin-bottom: 2.5rem;
        }

        .text-group {
            display: flex;
            flex-direction: column;
            text-align: center;
            transform: translateY(0);
            transition: 0.5s;
        }

        .text-group h2 {
            line-height: 2.2rem;
            font-weight: 600;
            font-size: 1.6rem;
        }

        .bullets {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bullets span {
            display: block;
            width: 0.5rem;
            height: 0.5rem;
            background-color: #aaa;
            margin: 0 0.25rem;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
        }

        .bullets span.active {
            width: 1.1rem;
            background-color: #151111;
            border-radius: 1rem;
        }

        @media (max-width: 850px) {
            .box {
                height: auto;
                max-width: 550px;
                overflow: hidden;
            }

            .inner-box {
                position: static;
                transform: none;
                width: revert;
                height: revert;
                padding: 2rem;
            }

            .forms-wrap {
                position: revert;
                width: 100%;
                height: auto;
            }

            form {
                max-width: revert;
                padding: 1.5rem 2.5rem 2rem;
                transition: transform 0.8s ease-in-out, opacity 0.45s linear;
            }

            .heading {
                margin: 2rem 0;
            }

            form.sign-up-form {
                transform: translateX(100%);
            }

            main.sign-up-mode form.sign-in-form {
                transform: translateX(-100%);
            }

            main.sign-up-mode form.sign-up-form {
                transform: translateX(0%);
            }

            .carousel {
                position: revert;
                height: auto;
                width: 100%;
                padding: 3rem 2rem;
                display: flex;
            }

            .images-wrapper {
                display: none;
            }

            .text-slider {
                width: 100%;
            }
        }

        @media (max-width: 530px) {
            main {
                padding: 1rem;
            }

            .box {
                border-radius: 2rem;
            }

            .inner-box {
                padding: 1rem;
            }

            .carousel {
                padding: 1.5rem 1rem;
                border-radius: 1.6rem;
            }

            .text-wrap {
                margin-bottom: 1rem;
            }

            .text-group h2 {
                font-size: 1.2rem;
            }

            form {
                padding: 1rem 2rem 1.5rem;
            }
        }

            .home-icon {
            width: 20px; /* Adjust the size as needed */
            margin-right: 10px; /* Adjust the margin as needed */
            cursor: pointer; /* Add a pointer cursor for better usability */
            }
    </style>
</head>
<body>
    <main>
        <div class="box">
            <div class="inner-box">
                <div class="forms-wrap">
                    <!-- Admin Sign In form -->
                    <form action="adminsignin.php" method="post" autocomplete="off" class="sign-in-form">
                        <div class="logo">
                            <a href="index.php">
                                <i class="fas fa-home home-icon"></i>
                            </a>
                            <img src="./images/Logo Jata KPM 01PPDKT-hitam (4) (1).png" alt="e-gerak" />
                            <h4>e-Gerak</h4>
                        </div>

                        <div class="heading">
                            <h2>Welcome Back Admin</h2>
                            <h6>Not an admin yet?</h6>
                            <a href="#" class="toggle">Sign up as Admin</a>
                        </div>

                        <div class="actual-form">
                            <div class="input-wrap">
                                <input
                                    type="text"
                                    name="username"
                                    minlength="4"
                                    class="input-field"
                                    autocomplete="off"
                                    required
                                />
                                <label>Username</label>
                            </div>

                            <div class="input-wrap">
                                <input
                                    type="password"
                                    name="password"
                                    minlength="4"
                                    class="input-field"
                                    autocomplete="off"
                                    required
                                />
                                <label>Password</label>
                            </div>

                            <input type="submit" name="signin" value="Sign In" class="sign-btn" />
                        </div>
                    </form>

                    <!-- Admin Sign Up form -->
                    <form action="adminsignin.php" method="post" autocomplete="off" class="sign-up-form">
                        <div class="logo">
                            <img src="./images/Logo Jata KPM 01PPDKT-hitam (4) (1).png" alt="e-Gerak" />
                            <h4>e-Gerak</h4>
                        </div>

                        <div class="heading">
                            <h2>Get Started as Admin</h2>
                            <h6>Already have an admin account?</h6>
                            <a href="#" class="toggle">Sign in</a>
                        </div>

                        <div class="actual-form">
                            <div class="input-wrap">
                                <input
                                    type="text"
                                    name="username"
                                    minlength="4"
                                    class="input-field"
                                    autocomplete="off"
                                    required
                                />
                                <label>Username</label>
                            </div>

                            <div class="input-wrap">
                                <input
                                    type="password"
                                    name="password"
                                    minlength="6"
                                    class="input-field"
                                    autocomplete="off"
                                    required
                                />
                                <label>Password</label>
                            </div>

                            <input type="submit" name="signup" value="Sign Up" class="sign-btn" />
                        </div>
                    </form>
                </div>

                <div class="carousel">
                    <div class="images-wrapper">
                        <img src="./img/art paint.jpeg" class="image img-1 show" alt="" />
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Javascript file -->
    <script src="script.js"></script>
</body>
</html>
