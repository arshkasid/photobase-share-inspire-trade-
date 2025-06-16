<?php
session_start();
include '../connect/connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $query = "INSERT INTO users (username, name, email, password, mobile) VALUES ('$username', '$name', '$email', '$password', '$mobile')";

    // Check if the username already exists
    $check_user_query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $check_user_query);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Username already exists. Please choose a different username.');</script>";
    } else {
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registration successful! You can now log in.');</script>";
            header("Location: login.php");
            exit();
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }

}

    ?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - photobase</title>
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
        .register-container {
            max-width: 420px;
            background: rgba(30, 32, 38, 0.88);
            border-radius: 16px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.18);
            padding: 40px 32px 32px 32px;
            text-align: center;
            color: #fff;
        }
        .register-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 18px;
            color: #fff;
        }
        .register-form input {
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
        .register-form input:focus {
            border: 1.5px solid #007bff;
            background: rgba(255,255,255,0.18);
        }
        .register-btn {
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
        .register-btn:hover {
            background: #0056b3;
        }
        .register-link {
            display: block;
            margin-top: 18px;
            color: #90caf9;
            text-decoration: none;
            font-size: 15px;
        }
        @media (max-width: 600px) {
            .register-container { padding: 24px 8px; }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-title">Create your photobase account</div>
        <form class="register-form" method="post" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="mobile" placeholder="Mobile Number" required>
            <button class="register-btn" type="submit">Register</button>
        </form>
        <a class="register-link" href="login.php">Already have an account? Login</a>
    </div>
</body>
</html>
