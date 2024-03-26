<?php

    include '../config/db_config.php';
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get username and password from form
        $username = $_POST["username"];
        $password = $_POST["password"];
    
        $username = mysqli_real_escape_string($conn , $username);
        $password = mysqli_real_escape_string($conn , $password);
        if ($username == "" || $password == "") {
            echo json_encode(array(
                'isSuccess' => false, 
                'message' => 'Enter complete details'));
        }
        // Prepare a SQL statement to fetch user from the database
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
    
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            if(password_verify($password, $row["password"])) {
                
                $_SESSION['username'] = $username;
                echo json_encode(array(
                    'isSuccess' => true, 
                    'message' => 'Login successful'));
                // header("Location: ../../../FrontEnd/dashboard.html");
                exit;
            }else {
                // Authentication failed, redirect back to login page with error message
                echo json_encode(array(
                    'isSuccess' => false, 
                    'message' => 'Invalid username or password'));
                // header("Location: ../../../FrontEnd/login.html?error=invalid_credentials");
                exit;
            }
        }else {
            // User does not exist, redirect back to login page with error message
            echo json_encode(array(
                'isSuccess' => false, 
                'message' => 'User does not exist'));
            // header("Location: ../../../FrontEnd/login.html?error=user_not_found");
            exit;
        }
    } else {
        // Redirect back to login page if accessed directly
        echo json_encode(array(
            'isSuccess' => false, 
            'message' => 'Method not allowed'));
        // header("Location: ../../../FrontEnd/login.html");
        exit();
    }
?>