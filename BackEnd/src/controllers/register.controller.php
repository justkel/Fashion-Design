<?php

include '../config/db_config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Hash the password
    if(empty($username) || empty($password)) {
        echo json_encode(array(
            'isSuccess' => false, 
            'message' => 'Enter complete details'));

            exit;
    }
    if($password !== $confirm_password) {
        echo json_encode(array(
            'isSuccess' => false, 
            'message' => 'Passwords do not match'));
        // header("Location: ../../../FrontEnd/register.html?error=password mismatch");
        exit;
    }else{
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            
                // User already registered.
                echo json_encode(array(
                    'isSuccess' => false, 
                    'message' => 'User Already registered'));
                // header("Location: ../../../FrontEnd/login.html?error=user_already_registered");
                exit;
        }else {
            // Insert new user into the database
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email, '$hashed_password')";
            $result = mysqli_query($conn, $sql);
    
            if ($result) {
                // Registration successful, redirect to login page or homepage
                echo json_encode(array(
                    'isSuccess' => true, 
                    'message' => 'Individual registered'));
                // header("Location: ../../../FrontEnd/login.html?registration=success");
                exit;
            } else {
                // Registration failed, redirect back to register page with error message
                echo json_encode(array(
                    'isSuccess' => false, 
                    'message' => 'Error registering client'));
                // header("Location: ../../../FrontEnd/register.html?error=error registering user");
            }
            }
        }
    }
?>