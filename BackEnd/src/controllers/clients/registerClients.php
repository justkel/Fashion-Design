<?php

include '../../model/client.model.php';
include '../../config/db_config.php';

function registerClient($fullname, $phonenumber, $gender, $address, $conn) {
    // Get form data and update client model
    $client['fullname'] = $fullname;
    $client['phonenumber'] = $phonenumber;
    $client['gender'] = $gender;
    $client['address'] = $address;

    if (empty($client['fullname']) || empty($client['phonenumber']) || empty($client['gender']) || empty($client['address'])) {
        return array(
            'isSuccess' => false,
            'message' => 'Enter complete details'
        );
    } else {
        $sql = "SELECT * FROM clients WHERE fullname = '" . $client['fullname'] . "'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            // User already registered.
            return array(
                'isSuccess' => false,
                'message' => 'Client Already registered'
            );
        } else {
            // Insert new client into the database
            $sql = "INSERT INTO clients (fullname, phonenumber, gender, address) VALUES ('" . $client['fullname'] . "', '" . $client['phonenumber'] . "', '" . $client['gender'] . "', '" . $client['address'] . "')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                // Registration successful
                return array(
                    'isSuccess' => true,
                    'message' => 'Client registered'
                );
            } else {
                // Registration failed
                return array(
                    'isSuccess' => false,
                    'message' => 'Error registering client'
                );
            }
        }
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Call the registerClient function
    $registrationResult = registerClient($_POST["fullname"], $_POST["phonenumber"], $_POST["gender"], $_POST["address"], $conn);

    // Encode and output the result
    echo json_encode($registrationResult);
}
?>
