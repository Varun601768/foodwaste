<?php
include '../connection.php';
$msg = 0;

if (isset($_POST['sign'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $location = $_POST['district'];

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

    $pass = password_hash($password, PASSWORD_DEFAULT);
    $sql = "SELECT * FROM delivery_persons WHERE email='$email'";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
      echo "<script>alert('Account already exists with this email!');</script>";
    } else {
        $query = "INSERT INTO delivery_persons(name, email, password, city) VALUES('$username', '$email', '$pass', '$location')";
        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            header("Location: deliverylogin.php");  // Redirect to login page after successful registration
            exit();
        } else {
            echo '<script type="text/javascript">alert("Data not saved")</script>';
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animated Register Form</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background:url("../img/bg.jpg");
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        /* Form container styles */
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

        /* Form title styling */
        .title {
            font-size: 32px;
            font-weight: bold;
            color: #06C167;
            margin-bottom: 10px;
        }

        /* Input group styles */
        .input-group {
            margin-bottom: 15px;
            position: relative;
        }

        .input-group label {
            color: white;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            outline: none;
            box-shadow: inset 3px 3px 8px rgba(0, 0, 0, 0.2),
                        inset -3px -3px 8px rgba(255, 255, 255, 0.2);
        }

        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Button styling */
        button, input[type="submit"] {
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

        /* Button hover effect */
        button:hover, input[type="submit"]:hover {
            background: linear-gradient(135deg, #049f55, #06C167);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6, 193, 103, 0.6);
        }

        /* Sign up/login link styling */
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

    </style>
</head>

<body>
    <div class="container">
        <h1 class="title">Register</h1>
        <form method="post" action="">
            <div class="input-group">
                <input type="text" name="username" required placeholder="Username" />
            </div>
            <div class="input-group">
                <input type="password" name="password" required placeholder="Password" />
            </div>
            <div class="input-group">
                <input type="email" name="email" required placeholder="Email" />
            </div>
            <div class="input-group">
                <select id="district" name="district" required>
                    <option value="mangalore">Mangalore</option>
                    <option value="bantwal">Bantwal</option>
                    <option value="puttur">Puttur</option>
                    <option value="belthangady">Belthangady</option>
                    <option value="sullia">Sullia</option>
                </select>
            </div>
            <input type="submit" name="sign" value="Register" />
            <div class="signin-up">
                Already a member? <a href="deliverylogin.php">Sign In</a>
            </div>
        </form>
    </div>
</body>

</html>
