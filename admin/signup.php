<?php
// session_start();
// $connection=mysqli_connect("localhost:3307","root","");
// $db=mysqli_select_db($connection,'demo');
include '../connection.php';
$msg=0;
if(isset($_POST['sign']))
{

    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];

    $location=$_POST['district'];
    $address=$_POST['address'];

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
    $pass=password_hash($password,PASSWORD_DEFAULT);
    $sql="select * from admin where email='$email'" ;
    $result= mysqli_query($connection, $sql);
    $num=mysqli_num_rows($result);
    if($num==1){
        // echo "<h1> already account is created </h1>";
        // echo '<script type="text/javascript">alert("already Account is created")</script>';
        echo "<script>alert('Account already exists with this email!');</script>";
    }
    else{
    
    $query="insert into admin(name,email,password,location,address) values('$username','$email','$pass','$location','$address')";
    $query_run= mysqli_query($connection, $query);
    if($query_run)
    {
        // $_SESSION['email']=$email;
        // $_SESSION['name']=$row['name'];
        // $_SESSION['gender']=$row['gender'];
       
        header("location:signin.php");
        // echo "<h1><center>Account does not exists </center></h1>";
        //  echo '<script type="text/javascript">alert("Account created successfully")</script>'; -->
    }
    else{
        echo '<script type="text/javascript">alert("data not saved")</script>';
        
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
   
    <script src="signin.js" defer></script>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Register</title>
  
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background:url("../img/bg.jpg");
        }

        /* Container styling */
        .container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 10px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 600px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .title {
            font-size: 28px;
            font-weight: bold;
            color: #06C167;
            margin-bottom: 10px;
        }

        /* Input Group */
        .input-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .input-group label {
            font-size: 14px;
            color: white;
            display: block;
            margin-bottom: 5px;
        }
       
      
        .input-group input,
        .input-group textarea,
        .input-group select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            background: rgba(238, 233, 233, 0.2);
            color: white;
            outline: none;
        }

        .input-group textarea {
            resize: none;
            height: 80px;
        }

        .input-group select {
            appearance: none;
            cursor: pointer;
        }

        /* Radio Group */
        .radio-group {
            text-align: left;
            color: white;
            margin-bottom: 15px;
        }

        .radio-group label {
            display: inline-block;
            margin-right: 10px;
        }

        .radio-group input {
            margin-right: 5px;
        }

        /* Button */
        button {
            width: 100%;
            padding: 12px;
            background: #06C167;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #049f55;
            transform: translateY(-2px);
        }

        /* Sign-in Link */
        .signin-up {
            margin-top: 10px;
            font-size: 14px;
            color: white;
        }

        .signin-up a {
            color: #06C167;
            font-weight: bold;
            text-decoration: none;
        }

        .signin-up a:hover {
            text-decoration: underline;
        }
   
    </style>
</head>
<body>
    <div class="container">
        <form action=" " method="post" id="form">
            <span class="title">Register</span>
            <br><br>

            <div class="input-group">
                <label for="username">Name</label>
                <input type="text" id="username" name="username" required placeholder="Enter your full name" />
            </div>
            
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email" />
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required placeholder="Enter your password" />
            </div>

            <div class="input-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" required placeholder="Enter your address"></textarea>
            </div>
            
            <div class="input-group">
                <label for="district">Location</label>
                <select id="district" name="district" required>
                    <option value="mangalore">Mangalore</option>
                    <option value="bantwal">Bantwal</option>
                    <option value="puttur">Puttur</option>
                    <option value="belthangady">Belthangady</option>
                    <option value="sullia">Sullia</option>
                </select>
            </div>

            <div class="radio-group">
                <label for="gender">Gender</label>
                <label>
                    <input type="radio" name="gender" value="male" required />
                    Male
                </label>
                <label>
                    <input type="radio" name="gender" value="female" required />
                    Female
                </label>
            </div>
            
            <button type="submit" name="sign">Register</button>
            
            <div class="signin-up">
                <span class="text">Already a member? 
                    <a href="signin.php" class="text login-link">Login Now</a>
                </span>
            </div>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>

