<?php
ob_start(); 

include("connect.php"); 

if ($_SESSION['name'] == '') {
    header("location:deliverylogin.php");
}

$name = $_SESSION['name'];
$id = $_SESSION['Did'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Orders</title>
    <link rel="stylesheet" href="delivery.css">
    <link rel="stylesheet" href="../home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #06C167;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --shadow: 0 10px 20px rgba(0,0,0,0.2);
            --deep-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        header {
            background: white;
            box-shadow: var(--shadow);
            transform: translateZ(10px);
            transition: all 0.3s ease;
        }
        
        .logo {
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .nav-bar ul li a {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .nav-bar ul li a:hover {
            color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .nav-bar ul li a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            background: var(--primary-color);
            bottom: -5px;
            left: 0;
            transition: width 0.3s;
        }
        
        .nav-bar ul li a.active::after,
        .nav-bar ul li a:hover::after {
            width: 100%;
        }
        
        .itm {
            background-color: white;
            display: grid;
            border-radius: 20px;
            box-shadow: var(--deep-shadow);
            transform-style: preserve-3d;
            transform: perspective(1000px);
            transition: all 0.5s ease;
            overflow: hidden;
            margin: 20px auto;
            max-width: 400px;
        }
        
        .itm:hover {
            transform: perspective(1000px) rotateY(5deg) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        .itm img {
            width: 100%;
            height: auto;
            transition: all 0.5s ease;
        }
        
        .itm:hover img {
            transform: scale(1.05);
        }
        
        h2 {
            text-align: center;
            color: var(--secondary-color);
            font-size: 2rem;
            margin: 20px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            position: relative;
            display: inline-block;
            left: 50%;
            transform: translateX(-50%);
        }
        
        h2::after {
            content: '';
            position: absolute;
            width: 50%;
            height: 4px;
            background: var(--primary-color);
            bottom: -10px;
            left: 25%;
            border-radius: 2px;
        }
        
        .log {
            text-align: center;
            margin: 20px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }
        
        .log a, .log p {
            display: inline-block;
            padding: 12px 25px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            transform: translateZ(0);
        }
        
        .log p {
            background: var(--secondary-color);
        }
        
        .log a:hover {
            transform: translateY(-5px) translateZ(10px);
            box-shadow: 0 15px 25px rgba(6, 193, 103, 0.3);
            background: #05a85c;
        }
        
        .table-container {
            background: white;
            border-radius: 20px;
            box-shadow: var(--deep-shadow);
            padding: 20px;
            margin: 20px auto;
            transform-style: preserve-3d;
            transform: perspective(1000px);
            transition: all 0.5s ease;
            overflow: hidden;
        }
        
        .table-container:hover {
            transform: perspective(1000px) translateY(-10px);
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
            border-bottom: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .table tr:hover td {
            background: rgba(6, 193, 103, 0.1);
            transform: translateX(5px);
        }
        
        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            color: white;
        }
        
        .status-paid {
            background-color: #2ecc71;
        }
        
        .status-pending {
            background-color: #f39c12;
        }
        
        .status-failed {
            background-color: #e74c3c;
        }
        
        /* 3D floating animation */
        @keyframes float {
            0% { transform: translateY(0px) rotateY(0deg); }
            50% { transform: translateY(-10px) rotateY(5deg); }
            100% { transform: translateY(0px) rotateY(0deg); }
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        @media (max-width: 767px) {
            .itm {
                max-width: 350px;
            }
            
            .table th, .table td {
                padding: 8px 10px;
                font-size: 0.9rem;
            }
            
            h2 {
                font-size: 1.5rem;
            }
            
            .log {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="logo">Food <b style="color: var(--primary-color);">Donate</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="delivery.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="openmap.php"><i class="fas fa-map-marked-alt"></i> Map</a></li>
            <li><a href="deliverymyord.php" class="active"><i class="fas fa-clipboard-list"></i> My Orders</a></li>
            <li><a href="logout.php"><i class="fas fa-clipboard-list-arrow-left"></i>LOGOUT</a></li>
        </ul>
    </nav>
</header>

<script>
    hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function () {
        navBar = document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    }
</script>

<div class="itm floating">
    <img src="../img/delivery.gif" alt="Delivery Animation">
</div>

<h2>Welcome <?php echo "$name"; ?></h2>

<div class="get">
    <?php
    // SQL query to fetch orders assigned to the delivery person
    $sql = "SELECT fd.Fid AS Fid, fd.name, fd.phoneno, fd.date, fd.delivery_by, fd.address as From_address, 
    ad.name AS delivery_person_name, ad.address AS To_address, fd.payment_status
    FROM food_donations fd
    LEFT JOIN admin ad ON fd.assigned_to = ad.Aid
    WHERE delivery_by = '$id'";

    // Execute the query
    $result = mysqli_query($connection, $sql);

    // Check for errors
    if (!$result) {
        die("Error executing query: " . mysqli_error($connection));
    }

    // Fetch the data as an associative array
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    ?>

    <div class="log">
        <a href="delivery.php"><i class="fas fa-arrow-left"></i> Take Orders</a>
        <p><i class="fas fa-truck"></i> Order assigned to you</p>
    </div>

    <!-- Display the orders in an HTML table -->
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th><i class="fas fa-user"></i> Name</th>
                        <th><i class="fas fa-phone"></i> Phone No</th>
                        <th><i class="fas fa-calendar-alt"></i> Date/Time</th>
                        <th><i class="fas fa-map-marker-alt"></i> Pickup Address</th>
                        <th><i class="fas fa-flag-checkered"></i> Delivery Address</th>
                        <th><i class="fas fa-money-bill-wave"></i> Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row) { 
                        // Determine status badge class
                        $statusClass = 'status-pending';
                        if ($row['payment_status'] == 'Paid') {
                            $statusClass = 'status-paid';
                        } elseif ($row['payment_status'] == 'Failed') {
                            $statusClass = 'status-failed';
                        }
                    ?>
                    <tr>
                        <td data-label="Name"><?php echo $row['name']; ?></td>
                        <td data-label="Phone No"><?php echo $row['phoneno']; ?></td>
                        <td data-label="Date/Time"><?php echo $row['date']; ?></td>
                        <td data-label="Pickup Address"><?php echo $row['From_address']; ?></td>
                        <td data-label="Delivery Address"><?php echo $row['To_address']; ?></td>
                        <td data-label="Payment Status">
                            <span class="status-badge <?php echo $statusClass; ?>">
                                <?php echo $row['payment_status']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php ob_end_flush(); ?>