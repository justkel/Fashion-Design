<?php
// Include the database configuration
include '../../config/db_config.php';
include '../../model/family.model.php';
include '../../model/client.model.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and update family model
    $family['familyname'] = $_POST["familyname"];
    $family['phonenumber'] = $_POST["phonenumber"];

    // Get client details
    $client['fullname'] = $_POST["fullname"];
    $client['phonenumber'] = $_POST["phonenumber"];
    $client['gender'] = $_POST["gender"];
    $client['address'] = $_POST["address"];

    // Check if family name and phone number are provided
    if(empty($family['familyname']) || empty($family['phonenumber'])) {  
        echo json_encode(array(
            'isSuccess' => false, 
            'message' => 'Please provide family name and phone number'));
        exit;
    }
   
    $sql = "SELECT * FROM families WHERE familyname = '" . $family['familyname'] . "'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // Family already registered.
        echo json_encode(array(
            'isSuccess' => false,
            'message' => 'Family Already registered'
        ));
    }else{
        // Insert new family into the database
        $sql = "INSERT INTO families (familyname, phonenumber) VALUES ('" . $family['familyname'] . "', '" . $family['phonenumber'] . "')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            // $family_id = mysqli_insert_id($conn); // Get the inserted family ID
    
            // If clients are to be added to the family
            // if (!empty($_POST["clients"]) && is_array($_POST["clients"])) {
            //     foreach ($_POST["clients"] as $client_id) {
            //         // Update client records with the family ID
            //         $update_sql = "UPDATE clients SET family_id = $family_id WHERE client_id = $client_id";
            //         $update_result = mysqli_query($conn, $update_sql);
            //         // Handle any errors if needed
            //     }
            // }
            // Registration successful
            echo (string)$client['fullname'];
            echo json_encode(array(
                'isSuccess' => true, 
                'message' => 'Family registered successfully'));
            exit;
        } else {
            // Registration failed
            echo json_encode(array(
                'isSuccess' => false, 
                'message' => 'Error registering family'));
            exit;
        }
    }
}

?>