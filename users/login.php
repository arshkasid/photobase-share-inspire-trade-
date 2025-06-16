<?php
session_start();
include '../connect/connect.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulate user authentication
    $username = $_POST['username'];
    $password = $_POST['password'];

    echo "<script>console.log('Username: $username, Password: $password');</script>";
    

   
    //find user in db
    $find_user_QUERY=    "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

    $result = mysqli_query($conn, $find_user_QUERY);
    if (mysqli_num_rows($result) > 0) {
        // User found, set session variable
        $_SESSION['username'] = $username;
        header("Location: ../feed");
        exit();
    } else {
        // User not found, show error
        echo "<script>alert('Invalid username or password. Please try again.');</script>";
    }


   
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Login - photobase</title>
    <style>
        body { 
            background: linear-gradient(120deg, #232526 0%, #414345 100%);
            font-family: 'Segoe UI', Arial, sans-serif; 
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .login-container {
            max-width: 400px;
            background: rgba(30, 32, 38, 0.88);
            border-radius: 16px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.18);
            padding: 40px 32px 32px 32px;
            text-align: center;
            z-index: 1;
            position: relative;
            color: #fff;
        }
        .login-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 18px;
            color: #fff;
            letter-spacing: 1px;
        }
        .login-form input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1.5px solid #444;
            font-size: 16px;
            outline: none;
            background: rgba(255,255,255,0.12);
            color: #fff;
            transition: border 0.2s, background 0.2s;
        }
        .login-form input:focus {
            border: 1.5px solid #007bff;
            background: rgba(255,255,255,0.18);
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }
        .login-btn:hover {
            background: #0056b3;
        }
        .login-link {
            display: block;
            margin-top: 18px;
            color: #90caf9;
            text-decoration: none;
            font-size: 15px;
        }
        @media (max-width: 600px) {
            .login-container { padding: 24px 8px; }
        }
    </style>
</head>

<body>
    <div class="logo-bg"></div>
    <div class="login-container">
        <div class="login-title">Login to photobase</div>
        <form class="login-form" method="post" action="">
            <input type="text" name="username" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="login-btn" type="submit">Login</button>
        </form>
        <a class="login-link" href="register.php">Don't have an account? Register</a>
    </div>
</body>
</html>
