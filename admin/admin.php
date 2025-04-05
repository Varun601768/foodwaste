<?php 
ob_start(); 
include("connect.php");

// Check if admin is logged in
if (!isset($_SESSION['name']) || $_SESSION['name'] == '') {
    header("location:signin.php");
    exit();
}

// Use the existing connection from connect.php // Assuming $con is your connection variable from connect.php

// Handle "Delete" action: update status to 'Rejected'
if (isset($_POST['delete'])) {
    $delete_id = mysqli_real_escape_string($connection, $_POST['delete_id']);

    // Update the donation record to mark as rejected
    $delete_query = "UPDATE food_donations SET status='Rejected' WHERE Fid = '$delete_id'";
    $delete_result = mysqli_query($connection, $delete_query);

    if ($delete_result) {
        $_SESSION['message'] = 'Donation rejected successfully.';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['error'] = 'Error rejecting donation: ' . mysqli_error($connection);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle "Get Food" action: update assigned_to and status to 'Accepted'
if (isset($_POST['food']) && isset($_POST['delivery_person_id'])) {
    $order_id = mysqli_real_escape_string($connection, $_POST['order_id']);
    $delivery_person_id = mysqli_real_escape_string($connection, $_POST['delivery_person_id']);

    // Check if the order is already assigned
    $sql = "SELECT * FROM food_donations WHERE Fid = $order_id AND assigned_to IS NOT NULL";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Order has already been assigned to someone else
        $_SESSION['error'] = "Sorry, this order has already been assigned to someone else.";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // Update the donation record to assign it and mark as accepted
    $sql = "UPDATE food_donations 
            SET assigned_to = $delivery_person_id, status='Accepted' 
            WHERE Fid = $order_id";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        $_SESSION['error'] = "Error assigning order: " . mysqli_error($connection);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    $_SESSION['message'] = "Order assigned successfully!";
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Panel</title>
    <!-- Font Awesome and Unicons for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <style>
        :root {
            --primary-color: #06C167;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --shadow: 0 10px 20px rgba(0,0,0,0.2);
            --deep-shadow: 0 15px 30px rgba(0,0,0,0.3);
            --card-gradient: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        nav {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: var(--secondary-color);
            box-shadow: var(--deep-shadow);
            transform-style: preserve-3d;
            transform: perspective(1000px);
            z-index: 100;
            transition: all 0.5s ease;
        }
        
        nav:hover {
            transform: perspective(1000px) rotateY(5deg);
        }
        
        .logo-name {
            display: flex;
            align-items: center;
            padding: 20px;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .logo-image {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            border-radius: 50%;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            transform: translateZ(20px);
        }
        
        .logo_name {
            font-size: 20px;
            font-weight: 600;
            transform: translateZ(10px);
        }
        
        .menu-items {
            height: calc(100% - 80px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 30px 0;
        }
        
        .nav-links li, .logout-mode li {
            list-style: none;
            margin: 8px 0;
            transform: translateZ(0);
            transition: all 0.3s ease;
        }
        
        .nav-links li:hover {
            transform: translateX(10px) translateZ(10px);
        }
        
        .nav-links a, .logout-mode a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            border-radius: 0 30px 30px 0;
            transition: all 0.3s ease;
        }
        
        .nav-links a:hover, .logout-mode a:hover {
            background: var(--primary-color);
            color: white;
            transform: translateZ(10px);
        }
        
        .nav-links i, .logout-mode i {
            font-size: 20px;
            margin-right: 15px;
        }
        
        .link-name {
            font-size: 16px;
            font-weight: 500;
        }
        
        .mode-toggle {
            position: relative;
            width: 40px;
            height: 20px;
            background: #4d4d4d;
            border-radius: 10px;
            cursor: pointer;
            margin-left: 20px;
            transform: translateZ(0);
            transition: all 0.3s ease;
        }
        
        .mode-toggle:hover {
            transform: translateZ(10px);
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
        
        section.dashboard {
            position: relative;
            left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            transition: all 0.5s ease;
            padding: 20px;
        }
        
        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .sidebar-toggle {
            font-size: 24px;
            color: var(--secondary-color);
            cursor: pointer;
            transform: translateZ(0);
            transition: all 0.3s ease;
        }
        
        .sidebar-toggle:hover {
            color: var(--primary-color);
            transform: translateZ(10px) rotate(90deg);
        }
        
        .logo {
            font-size: 24px;
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .dash-content {
            transform-style: preserve-3d;
        }
        
        .overview, .activity {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            transform: translateZ(0);
            transition: all 0.5s ease;
        }
        
        .overview:hover, .activity:hover {
            transform: translateY(-10px) translateZ(10px);
            box-shadow: var(--deep-shadow);
        }
        
        .title {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .title i {
            font-size: 24px;
            color: var(--primary-color);
            margin-right: 10px;
        }
        
        .title .text {
            font-size: 20px;
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .boxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .box {
            background: var(--card-gradient);
            padding: 20px;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow);
            transform: translateZ(0);
            transition: all 0.5s ease;
            position: relative;
            overflow: hidden;
        }
        
        .box:hover {
            transform: translateY(-10px) translateZ(10px);
            box-shadow: var(--deep-shadow);
        }
        
        .box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-color);
        }
        
        .box.box1::before { background: #3498db; }
        .box.box2::before { background: #e74c3c; }
        .box.box3::before { background: #2ecc71; }
        
        .box i {
            font-size: 40px;
            margin-bottom: 15px;
            color: var(--secondary-color);
        }
        
        .box .text {
            font-size: 16px;
            color: #7a7a7a;
            margin-bottom: 10px;
        }
        
        .box .number {
            font-size: 28px;
            font-weight: 700;
            color: var(--secondary-color);
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transform: translateZ(0);
            transition: all 0.5s ease;
        }
        
        .table-container:hover {
            transform: translateY(-5px) translateZ(10px);
            box-shadow: var(--deep-shadow);
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            text-align: left;
            position: relative;
        }
        
        .table th::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 3px;
            background: rgba(255,255,255,0.5);
            bottom: 0;
            left: 0;
        }
        
        .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }
        
        .table tr:hover td {
            background: rgba(6, 193, 103, 0.1);
            transform: translateX(5px);
        }
        
        button {
            padding: 8px 15px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            transform: translateZ(0);
        }
        
        button[name="food"] {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 5px 15px rgba(6, 193, 103, 0.3);
        }
        
        button[name="food"]:hover {
            background: #05a85c;
            transform: translateY(-3px) translateZ(10px);
            box-shadow: 0 8px 20px rgba(6, 193, 103, 0.4);
        }
        
        button[name="delete"] {
            background: var(--accent-color);
            color: white;
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }
        
        button[name="delete"]:hover {
            background: #c0392b;
            transform: translateY(-3px) translateZ(10px);
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
        }
        
        /* Alert messages */
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: white;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            animation: slideIn 0.5s, fadeOut 0.5s 2.5s;
        }
        
        .alert.success {
            background-color: var(--primary-color);
        }
        
        .alert.error {
            background-color: var(--accent-color);
        }
        
        @keyframes slideIn {
            from {right: -300px; opacity: 0;}
            to {right: 20px; opacity: 1;}
        }
        
        @keyframes fadeOut {
            from {opacity: 1;}
            to {opacity: 0;}
        }
        
        @media (max-width: 768px) {
            nav {
                width: 80px;
            }
            
            nav:hover {
                width: 260px;
            }
            
            .link-name {
                display: none;
            }
            
            nav:hover .link-name {
                display: inline;
            }
            
            section.dashboard {
                left: 80px;
                width: calc(100% - 80px);
            }
            
            nav:hover ~ section.dashboard {
                left: 260px;
                width: calc(100% - 260px);
            }
            
            .boxes {
                grid-template-columns: 1fr;
            }
        }
        
        /* 3D floating animation */
        @keyframes float {
            0% { transform: translateY(0px) rotateY(0deg); }
            50% { transform: translateY(-10px) rotateY(5deg); }
            100% { transform: translateY(0px) rotateY(0deg); }
        }
        
        .logo-image {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <?php
    // Display success/error messages
    if (isset($_SESSION['message'])) {
        echo '<div class="alert success">'.$_SESSION['message'].'</div>';
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="alert error">'.$_SESSION['error'].'</div>';
        unset($_SESSION['error']);
    }
    ?>
    
    <nav>
        <div class="logo-name">
            <div class="logo-image">A</div>
            <span class="logo_name">ADMIN</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="#"><i class="uil uil-estate"></i><span class="link-name">Dashboard</span></a></li>
                <li><a href="analytics.php"><i class="uil uil-chart"></i><span class="link-name">Analytics</span></a></li>
                <li><a href="donate.php"><i class="uil uil-heart"></i><span class="link-name">Donates</span></a></li>
                <li><a href="feedback.php"><i class="uil uil-comments"></i><span class="link-name">Feedbacks</span></a></li>
                <li><a href="adminprofile.php"><i class="uil uil-user"></i><span class="link-name">Profile</span></a></li>
                <li><a href="admin_delivery_view.php"><i class="uil uil-share"></i><span class="link-name">DeliveryBoy Details</span></a></li>
            </ul>
            
            <ul class="logout-mode">
                <li><a href="../logout.php"><i class="uil uil-signout"></i><span class="link-name">Logout</span></a></li>
                <li class="mode">
                    <a href="#"><i class="uil uil-moon"></i><span class="link-name">Dark Mode</span></a>
                    <div class="mode-toggle"><span class="switch"></span></div>
                </li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <i class="uil uil-bars sidebar-toggle"></i>
            <p class="logo">Food <b style="color: var(--primary-color);">Donate</b></p>
        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="uil uil-tachometer-fast-alt"></i><span class="text">Dashboard</span>
                </div>
                <div class="boxes">
                    <div class="box box1">
                        <i class="uil uil-user"></i>
                        <span class="text">Total users</span>
                        <?php
                        $query = "SELECT count(*) as count FROM login";
                        $result = mysqli_query($connection, $query);
                        $row = mysqli_fetch_assoc($result);
                        echo "<span class=\"number\">" . $row['count'] . "</span>";
                        ?>
                    </div>
                    <div class="box box2">
                        <i class="uil uil-comments"></i>
                        <span class="text">Feedbacks</span>
                        <?php
                        $query = "SELECT count(*) as count FROM user_feedback";
                        $result = mysqli_query($connection, $query);
                        $row = mysqli_fetch_assoc($result);
                        echo "<span class=\"number\">" . $row['count'] . "</span>";
                        ?>
                    </div>
                    <div class="box box3">
                        <i class="uil uil-heart"></i>
                        <span class="text">Total donates</span>
                        <?php
                        $query = "SELECT count(*) as count FROM food_donations";
                        $result = mysqli_query($connection, $query);
                        $row = mysqli_fetch_assoc($result);
                        echo "<span class=\"number\">" . $row['count'] . "</span>";
                        ?>
                    </div>
                </div>
            </div>

            <div class="activity">
                <div class="title">
                    <i class="uil uil-clock-three"></i>
                    <span class="text">Recent Donations</span>
                </div>
                <div class="get">
                    <?php
                    $loc = $_SESSION['location'];

                    // Fetch donations that are unassigned (for the admin's location)
                    $sql = "SELECT * FROM food_donations WHERE assigned_to IS NULL AND location='$loc' AND status IS NULL OR status='Pending'";
                    $result = mysqli_query($connection, $sql);
                    $id = $_SESSION['Aid'];

                    if (!$result) {
                        die("Error executing query: " . mysqli_error($connection));
                    }

                    // Collect the data as an associative array
                    $data = array();
                    while ($row = mysqli_fetch_assoc($result)) {
                        $data[] = $row;
                    }
                    ?>
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
                                        <th>Action</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $row) { ?>
                                        <tr>
                                            <td data-label="name"><?= htmlspecialchars($row['name']) ?></td>
                                            <td data-label="food"><?= htmlspecialchars($row['food']) ?></td>
                                            <td data-label="phoneno"><?= htmlspecialchars($row['phoneno']) ?></td>
                                            <td data-label="date"><?= htmlspecialchars($row['date']) ?></td>
                                            <td data-label="address"><?= htmlspecialchars($row['address']) ?></td>
                                            <td data-label="quantity"><?= htmlspecialchars($row['quantity']) ?></td>
                                            <td data-label="Action">
                                                <?php if (is_null($row['assigned_to'])) { ?>
                                                    <form method="post" action="">
                                                        <input type="hidden" name="order_id" value="<?= $row['Fid'] ?>">
                                                        <input type="hidden" name="delivery_person_id" value="<?= $id ?>">
                                                        <button type="submit" name="food">Get Food</button>
                                                    </form>
                                                <?php } else if ($row['assigned_to'] == $id) { ?>
                                                    <span style="color: var(--primary-color); font-weight: 600;">Assigned to you</span>
                                                <?php } else { ?>
                                                    <span style="color: var(--accent-color);">Assigned to another</span>
                                                <?php } ?>
                                            </td>
                                            <td data-label="Delete">
                                                <form method="post" action="">
                                                    <input type="hidden" name="delete_id" value="<?= $row['Fid'] ?>">
                                                    <button type="submit" name="delete" onclick="return confirm('Are you sure you want to reject this donation?');">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Toggle sidebar on mobile
        document.querySelector('.sidebar-toggle').addEventListener('click', function() {
            document.querySelector('nav').classList.toggle('active');
        });

        // Dark mode toggle
        document.querySelector('.mode-toggle').addEventListener('click', function() {
            document.body.classList.toggle('dark');
            document.querySelector('.switch').classList.toggle('active');
            
            if (document.body.classList.contains('dark')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        });

        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark');
            document.querySelector('.switch').classList.add('active');
        }
    </script>
</body>
</html>
<?php ob_end_flush(); ?>