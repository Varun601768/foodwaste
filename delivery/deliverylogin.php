<?php
session_start();
// $connection = mysqli_connect("localhost:3307", "root", "");
// $db = mysqli_select_db($connection, 'demo');
include '../connection.php'; 
$msg=0;
if (isset($_POST['sign'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $sanitized_emailid =  mysqli_real_escape_string($connection, $email);
  $sanitized_password =  mysqli_real_escape_string($connection, $password);
  // $hash=password_hash($password,PASSWORD_DEFAULT);

  $sql = "select * from delivery_persons where email='$sanitized_emailid'";
  $result = mysqli_query($connection, $sql);
  $num = mysqli_num_rows($result);
 
  if ($num == 1) {
    while ($row = mysqli_fetch_assoc($result)) {
      if (password_verify($sanitized_password, $row['password'])) {
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $row['name'];
        $_SESSION['Did']=$row['Did'];
        $_SESSION['city']=$row['city'];
        header("location:delivery.php");
      } else {
        $msg = 1;
        // echo '<style type="text/css">
        // {
        //     .password input{
                
        //         border:.5px solid red;
                
                
        //       }

        // }
        // </style>';
        // echo "<h1><center> Login Failed incorrect password</center></h1>";
      }
    }
  } else {
    echo "<script>alert('Account does not exist');</script>";
  }




  // $query="select * from login where email='$email'and password='$password'";
  // $qname="select name from login where email='$email'and password='$password'";


  // if(mysqli_num_rows($query_run)==1)
  // {
  // //   $_SESSION['name']=$name;

  //   // echo "<h1><center> Login Sucessful  </center></h1>". $name['gender'] ;

  //   $_SESSION['email']=$email;
  //   $_SESSION['name']=$name['name'];
  //   $_SESSION['gender']=$name['gender'];
  //   header("location:home.html");

  // }
  // else{
  //   echo "<h1><center> Login Failed</center></h1>";
  // }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Delivery Login</title>
    <style>
        /* Global Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background:url("../img/bg.jpg"); /* Gradient background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        /* Form container styles */
        .center {
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
        h1 {
            font-size: 32px;
            font-weight: bold;
            color: #06C167;
            margin-bottom: 10px;
        }

        /* Input group styles */
        .txt_field {
            position: relative;
            margin-bottom: 20px;
        }

        .txt_field input {
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

        .txt_field label {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            transition: 0.3s;
        }

        .txt_field input:focus ~ label,
        .txt_field input:valid ~ label {
            top: -10px;
            font-size: 12px;
            color: #06C167;
        }

        /* Button styling */
        input[type="submit"] {
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
        input[type="submit"]:hover {
            background: linear-gradient(135deg, #049f55, #06C167);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(6, 193, 103, 0.6);
        }

        /* Error message styling */
        .error {
            color: red;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 10px;
        }

        /* Sign up/login link styling */
        .signup_link {
            font-size: 14px;
            margin-top: 15px;
            color: white;
        }

        .signup_link a {
            color: #06C167;
            text-decoration: none;
            font-weight: 600;
        }

        .signup_link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="center">
        <h1>Delivery Login</h1>
        <form method="post">
            <div class="txt_field">
                <input type="email" name="email" required/>
                <label>Email</label>
            </div>
            <div class="txt_field">
                <input type="password" name="password" required/>
                <label>Password</label>
            </div>
            <?php
            if(isset($msg) && $msg == 1){
                echo '<p class="error">Password not match.</p>';
            }
            ?>
            <input type="submit" value="Login" name="sign">
            <div class="signup_link">
                Not a member? <a href="deliverysignup.php">Signup</a>
            </div>
        </form>
    </div>
</body>
</html>
