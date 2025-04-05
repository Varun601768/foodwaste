<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../connection.php';

if (empty($_SESSION['name'])) {
    header("location:signin.php");
    exit;
}

$query = "SELECT * FROM user_feedback";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Iconscout CSS -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <title>Admin Feedback</title>
    
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
            padding: 30px;
            transition: all 0.3s ease;
            min-height: 100vh;
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
        
        .activity {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-dark);
            padding: 20px;
            transform-style: preserve-3d;
        }
        
        /* 3D Table */
        .table-container {
            width: 100%;
            overflow-x: auto;
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
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .table th {
            background: var(--primary-color);
            color: white;
            font-weight: 500;
            position: sticky;
            top: 0;
        }
        
        .table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .table tr:hover {
            background: #f1f1f1;
            transform: scale(1.01);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        /* Message column styling */
        .table td:nth-child(3) {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
                padding: 15px;
            }
            
            .table th, .table td {
                padding: 10px 8px;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 480px) {
            .table td:nth-child(3) {
                max-width: 150px;
            }
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
                <li><a href="donate.php">
                    <i class="uil uil-heart"></i>
                    <span class="link-name">Donates</span>
                </a></li>
                <li><a href="feedback.php" class="active">
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

                
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="activity">
            <h2 style="margin-bottom: 20px; color: var(--primary-color);">User Feedback</h2>
            <div class="table-container">
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td title="<?php echo htmlspecialchars($row['message']); ?>">
                                        <?php echo htmlspecialchars($row['message']); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Toggle sidebar
        document.querySelector('.sidebar-toggle')?.addEventListener('click', function() {
            document.querySelector('nav').classList.toggle('collapsed');
            document.querySelector('.dashboard').classList.toggle('expanded');
        });
        
        // Dark mode toggle
        document.querySelector('.mode-toggle')?.addEventListener('click', function() {
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
        
        // Add active class to current page link
        const currentPage = window.location.pathname.split('/').pop();
        document.querySelectorAll('.nav-links li a').forEach(link => {
            if(link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>