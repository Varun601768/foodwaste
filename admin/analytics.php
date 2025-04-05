<?php
include("connect.php");

// Fetch data for charts
$query_male = "SELECT COUNT(*) as count FROM login WHERE gender='male'";
$query_female = "SELECT COUNT(*) as count FROM login WHERE gender='female'";
$query_puttur = "SELECT COUNT(*) as count FROM food_donations WHERE location='Puttur'";
$query_sulya = "SELECT COUNT(*) as count FROM food_donations WHERE location='Sulya'";
$query_vitla = "SELECT COUNT(*) as count FROM food_donations WHERE location='Vitla'";

$result_male = mysqli_query($connection, $query_male);
$result_female = mysqli_query($connection, $query_female);
$result_puttur = mysqli_query($connection, $query_puttur);
$result_sulya = mysqli_query($connection, $query_sulya);
$result_vitla = mysqli_query($connection, $query_vitla);

$male = mysqli_fetch_assoc($result_male)['count'];
$female = mysqli_fetch_assoc($result_female)['count'];
$puttur = mysqli_fetch_assoc($result_puttur)['count'];
$sulya = mysqli_fetch_assoc($result_sulya)['count'];
$vitla = mysqli_fetch_assoc($result_vitla)['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Admin Dashboard</title>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>
<style>
    /* Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #f5f7fa;
    color: #333;
    overflow-x: hidden;
}

/* Sidebar (3D Floating Effect) */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100vh;
    background: linear-gradient(145deg, #2c3e50, #34495e);
    box-shadow: 10px 0 20px rgba(0, 0, 0, 0.3);
    transform-style: preserve-3d;
    transition: all 0.3s ease;
    z-index: 100;
}

.sidebar .logo {
    padding: 20px;
    color: #fff;
    text-align: center;
    font-size: 1.5rem;
    font-weight: 600;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.nav-links {
    padding: 20px;
}

.nav-links li {
    list-style: none;
    margin-bottom: 10px;
}

.nav-links li a {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    color: #fff;
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

/* Main Content */
.main-content {
    margin-left: 250px;
    padding: 20px;
    transition: all 0.3s ease;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.toggle-sidebar {
    font-size: 1.5rem;
    cursor: pointer;
}

.logo-text {
    font-size: 1.5rem;
    font-weight: 600;
}

.logo-text span {
    color: #06C167;
}

.user-profile span {
    font-weight: 500;
}

/* Stats Boxes (3D Neumorphic) */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-box {
    background: linear-gradient(145deg, #ffffff, #f0f0f0);
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    transition: all 0.3s ease;
}

.box-3d {
    box-shadow: 
        8px 8px 15px rgba(0, 0, 0, 0.1),
        -5px -5px 10px rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-box:hover {
    transform: translateY(-5px) rotateX(5deg);
    box-shadow: 
        12px 12px 20px rgba(0, 0, 0, 0.15),
        -8px -8px 15px rgba(255, 255, 255, 0.9);
}

.stat-box i {
    font-size: 2.5rem;
    margin-bottom: 15px;
    color: #06C167;
}

.stat-title {
    display: block;
    font-size: 1rem;
    color: #666;
    margin-bottom: 10px;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
}

/* Charts (3D Styled) */
.charts-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.chart-box {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .sidebar {
        width: 70px;
        overflow: hidden;
    }
    .sidebar .logo span,
    .nav-links li a span {
        display: none;
    }
    .main-content {
        margin-left: 70px;
    }
    .charts-container {
        grid-template-columns: 1fr;
    }
}
</style>
<body>
    <!-- Sidebar Navigation -->
    <nav class="sidebar">
        <div class="logo">
            <span>ADMIN PANEL</span>
        </div>
        <ul class="nav-links">
            <li><a href="admin.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-chart-line"></i> Analytics</a></li>
            <li><a href="donate.php"><i class="fas fa-heart"></i> Donations</a></li>
            <li><a href="feedback.php"><i class="fas fa-comments"></i> Feedbacks</a></li>
            <li><a href="adminprofile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <div class="toggle-sidebar">
                <i class="fas fa-bars"></i>
            </div>
            <div class="logo-text">Food <span>Donate</span></div>
            <div class="user-profile">
                <span>Welcome, <?php echo $_SESSION['name']; ?></span>
            </div>
        </div>

        <!-- Dashboard Stats -->
        <div class="stats-container">
            <div class="stat-box box-3d">
                <i class="fas fa-users"></i>
                <span class="stat-title">Total Users</span>
                <?php
                    $query = "SELECT COUNT(*) as count FROM login";
                    $result = mysqli_query($connection, $query);
                    $row = mysqli_fetch_assoc($result);
                    echo "<span class='stat-value'>" . $row['count'] . "</span>";
                ?>
            </div>
            <div class="stat-box box-3d">
                <i class="fas fa-comment-dots"></i>
                <span class="stat-title">Feedbacks</span>
                <?php
                    $query = "SELECT COUNT(*) as count FROM user_feedback";
                    $result = mysqli_query($connection, $query);
                    $row = mysqli_fetch_assoc($result);
                    echo "<span class='stat-value'>" . $row['count'] . "</span>";
                ?>
            </div>
            <div class="stat-box box-3d">
                <i class="fas fa-utensils"></i>
                <span class="stat-title">Total Donations</span>
                <?php
                    $query = "SELECT COUNT(*) as count FROM food_donations";
                    $result = mysqli_query($connection, $query);
                    $row = mysqli_fetch_assoc($result);
                    echo "<span class='stat-value'>" . $row['count'] . "</span>";
                ?>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-container">
            <div class="chart-box box-3d">
                <canvas id="genderChart"></canvas>
            </div>
            <div class="chart-box box-3d">
                <canvas id="donationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>// Toggle Sidebar
document.querySelector('.toggle-sidebar').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('collapsed');
    document.querySelector('.main-content').classList.toggle('expanded');
});

// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    // Gender Distribution Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'bar',
        data: {
            labels: ['Male', 'Female'],
            datasets: [{
                data: [<?= $male ?>, <?= $female ?>],
                backgroundColor: ['#06C167', '#3A7BFF'],
                borderColor: ['#06C167', '#3A7BFF'],
                borderWidth: 1,
                borderRadius: 6,
                hoverBackgroundColor: ['#05A357', '#2D6BFF'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'User Gender Distribution',
                    font: { size: 16 }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    cornerRadius: 6,
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.1)' } },
                x: { grid: { display: false } }
            },
        }
    });

    // Donation Location Chart
    const donationCtx = document.getElementById('donationChart').getContext('2d');
    new Chart(donationCtx, {
        type: 'bar',
        data: {
            labels: ['Puttur', 'Sulya', 'Vitla'],
            datasets: [{
                data: [<?= $puttur ?>, <?= $sulya ?>, <?= $vitla ?>],
                backgroundColor: ['#06C167', '#3A7BFF', '#FF6384'],
                borderColor: ['#06C167', '#3A7BFF', '#FF6384'],
                borderWidth: 1,
                borderRadius: 6,
                hoverBackgroundColor: ['#05A357', '#2D6BFF', '#E55373'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Food Donations by Location',
                    font: { size: 16 }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    cornerRadius: 6,
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.1)' } },
                x: { grid: { display: false } }
            },
        }
    });
});</script>
</body>
</html>