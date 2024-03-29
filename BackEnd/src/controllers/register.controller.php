<?php

include '../config/db_config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Hash the password
    if(empty($username) || empty($email)|| empty($password)) {
        echo "<script>alert('Enter complete details'); window.location.href = '../../../FrontEnd/Index/index.html';</script>";
        exit;
    }
    
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        
            // User already registered.
            echo "<script>alert('User is already registered'); window.location.href = '../../../FrontEnd/Index/index.html';</script>";
            exit;
    }else {
        // Insert new user into the database
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email' , '$hashed_password')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Registration successful, redirect to login page or homepage
            echo "<script>alert('User registered'); window.location.href = '../../../FrontEnd/Index/index.html';</script>";
            exit;
        } else {
            // Registration failed, redirect back to register page with error message
            echo "<script>alert('Error registering user'); window.location.href = '../../../FrontEnd/Index/index.html';</script>";
            // header("Location: ../../../FrontEnd/register.html?error=error registering user");
        }
        }
    }
    
?>