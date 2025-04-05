<?php
session_start();
include '../connection.php';

// Check admin authentication
if(empty($_SESSION['name'])){
    header("location:signin.php");
    exit();
}

// Fetch orders taken by delivery boys
$query = "
SELECT fd.Fid, fd.name AS donor_name, fd.date, fd.address AS pickup_address, 
       ad.name AS delivery_person_name, fd.delivery_by, fd.payment_status 
FROM food_donations fd
LEFT JOIN admin ad ON fd.assigned_to = ad.Aid
WHERE fd.delivery_by IS NOT NULL";

$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Delivery Orders</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            perspective: 1000px;
        }
        
        h1 {
            color: var(--primary-color);
            margin-bottom: 30px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* 3D Table Styling */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            box-shadow: var(--shadow-dark);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 30px;
            transform-style: preserve-3d;
            transform: rotateX(1deg);
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background: var(--primary-color);
            color: white;
            font-weight: 500;
            position: sticky;
            top: 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        tr:hover {
            background: #f1f1f1;
            transform: scale(1.01);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        /* 3D Button Styling */
        button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            box-shadow: 0 3px 6px rgba(6, 193, 103, 0.2);
            transition: all 0.3s ease;
        }
        
        button:hover {
            background: #05a357;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(6, 193, 103, 0.3);
        }
        
        /* 3D Back Link */
        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background: var(--secondary-color);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            box-shadow: 0 3px 6px rgba(58, 123, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            background: #2d6bff;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(58, 123, 255, 0.3);
        }
        
        /* Payment Status Badges */
        .status-paid {
            color: #06C167;
            font-weight: 600;
        }
        
        .status-unpaid {
            color: #FF6384;
            font-weight: 600;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
            }
            
            th, td {
                padding: 10px 8px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-truck"></i> Delivery Orders</h1>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Donor Name</th>
                        <th>Date</th>
                        <th>Pickup Address</th>
                        <th>Delivery Person</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['Fid']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['donor_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['pickup_address']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['delivery_person_name']) . "</td>";
                            
                            // Payment status with colored badge
                            if ($row['payment_status'] == 'Paid') {
                                echo "<td><span class='status-paid'><i class='fas fa-check-circle'></i> Paid</span></td>";
                            } else {
                                echo "<td><span class='status-unpaid'><i class='fas fa-times-circle'></i> Unpaid</span></td>";
                            }
                            
                            echo "<td>";
                            if ($row['payment_status'] == 'Unpaid') {
                                echo "<form method='POST' action='mark_payment.php'>";
                                echo "<input type='hidden' name='order_id' value='" . htmlspecialchars($row['Fid']) . "'>";
                                echo "<button type='submit'><i class='fas fa-money-bill-wave'></i> Mark as Paid</button>";
                                echo "</form>";
                            } else {
                                echo "<span class='status-paid'><i class='fas fa-check'></i> Completed</span>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No delivery orders found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <a href="admin.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <script>
        // Add animation to table rows
        document.querySelectorAll('tbody tr').forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            row.style.transition = `all 0.3s ease ${index * 0.05}s`;
            
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>