<?php
ob_start(); 
include("connect.php"); 
if($_SESSION['name']==''){
    header("location:deliverylogin.php");
}
$name=$_SESSION['name'];
$city=$_SESSION['city'];
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,"http://ip-api.com/json");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$result=curl_exec($ch);
$result=json_decode($result);
$id=$_SESSION['Did'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard</title>
    <script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
    <link rel="stylesheet" href="../home.css">
    <link rel="stylesheet" href="delivery.css">
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
        }
        
        .log a {
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
        
        button[type="submit"], button[name="food"] {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(6, 193, 103, 0.3);
            transform: translateZ(0);
        }
        
        button[type="submit"]:hover, button[name="food"]:hover {
            transform: translateY(-3px) translateZ(10px);
            box-shadow: 0 8px 20px rgba(6, 193, 103, 0.4);
            background: #05a85c;
        }
        
        @media (max-width: 767px) {
            .itm {
                max-width: 350px;
            }
            
            .table th, .table td {
                padding: 8px 10px;
            }
            
            h2 {
                font-size: 1.5rem;
            }
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
        
        /* Pulse animation for new orders */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(6, 193, 103, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(6, 193, 103, 0); }
            100% { box-shadow: 0 0 0 0 rgba(6, 193, 103, 0); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        /* Status indicators */
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .status.assigned {
            background: #f39c12;
            color: white;
        }
        
        .status.yours {
            background: #2ecc71;
            color: white;
        }
        
        .status.taken {
            background: #e74c3c;
            color: white;
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
                <li><a href="#home" class="active"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="openmap.php"><i class="fas fa-map-marked-alt"></i> Map</a></li>
                <li><a href="deliverymyord.php"><i class="fas fa-clipboard-list"></i> My Orders</a></li>
                <li><a href="logout.php"><i class="fas fa-clipboard-list-arrow-left"></i>LOGOUT</a></li>
            </ul>
        </nav>
    </header>
    <br>
    <script>
        hamburger=document.querySelector(".hamburger");
        hamburger.onclick =function(){
            navBar=document.querySelector(".nav-bar");
            navBar.classList.toggle("active");
        }
    </script>

    <h2>Welcome <?php echo"$name";?></h2>

    <div class="itm floating">
        <img src="../img/delivery.gif" alt="Delivery Animation"> 
    </div>
    
    <div class="log">
        <a href="deliverymyord.php" class="pulse"><i class="fas fa-truck"></i> My Orders</a>
    </div>
  
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Date/Time</th>
                        <th>Pickup Address</th>
                        <th>Delivery Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sql = "SELECT fd.Fid AS Fid,fd.location as cure, fd.name,fd.phoneno,fd.date,fd.delivery_by, fd.address as From_address, 
                    ad.name AS delivery_person_name, ad.address AS To_address
                    FROM food_donations fd
                    LEFT JOIN admin ad ON fd.assigned_to = ad.Aid where assigned_to IS NOT NULL and delivery_by IS NULL and fd.location='$city'";
                    
                    $result=mysqli_query($connection, $sql);
                    
                    if (!$result) {
                        die("Error executing query: " . mysqli_error($connection));
                    }
                    
                    while ($row = mysqli_fetch_assoc($result)) { 
                        echo "<tr>
                            <td data-label=\"name\">".$row['name']."</td>
                            <td data-label=\"phoneno\">".$row['phoneno']."</td>
                            <td data-label=\"date\">".$row['date']."</td>
                            <td data-label=\"Pickup Address\">".$row['From_address']."</td>
                            <td data-label=\"Delivery Address\">".$row['To_address']."</td>
                            <td data-label=\"Action\">";
                            
                            if ($row['delivery_by'] == null) {
                                echo "<form method=\"post\" action=\" \">
                                    <input type=\"hidden\" name=\"order_id\" value=\"".$row['Fid']."\">
                                    <input type=\"hidden\" name=\"delivery_person_id\" value=\"".$id."\">
                                    <button type=\"submit\" name=\"food\"><i class=\"fas fa-hand-paper\"></i> Take Order</button>
                                </form>";
                            } else if ($row['delivery_by'] == $id) {
                                echo "<span class=\"status yours\"><i class=\"fas fa-check-circle\"></i> Assigned to you</span>";
                            } else {
                                echo "<span class=\"status taken\"><i class=\"fas fa-times-circle\"></i> Already taken</span>";
                            }
                            
                        echo "</td></tr>";
                    } 
                    
                    if (isset($_POST['food']) && isset($_POST['delivery_person_id'])) {
                        $order_id = $_POST['order_id'];
                        $delivery_person_id = $_POST['delivery_person_id'];
                        $sql = "SELECT * FROM food_donations WHERE Fid = $order_id AND delivery_by IS NOT NULL";
                        $result = mysqli_query($connection, $sql);
                    
                        if (mysqli_num_rows($result) > 0) {
                            echo "<script>alert('Sorry, this order has already been assigned to someone else.');</script>";
                        } else {
                            $sql = "UPDATE food_donations SET delivery_by = $delivery_person_id WHERE Fid = $order_id";
                            $result = mysqli_query($connection, $sql);
                    
                            if (!$result) {
                                die("Error assigning order: " . mysqli_error($connection));
                            }
                            
                            echo "<script>alert('Order successfully assigned to you!'); window.location.href=window.location.href;</script>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php ob_end_flush(); ?>