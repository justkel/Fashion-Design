<?php
include '../../model/client.model.php';
include '../../config/db_config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
   // Get form data and update client model
    $client['fullname'] = $_POST["fullname"];
    $client['phonenumber'] = $_POST["phonenumber"];
    $client['gender'] = $_POST["gender"];
    $client['address'] = $_POST["address"];


  
    if(empty($client['fullname']) || empty($client['phonenumber']) || empty($client['gender']) || empty($client['address'])) {  
        echo json_encode(array(
            'isSuccess' => false, 
            'message' => 'Enter complete details'));

            exit;
    }else{
        $sql = "SELECT * FROM clients WHERE fullname = '" . $client['fullname'] . "'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            
                // User already registered.
                echo json_encode(array(
                    'isSuccess' => false, 
                    'message' => 'Client Already registered'));
                // header("Location: ../../../FrontEnd/login.html?error=user_already_registered");
                exit;
            }else {
                // Insert new client into the database
                $sql = "INSERT INTO clients (fullname, phonenumber, gender, address) VALUES ('" . $client['fullname'] . "', '" . $client['phonenumber'] . "', '" . $client['gender'] . "', '" . $client['address'] . "')";
                $result = mysqli_query($conn, $sql);
                
                $result = mysqli_query($conn, $sql);
        
                if ($result) {
                    // Registration successful, redirect to login page or homepage
                    echo json_encode(array(
                        'isSuccess' => true, 
                        'message' => 'client registered'));
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