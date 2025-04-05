<?php
session_start();
if(empty($_SESSION['name'])){
    header("location:signin.php");
    exit();
}
include("../connection.php");

// Fetch location data if form submitted
$location_data = [];
if(isset($_POST['district'])) { 
    $location = $_POST['district'];
    $sql = "SELECT * FROM food_donations WHERE location='$location'";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $location_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Iconscout CSS -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <title>Admin Dashboard Panel</title>
    
    <style>
        :root {
            --primary-color: #06C167;
            --secondary-color: #3A7BFF;
            --dark-color: #2c3e50;
            --light-color: #f5f7fa;
            --shadow-dark: 0 5px 15px rgba(0, 0, 0, 0.1);
            --shadow-light: 0 5px 15px rgba(255, 255, 255, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: var(--light-color);
            color: #333;
            transition: all 0.3s ease;
        }
        
        /* 3D Navigation */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(145deg, var(--dark-color), #34495e);
            box-shadow: 10px 0 20px rgba(0, 0, 0, 0.3);
            transform-style: preserve-3d;
            z-index: 100;
            transition: all 0.3s ease;
        }
        
        .logo-name {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo_name {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .menu-items {
            padding: 20px;
            height: calc(100% - 80px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .nav-links li {
            list-style: none;
            margin-bottom: 15px;
        }
        
        .nav-links li a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .nav-links li a:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2);
        }
        
        .nav-links li a i {
            margin-right: 10px;
            font-size: 1.1rem;
        }
        
        .logout-mode {
            list-style: none;
            color:white;
        }
        
        /* 3D Dashboard Content */
        .dashboard {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow-dark);
            margin-bottom: 20px;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        
        .sidebar-toggle {
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        /* 3D Location Filter */
        .location {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow-dark);
            margin-bottom: 20px;
            transform-style: preserve-3d;
        }
        
        .location form {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .location label {
            font-weight: 500;
            color: #555;
        }
        
        .location select, .location input[type="submit"] {
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: white;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .location select {
            min-width: 200px;
        }
        
        .location input[type="submit"] {
            background: var(--primary-color);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 500;
            box-shadow: 0 3px 6px rgba(6, 193, 103, 0.2);
        }
        
        .location input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(6, 193, 103, 0.3);
        }
        
        /* 3D Table */
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
            perspective: 1000px;
        }
        
        .table-wrapper {
            transform-style: preserve-3d;
            transform: rotateX(1deg);
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: var(--shadow-dark);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table th, .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .table th {
            background: var(--primary-color);
            color: white;
            font-weight: 500;
        }
        
        .table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .table tr:hover {
            background: #f1f1f1;
            transform: scale(1.01);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        /* Dark Mode Toggle */
        .mode-toggle {
            position: relative;
            width: 40px;
            height: 20px;
            background: #ddd;
            border-radius: 10px;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .switch {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            nav {
                width: 70px;
            }
            .logo_name, .link-name {
                display: none;
            }
            .dashboard {
                margin-left: 70px;
            }
        }
        .logout-mode {
    list-style: none;
    margin-top: auto; /* Pushes it to the bottom */
    padding: 15px;
}

.logout-mode li a {
    display: flex;
    align-items: center;
    color: white !important; /* Force white color */
    text-decoration: none;
    padding: 12px 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.logout-mode li a:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
    box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.2);
}

.logout-mode li a i {
    margin-right: 10px;
    font-size: 1.1rem;
    color: white !important;
}

.logout-mode .link-name {
    color: white !important;
}
    </style>
</head>
<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <!--<img src="images/logo.png" alt="">-->
            </div>
            <span class="logo_name">ADMIN</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="admin.php">
                    <i class="uil uil-estate"></i>
                    <span class="link-name">Dashboard</span>
                </a></li>
                <li><a href="analytics.php">
                    <i class="uil uil-chart"></i>
                    <span class="link-name">Analytics</span>
                </a></li>
                <li><a href="#">
                    <i class="uil uil-heart"></i>
                    <span class="link-name">Donates</span>
                </a></li>
                <li><a href="feedback.php">
                    <i class="uil uil-comments"></i>
                    <span class="link-name">Feedbacks</span>
                </a></li>
                <li><a href="adminprofile.php">
                    <i class="uil uil-user"></i>
                    <span class="link-name">Profile</span>
                </a></li>
            </ul>
            
            <ul class="logout-mode">
    <li>
        <a href="../logout.php">
            <i class="uil uil-signout"></i>
            <span class="link-name">Logout</span>
        </a>
    </li>
</ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <p class="logo">Food <b style="color: #06C167; ">Donate</b></p>
            <p class="user">Welcome, <?php echo $_SESSION['name']; ?></p>
        </div>
        
        <div class="activity">
            <div class="location">
                <form method="post">
                    <label for="location" class="logo">Select Location:</label>
                    <select id="district" name="district" required>
                        <option value="Mangalore">Mangalore</option>
                        <option value="Bantwal">Bantwal</option>
                        <option value="Puttur">Puttur</option>
                        <option value="Belthangady">Belthangady</option>
                        <option value="Sullia">Sullia</option>
                    </select>
                    <input type="submit" value="Get Details">
                </form>
                <br>

                <?php if(!empty($location_data)): ?>
                <div class="table-container">
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Food</th>
                                    <th>Phone No</th>
                                    <th>Date/Time</th>
                                    <th>Address</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($location_data as $row): ?>
                                <tr>
                                    <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
                                    <td data-label="Food"><?= htmlspecialchars($row['food']) ?></td>
                                    <td data-label="Phone No"><?= htmlspecialchars($row['phoneno']) ?></td>
                                    <td data-label="Date/Time"><?= htmlspecialchars($row['date']) ?></td>
                                    <td data-label="Address"><?= htmlspecialchars($row['address']) ?></td>
                                    <td data-label="Quantity"><?= htmlspecialchars($row['quantity']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php elseif(isset($_POST['district'])): ?>
                    <p>No results found for <?= htmlspecialchars($_POST['district']) ?>.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script>
        // Toggle sidebar
        document.querySelector('.sidebar-toggle').addEventListener('click', function() {
            document.querySelector('nav').classList.toggle('collapsed');
            document.querySelector('.dashboard').classList.toggle('expanded');
        });
        
        // Dark mode toggle
        document.querySelector('.mode-toggle').addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            document.querySelector('.switch').classList.toggle('active');
            
            if(document.body.classList.contains('dark-mode')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        });
        
        // Check for saved dark mode preference
        if(localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            document.querySelector('.switch').classList.add('active');
        }
    </script>
</body>
</html>