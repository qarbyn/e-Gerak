<!-- leftnavbar.php -->

<nav class="left-navbar">
    <ul>
        <li><a href="../index.php">Home</li>
        <li><a href="staffdashboard.php">Dashboard</li>
        <li><a href="staffmovement.php">Movement</a></li>
        <li><a href="staffprofile.php">Profile</a></li>
        <li><a href="stafflist.php">Staffs</a></li>
        <li><a href="../logout.php">Log Out</a></li>
    </ul>
</nav>

<style>
/* Navbar styles */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

.left-navbar {
    background-color: #2c3e50; /* Set your background color */
    height: 100vh;
    width: 190px;
    position: fixed;
    top: 0;
    left: 0;
    overflow-x: hidden;
    padding-top: 20px;
    font-family: "Poppins", sans-serif;
}

.left-navbar ul {
    list-style-type: none;
    padding: 0;
}

.left-navbar li {
    padding: 20px;
    text-align: center;
    color: #ecf0f1; /* Set your text color */
    font-family: "Poppins", sans-serif;
    transition: background-color 0.7s ease-in-out, color 0.7s ease-in-out; /* Smooth transition */
}

.left-navbar a {
    text-decoration: none;
    color: #ecf0f1; /* Set your link color */
    font-size: 20px;
    font-family: "Poppins", sans-serif;
    display: block;
    position: relative;
}

.left-navbar a:hover {
    /* Set your hover background color */
    color: #fff; /* Set your hover text color */
    box-shadow: 0 0 20px #3498db; /* Set the glow effect */
    background-color: #3498db;
}

</style>
