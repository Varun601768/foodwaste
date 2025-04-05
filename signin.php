<?php
session_start();
include 'connection.php';
$msg = 0; // Initialize $msg to avoid undefined variable warning

if (isset($_POST['sign'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    
    $sql = "SELECT * FROM login WHERE email='$email'";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);
    
    if ($num == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $row['name'];
                $_SESSION['gender'] = $row['gender'];
                header("location:home.html");
                exit();
            } else {
                $msg = 1; // Set $msg if password doesn't match
            }
        }
    } else {
        echo "<script>alert('Account does not exist');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <style>
       /* Apply box-sizing universally */
* {
    box-sizing: border-box;
}

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
    gap: 15px;
    align-items: center;
    justify-content: center;
    margin-top: 5px;
}

.radio-group label {
    display: flex;
    align-items: center;
    font-size: 16px;
    color: white;
    cursor: pointer;
}

.radio-group input {
    margin-right: 8px;
    transform: scale(1.2);
    accent-color: #06C167;
}

#nn {
    color: white;
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;
}
.input-group {
    margin-bottom: 15px;
    position: relative;
}

.input-group input {
    width: 100%;
    padding: 12px 20px; /* Added padding to left and right for better spacing */
    border: none;
    border-radius: 10px;
    font-size: 14px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    outline: none;
    transition: all 0.3s ease;
    box-shadow: inset 3px 3px 8px rgba(0, 0, 0, 0.2), inset -3px -3px 8px rgba(255, 255, 255, 0.2);
}

.input-group input:focus {
    border-color: #06C167; /* Green color for focus state */
    box-shadow: 0 0 5px rgba(6, 193, 103, 0.6); /* Soft green glow */
}

.input-group input::placeholder {
    color: rgba(255, 255, 255, 0.6); /* Lighter placeholder text */
    /* Make placeholder text look distinct */
}

.input-group input:hover {
    border-color: #049f55; /* Darker green on hover */
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

    </style>
</head>

<body>
<div class="container">
    <div class="regform">
        <form action="" method="post">
            <p class="logo">Food <b style="color:#06C167;">Donate</b></p>
            <p id="heading">Welcome back!</p>

            <!-- Email Input Group -->
            <div class="input-group">
                <input type="email" placeholder="Email address" name="email" required />
            </div>

            <!-- Password Input Group -->
            <div class="input-group password">
                <input type="password" placeholder="Password" name="password" id="password" required />
               
                <?php
                if ($msg == 1) {
                    echo '<p class="error">Password does not match.</p>';
                }
                ?>
            </div>

            <!-- Sign In Button -->
            <div class="btn">
                <button type="submit" name="sign">Sign in</button>
            </div>

            <!-- Sign Up Link -->
            <div class="signin-up">
                <p>Don't have an account? <a href="signup.php">Register</a></p>
            </div>
        </form>
    </div>
</div>


    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.querySelector('.showHidePw');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.classList.toggle('uil-eye');
            togglePassword.classList.toggle('uil-eye-slash');
        });
    </script>
</body>

</html>

