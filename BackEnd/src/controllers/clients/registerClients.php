<?php

session_start();

// Include necessary files
include '../../model/client.model.php';
include '../../config/db_config.php';

// Function to register a client
function registerClient($fullname, $phonenumber, $gender, $address, $conn) {
    // Get form data and update client model
    $client['fullname'] = $fullname;
    $client['phonenumber'] = $phonenumber;
    $client['gender'] = $gender;
    $client['address'] = $address;

    // Check if the user is logged in
    if(isset($_SESSION['username'])) {
        $client['username'] = $_SESSION['username'];
    }

    // Check if any field is empty
    if (empty($client['fullname']) || empty($client['phonenumber']) || empty($client['gender']) || empty($client['address'])) {
        echo "<script>alert('Enter Complete Details'); window.location.href = '../../../../FrontEnd/Customer/create-customer.php';</script>";
        return; // Exit the function
    }

    // Check if the client is already registered
    $sql = "SELECT * FROM clients WHERE fullname = '" . $client['fullname'] . "' AND username = '" . $_SESSION['username'] . "'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        echo "<script>alert('Client already registered'); window.location.href = '../../../../FrontEnd/Customer/create-customer.php';</script>";
        return; // Exit the function
    }

    // Insert new client into the database
    $sql = "INSERT INTO clients (fullname, phonenumber, gender, address, username) VALUES ('" . $client['fullname'] . "', '" . $client['phonenumber'] . "', '" . $client['gender'] . "', '" . $client['address'] . "', '". $client['username']."')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script>alert('Client Registered successfully'); window.location.href = '../../../../FrontEnd/Customer/customers.php';</script>";
    } else {
        echo "<script>alert('Error registering client'); window.location.href = '../../../../FrontEnd/Customer/create-customer.php';</script>";
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Call the registerClient function
    registerClient($_POST["fullname"], $_POST["phonenumber"], $_POST["gender"], $_POST["address"], $conn);
}
?>
