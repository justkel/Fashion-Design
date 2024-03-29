<?php

session_start();
// Include the database configuration
include '../../config/db_config.php';
include '../../model/family.model.php';
include '../../model/client.model.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and update family model
    $family['familyname'] = $_POST["familyname"];
    $family['phonenumber'] = $_POST["phonenumber"];
    $family['clientid'] = $_POST["clientid"];

    // Check if the user is logged in
    if(isset($_SESSION['username'])) {
        $family['username'] = $_SESSION['username'];
    }

    // Check if family name and phone number are provided
    if(empty($family['familyname']) || empty($family['phonenumber'])) {  
        echo "<script>alert('Enter Complete Details'); window.location.href = '../../../../FrontEnd/Customer/create-customer.php';</script>";
        // return; // Exit the function
    }
   
    $sql = "SELECT * FROM families WHERE familyname = '" . $family['familyname'] . "' AND username = '" . $_SESSION['username'] . "'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Family already registered.
        echo "<script>alert('Family already registered'); window.location.href = '../../../../FrontEnd/Customer/create-customer.php';</script>";
        // return; // Exit the function
    }else{
        // Insert new family into the database
        $sql = "INSERT INTO families (familyname, phonenumber, num_clients, username) VALUES ('" . $family['familyname'] . "', '" . $family['phonenumber'] . "', '" . $family['num_clients'] . "', '". $family['username']."')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $family_id = mysqli_insert_id($conn); // Get the inserted family ID
    
            // If clients are to be added to the family
            if (!empty($_POST["clientid"]) && is_array($_POST["clientid"])) {
                foreach ($_POST["clientid"] as $clientid) {
                    // Update client records with the family ID
                    $update_sql = "UPDATE clients SET family_id = $family_id WHERE clientid = $clientid";
                    $update_result = mysqli_query($conn, $update_sql);
                    // Handle any errors if needed
                }
            }
            // Registration successful
            echo "<script>alert('Family Registered successfully'); window.location.href = '../../../../FrontEnd/Customer/customers.php';</script>";
        } else {
            echo "<script>alert('Error registering FAMILY'); window.location.href = '../../../../FrontEnd/Customer/create-customer.php';</script>";
        }
    }
}

?>