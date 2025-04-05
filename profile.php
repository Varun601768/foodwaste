<?php
include("login.php");

// Redirect to signup page if not logged in
if (!isset($_SESSION['name']) || $_SESSION['name'] == '') {
    header("location: signup.php");
    exit();
}

$email = $_SESSION['email'];
$message = "";

// Database connection (Ensure it's included in login.php or define it here)


// Check for rejected food donations
$query = "SELECT * FROM food_donations WHERE email='$email' AND status='rejected'";
$result = mysqli_query($connection, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $message = "Your food donation was rejected.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
</head>
<style>
        /* 3D Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4efe9 100%);
            color: #333;
            overflow-x: hidden;
            min-height: 100vh;
            perspective: 1000px;
            padding-top: 80px;
        }

        /* 3D Header with Depth */
        header {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            backdrop-filter: blur(10px);
            transform-style: preserve-3d;
            transform: translateZ(20px);
            border-bottom: 1px solid rgba(6, 193, 103, 0.2);
        }

        .logo {
            font-size: 2.2rem;
            font-weight: 800;
            color: #333;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.1);
            letter-spacing: 1px;
            transform: translateZ(30px);
        }

        /* Navigation Bar */
        .nav-bar {
            display: flex;
            transform-style: preserve-3d;
        }

        .nav-bar ul {
            display: flex;
            list-style: none;
            transform-style: preserve-3d;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .nav-bar ul li {
            margin: 0 1rem;
            transform-style: preserve-3d;
        }

        .nav-bar ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            position: relative;
            transform-style: preserve-3d;
        }

        .nav-bar ul li a:before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: #06C167;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        .nav-bar ul li a:hover:before {
            transform: scaleX(1);
            transform-origin: left;
        }

        .nav-bar ul li a.active {
            background: #06C167;
            color: white;
            box-shadow: 0 5px 15px rgba(6, 193, 103, 0.4);
            transform: translateZ(10px);
        }

        /* Hamburger Menu */
        .hamburger {
            display: none;
            cursor: pointer;
            transform-style: preserve-3d;
        }

        .hamburger .line {
            width: 30px;
            height: 3px;
            background: #333;
            margin: 6px 0;
            transition: all 0.3s ease;
        }

        /* 3D Profile Section */
        .profile {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            transform-style: preserve-3d;
        }

        .profilebox {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transform-style: preserve-3d;
            transform: translateZ(20px);
            transition: all 0.5s ease;
        }

        .profilebox:hover {
            transform: translateZ(30px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
        }

        .headingline {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 20px;
            transform: translateZ(30px);
            position: relative;
            display: inline-block;
        }

        .headingline::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 4px;
            background: #06C167;
            border-radius: 2px;
            transform: translateZ(20px);
        }

        .info {
            margin-bottom: 30px;
            transform-style: preserve-3d;
        }

        .info p {
            font-size: 1.2rem;
            margin: 15px 0;
            transform: translateZ(15px);
            padding: 10px;
            background: rgba(6, 193, 103, 0.1);
            border-radius: 8px;
            border-left: 4px solid #06C167;
        }

        .info a {
            text-decoration: none;
            font-size: 1.1rem;
            padding: 10px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
            transform: translateZ(20px);
            display: inline-block;
            box-shadow: 0 5px 15px rgba(6, 193, 103, 0.3);
        }

        .info a:hover {
            background: #04a858;
            transform: translateZ(30px) scale(1.05);
            box-shadow: 0 8px 25px rgba(6, 193, 103, 0.4);
        }

        .alert {
            margin: 20px 0;
            padding: 15px;
            border-radius: 8px;
            transform: translateZ(20px);
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.1);
        }

        .heading {
            font-size: 2rem;
            color: #333;
            margin: 30px 0 20px;
            transform: translateZ(25px);
            position: relative;
        }

        .heading::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 80px;
            height: 3px;
            background: #06C167;
            border-radius: 2px;
            transform: translateZ(15px);
        }

        /* 3D Table Section */
        .table-container {
            margin: 40px 0;
            transform-style: preserve-3d;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transform: translateZ(20px);
            transition: all 0.5s ease;
        }

        .table-wrapper:hover {
            transform: translateZ(30px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            transform-style: preserve-3d;
        }

        .table th {
            background: #06C167;
            color: white;
            padding: 15px;
            text-align: left;
            transform: translateZ(25px);
            position: relative;
        }

        .table th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: rgba(255, 255, 255, 0.5);
            transform: translateZ(15px);
        }

        .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            transform: translateZ(15px);
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .table tr:hover td {
            background: rgba(6, 193, 103, 0.05);
            transform: translateZ(20px);
        }

        /* Status Badges */
        .table td:last-child {
            font-weight: 600;
        }

        .table td:last-child[data-status="Pending"] {
            color: #ffc107;
        }

        .table td:last-child[data-status="Completed"] {
            color: #28a745;
        }

        .table td:last-child[data-status="Cancelled"] {
            color: #dc3545;
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .hamburger {
                display: block;
                transform: translateZ(30px);
            }

            .nav-bar {
                position: fixed;
                top: 80px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 80px);
                background: rgba(255, 255, 255, 0.98);
                transition: all 0.5s ease;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(10px);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 999;
                flex-direction: column;
            }

            .nav-bar.active {
                left: 0;
            }

            .nav-bar ul {
                flex-direction: column;
                padding: 2rem;
                width: 100%;
                text-align: center;
            }

            .nav-bar ul li {
                margin: 1.5rem 0;
            }

            .nav-bar ul li a {
                font-size: 1.3rem;
                padding: 1rem 2rem;
                display: block;
            }

            .hamburger.active .line:nth-child(1) {
                transform: rotate(45deg) translate(5px, 6px);
            }

            .hamburger.active .line:nth-child(2) {
                opacity: 0;
            }

            .hamburger.active .line:nth-child(3) {
                transform: rotate(-45deg) translate(5px, -6px);
            }

            .profilebox {
                padding: 20px;
            }

            .headingline {
                font-size: 2rem;
            }

            .table th, .table td {
                padding: 10px;
                font-size: 0.9rem;
            }
        }

        /* 3D Animations */
        @keyframes floatIn {
            from {
                opacity: 0;
                transform: translateY(50px) translateZ(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0) translateZ(0);
            }
        }

        .profilebox, .heading, .table-wrapper {
            animation: floatIn 1s ease-out forwards;
        }

        .profilebox {
            animation-delay: 0.2s;
        }

        .heading {
            animation-delay: 0.4s;
        }

        .table-wrapper {
            animation-delay: 0.6s;
        }
    </style>
<body>
<header>
    <div class="logo">Food <b style="color: #06C167;">Donate</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="profile.php" class="active">Profile</a></li>
        </ul>
    </nav>
</header>
<script>
    const hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function() {
        const navBar = document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    }
</script>

<div class="profile">
    <div class="profilebox">
        <p class="headingline">
            Profile
        </p>
        <div class="info">
            <p>Name: <?php echo htmlspecialchars($_SESSION['name']); ?></p>
            <p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p>Gender: <?php echo htmlspecialchars($_SESSION['gender']); ?></p>
            <a href="logout.php">Logout</a>
        </div>
        <hr>
        <br>
        <p class="heading">Your Donations</p>

        <div class="table-container">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Food</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Date/Time</th>
                            <th>Status</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
$query = "SELECT * FROM food_donations WHERE email='$email'";
$result = mysqli_query($connection, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
        <td>" . htmlspecialchars($row['food']) . "</td>
        <td>" . htmlspecialchars($row['type']) . "</td>
        <td>" . htmlspecialchars($row['category']) . "</td>
        <td>" . htmlspecialchars($row['date']) . "</td>
        <td>" . htmlspecialchars($row['status']) . "</td>
        <td><button onclick=\"sendEmail('" . $row['email'] . "')\">Message</button></td>
    </tr>";
    }
}    
?>


                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<script>
function sendEmail(email) {
    fetch('send_email.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'email=' + encodeURIComponent(email)
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Show success or error message
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>




</body>
</html>