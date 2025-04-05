<?php // Ensure session is started
include("login.php"); 

if (!isset($_SESSION['name']) || $_SESSION['name'] == '') {
    header("location: signin.php");
    exit();
}

$emailid = $_SESSION['email'];
$connection = mysqli_connect("localhost", "root", "", "demo"); // Database connection

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $foodnames = $_POST['foodname']; // Array of food names
    $quantities = $_POST['quantity']; // Array of quantities
    
    $meal = mysqli_real_escape_string($connection, $_POST['meal']);
    $category = mysqli_real_escape_string($connection, $_POST['category']);
    $phoneno = mysqli_real_escape_string($connection, $_POST['phoneno']);
    $district = mysqli_real_escape_string($connection, $_POST['district']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $name = mysqli_real_escape_string($connection, $_POST['name']);

    $query_success = true; // Track query execution status

    foreach ($foodnames as $index => $foodname) {
        $foodname = mysqli_real_escape_string($connection, $foodname);
        $quantity = mysqli_real_escape_string($connection, $quantities[$index]); // Get corresponding quantity

        $query = "INSERT INTO food_donations (email, food, type, category, phoneno, location, address, name, quantity) 
                  VALUES ('$emailid', '$foodname', '$meal', '$category', '$phoneno', '$district', '$address', '$name', '$quantity')";

        if (!mysqli_query($connection, $query)) {
            $query_success = false;
        }
    }

    if ($query_success) {
        echo '<script>alert("Data saved successfully!");</script>';
        header("location: delivery.html");
        exit();
    } else {
        echo '<script>alert("Data not saved. Please try again!");</script>';
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donation Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            perspective: 1000px;
            padding: 20px;
        }

        /* 3D Container */
        .container {
            width: 100%;
            max-width: 800px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            padding: 40px;
            transform-style: preserve-3d;
            transform: translateZ(30px) rotateX(5deg);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .container:hover {
            transform: translateZ(50px) rotateX(0deg);
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.2);
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(to right, #06C167, #04a858);
        }

        /* 3D Form Title */
        .form-title {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            transform: translateZ(40px);
            position: relative;
        }

        .form-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%) translateZ(20px);
            width: 100px;
            height: 4px;
            background: #06C167;
            border-radius: 2px;
        }

        /* 3D Form Elements */
        form {
            display: flex;
            flex-direction: column;
            gap: 25px;
            transform-style: preserve-3d;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            transform-style: preserve-3d;
        }

        label {
            font-weight: 600;
            color: #555;
            transform: translateZ(20px);
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select,
        textarea {
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            transform-style: preserve-3d;
            transform: translateZ(15px);
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        select:focus,
        textarea:focus {
            border-color: #06C167;
            outline: none;
            box-shadow: 0 0 0 3px rgba(6, 193, 103, 0.2);
            transform: translateZ(25px);
        }

        /* Radio Buttons with 3D Effect */
        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
            transform-style: preserve-3d;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            transform: translateZ(15px);
            cursor: pointer;
        }

        input[type="radio"] {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ddd;
            border-radius: 50%;
            transition: all 0.3s ease;
            position: relative;
            transform-style: preserve-3d;
            cursor: pointer;
        }

        input[type="radio"]:checked {
            border-color: #06C167;
            background: #06C167;
            box-shadow: 0 0 0 2px white, 0 0 0 4px #06C167;
            transform: translateZ(20px);
        }

        /* 3D Buttons */
        button {
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            transform-style: preserve-3d;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        button[type="submit"] {
            background: #06C167;
            color: white;
            margin-top: 20px;
            transform: translateZ(30px);
            box-shadow: 0 10px 25px rgba(6, 193, 103, 0.4);
        }

        button[type="submit"]:hover {
            background: #04a858;
            transform: translateZ(40px) scale(1.05);
            box-shadow: 0 15px 35px rgba(6, 193, 103, 0.5);
        }

        .add-food-button {
            background: #f8f9fa;
            color: #06C167;
            border: 2px solid #06C167;
            transform: translateZ(25px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .add-food-button:hover {
            background: #06C167;
            color: white;
            transform: translateZ(35px);
            box-shadow: 0 10px 25px rgba(6, 193, 103, 0.3);
        }

        /* Dynamic Food Fields */
        #food-container {
            display: flex;
            flex-direction: column;
            gap: 25px;
            transform-style: preserve-3d;
        }

        #food-container .form-group {
            background: rgba(6, 193, 103, 0.05);
            padding: 20px;
            border-radius: 15px;
            border-left: 4px solid #06C167;
            transform: translateZ(20px);
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        #food-container .form-group:hover {
            transform: translateZ(25px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
                transform: translateZ(20px) rotateX(0deg);
            }

            .form-title {
                font-size: 2rem;
            }

            .radio-group {
                flex-direction: column;
                gap: 10px;
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

        .container {
            animation: floatIn 0.8s ease-out forwards;
        }
    </style>
</head>
<body>
    <div class="container">
        <p class="form-title">Food Donation Form</p>
        <form action="" method="post">
            <!-- Food Name (Dynamic Fields) -->
            <div id="food-container">
                <div class="form-group">
                    <label>Food Name:</label>
                    <input type="text" name="foodname[]" required />
                    <label>Quantity (Number of persons/kg):</label>
                    <input type="text" name="quantity[]" required />
                </div>
            </div>
            <button type="button" class="add-food-button" onclick="addFoodField()">
                <i class="fa fa-plus"></i> Add More Food
            </button>

            <!-- Meal Type -->
            <div class="form-group">
                <label>Meal Type:</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="meal" id="veg" value="veg" required />
                        Veg
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="meal" id="non-veg" value="non-veg" />
                        Non-Veg
                    </label>
                </div>
            </div>

            <div class="form-group">
        <label for="food">Select the Category:</label>
        <div class="image-radio-group">
            <input type="radio" id="raw-food" name="category" value="raw-food">
            <label for="raw-food">
              <img src="img/raw-food.png" alt="raw-food" height="70px" >
            </label>
            <input type="radio" id="cooked-food" name="category" value="cooked-food"checked>
            <label for="cooked-food">
              <img src="img/cooked-food.png" alt="cooked-food" height="70px" >
            </label>
            <input type="radio" id="packed-food" name="category" value="packed-food">
            <label for="packed-food">
              <img src="img/packed-food.png" alt="packed-food" height="70px">
            </label>
          </div>
          <br>
        <!-- <input type="text" id="food" name="food"> -->
        </div>

            <!-- Other fields -->
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" required />
            </div>
            <div class="form-group">
                <label for="phoneno">Phone Number:</label>
                <input type="tel" id="phoneno" name="phoneno" maxlength="10" pattern="[0-9]{10}" required />
            </div>
            <div class="form-group">
                <label for="district">Taluk:</label>
                <select id="district" name="district" required>
                    <option value="mangalore">Mangalore</option>
                    <option value="bantwal">Bantwal</option>
                    <option value="puttur">Puttur</option>
                    <option value="belthangady">Belthangady</option>
                    <option value="sullia">Sullia</option>
                </select>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required />
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" name="submit">
                    <i class="fa fa-paper-plane"></i> Submit Donation
                </button>
            </div>
        </form>
    </div>

    <script>
        function addFoodField() {
            const foodContainer = document.getElementById('food-container');
            const foodField = document.createElement('div');
            foodField.classList.add('form-group');

            foodField.innerHTML = `
                <label>Food Name:</label>
                <input type="text" name="foodname[]" required />
                <label>Quantity (Number of persons/kg):</label>
                <input type="text" name="quantity[]" required />
            `;
            foodContainer.appendChild(foodField);
            
            // Add animation to new field
            foodField.style.opacity = '0';
            foodField.style.transform = 'translateY(20px) translateZ(-20px)';
            setTimeout(() => {
                foodField.style.transition = 'all 0.4s ease-out';
                foodField.style.opacity = '1';
                foodField.style.transform = 'translateY(0) translateZ(20px)';
            }, 10);
        }
    </script>
</body>
</html>