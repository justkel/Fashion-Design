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
  if (isset($_SESSION['username'])) {
    $family['username'] = $_SESSION['username'];
  }

  // Check if family name and phone number are provided
  if (empty($family['familyname']) || empty($family['phonenumber'])) {
    echo "<script>alert('Enter Complete Details'); window.location.href = '../../../../FrontEnd/Customer/create-customer.php';</script>";
    // return; // Exit the function
  }

  $sql = "SELECT * FROM families WHERE familyname = '" . $family['familyname'] . "' AND username = '" . $_SESSION['username'] . "'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) == 1) {
    // Family already registered.
    echo "<script>alert('Family already registered'); window.location.href = '../../../../FrontEnd/Customer/create-customer.php';</script>";
    // return; // Exit the function
  } else {
    // Insert new family into the database
    $sql = "INSERT INTO families (familyname, phonenumber, num_clients, username) VALUES ('" . $family['familyname'] . "', '" . $family['phonenumber'] . "', '" . $family['num_clients'] . "', '" . $family['username'] . "')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      $family_id = mysqli_insert_id($conn); // Get the inserted family ID

      $already_associated = false;

      // If clients are to be added to the family
      if (!empty($_POST["clientid"]) && is_array($_POST["clientid"])) {
        foreach ($_POST["clientid"] as $clientid) {
          // Check if the client is already associated with a family
          $check_sql = "SELECT * FROM clients WHERE clientid = $clientid AND (family_id IS NOT NULL OR family_id != '')";
          $check_result = mysqli_query($conn, $check_sql);

          // Update `already_associated` only if client is associated
          if (mysqli_num_rows($check_result) > 0) {
              $already_associated = true;
              continue; // Skip to the next client
          }

          // Update client records with the family ID
          $update_sql = "UPDATE clients SET family_id = $family_id WHERE clientid = $clientid";
          $update_result = mysqli_query($conn, $update_sql);
          // Handle any errors if needed
        }
      }

      if ($already_associated) {
        echo "<script>alert('Family created successfully, but some clients are already associated with a family.'); window.location.href = '../../../../FrontEnd/Customer/customers.php';</script>";
      } else {

        // Registration successful message
        echo "<script>alert('Family Registered successfully'); window.location.href = '../../../../FrontEnd/Customer/customers.php';</script>";
      }
      // Update num_clients in families table (after client association)
      $update_num_clients_sql = "UPDATE families SET num_clients = (SELECT COUNT(*) FROM clients WHERE family_id = $family_id) WHERE family_id = $family_id";
      mysqli_query($conn, $update_num_clients_sql);
    } else {
      echo "<script>alert('Error registering FAMILY'); window.location.href = '../../../../FrontEnd/Customer/create-customer.php';</script>";
    }
  }
}

?>
