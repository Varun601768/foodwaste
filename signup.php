<?php
include 'connection.php';

if (isset($_POST['sign'])) {
    $username = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $gender = $_POST['gender'];

    // Validate username: Only letters, spaces, and underscores are allowed (no numbers)
    if (!preg_match("/^[a-zA-Z_ ]{3,20}$/", $username)) {
        echo "<script>alert('Invalid username! Only 3-20 characters, letters, spaces, and underscores are allowed. Numbers are not permitted.');</script>";
        exit();
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!');</script>";
        exit();
    }

    // Validate password (optional: add stronger rules)
    if (strlen($password) < 6) {
        echo "<script>alert('Password must be at least 6 characters long!');</script>";
        exit();
    }

    // Check if email already exists
    $sql = "SELECT * FROM login WHERE email='$email'";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        echo "<script>alert('Account already exists with this email!');</script>";
    } else {
        $pass = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO login (name, email, password, gender) VALUES ('$username', '$email', '$pass', '$gender')";
        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            header("location:signin.php?status=success");
        } else {
            echo '<script>alert("Data not saved. Please try again.");</script>';
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - 3D Design</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background:url("img/bg.jpg");
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #06C167;
            margin-bottom: 10px;
        }

        .input-group {
            margin-bottom: 15px;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            outline: none;
            box-shadow: inset 3px 3px 8px rgba(0, 0, 0, 0.2), inset -3px -3px 8px rgba(255, 255, 255, 0.2);
        }

        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            font-size: 18px;
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background: linear-gradient(135deg, #06C167, #049f55);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(6, 193, 103, 0.4);
        }

        button:hover {
            background: linear-gradient(135deg, #049f55, #06C167);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6, 193, 103, 0.6);
        }

        .signin-up {
            font-size: 14px;
            margin-top: 15px;
            color: white;
        }

        .signin-up a {
            color: #06C167;
            text-decoration: none;
            font-weight: 600;
        }

        .signin-up a:hover {
            text-decoration: underline;
        }
        .radio-group {
    display: flex;
    gap: 15px; /* Adds space between the radio buttons */
    align-items: center;
}

.radio-group label {
    display: flex;
    align-items: center;
    font-size: 14px;
    color: #333;
}

.radio-group input {
    margin-right: 5px; 
    /* Space between the radio button and label text */
}
.radio-group #nn{
    color:white;
}
.radio-group {
    display: flex;
    gap: 20px; /* Space between the radio buttons */
    align-items: center;
    justify-content: center;
    margin-top: 5px;
}

.radio-group label {
    display: flex;
    align-items: center;
    font-size: 16px;
    color: white; /* Makes the text visible on the dark background */
    cursor: pointer;
}

.radio-group input {
    margin-right: 8px; /* Space between the radio button and label text */
    transform: scale(1.2); /* Increases size for better visibility */
    accent-color: #06C167; /* Changes color to match theme */
}

/* Ensure label visibility */
#nn {
    color: white;
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;
}



    </style>
</head>
<body>
    <div class="container">
        <p class="logo">Food <b style="color: #04a856;">Donate</b></p>
        <p id="heading">Create your account</p>
        <form action="" method="post">
            <div class="input-group">
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="input-group">
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
    <label id="nn">Gender</label>
    <div class="radio-group">
        <label><input type="radio" name="gender" value="male" required> Male</label>
        <label><input type="radio" name="gender" value="female"> Female</label>
    </div>
</div>


            <div class="input-group password-wrapper">
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <i class="fa-solid fa-eye-slash toggle-password" id="togglePassword"></i>
            </div>
            <button type="submit" name="sign">Signup</button>
            <div class="signin-up">
                <p>Already have an account? <a href="signin.php">Sign in</a></p>
            </div>
        </form>
    </div>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            togglePassword.classList.toggle('fa-eye');
            togglePassword.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>