<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['username'])) {
    echo "<script>alert('You are not allowed to view this page'); window.location.href = '../Index/index.html';</script>";
    exit();
}

$servername = "localhost"; // Change this to your database server name
$username = "root"; // Change this to your database username
$password = "Iwuhkel12#"; // Change this to your database password
$dbname = "taylors"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(array("error" => "Connection failed: " . $conn->connect_error));
    exit; // Use exit after sending error to stop script execution
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the family ID from the form
    $family_id = $_POST['family_id'];

    // Get the updated family name and phone number from the form
    $family_name = $_POST['family_name'];
    $phone_number = $_POST['phone_number'];

    // Check if family name and phone number are provided
    if (empty($family_name) || empty($phone_number)) {
        echo "<script>alert('Enter Complete Details'); window.location.href = '../../../FrontEnd/Customer/modifyFamily.php?family_id=$family_id';</script>";
        // return; // Exit the function
    }

    // Fetch the current family details
    $sql_fetch = "SELECT * FROM families WHERE family_id = '$family_id'";
    $result_fetch = mysqli_query($conn, $sql_fetch);
    $family = mysqli_fetch_assoc($result_fetch);

    // Check if the new family name and phone number are different from the current values
    if ($family['familyname'] === $family_name && $family['phonenumber'] === $phone_number) {
        // No new changes made
        echo "<script>alert('No new changes made'); window.location.href = '../../../FrontEnd/Customer/modifyFamily.php?family_id=$family_id';</script>";
        exit();
    }

    // Check if the new family name is not the same as the current family name
    if ($family['familyname'] !== $family_name) {
        // Check if the new family name already exists in the database
        $check_sql = "SELECT * FROM families WHERE LOWER(familyname) = LOWER('$family_name') AND family_id != '$family_id'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            // New family name already exists
            echo "<script>alert('Family name already exists'); window.location.href = '../../../FrontEnd/Customer/modifyFamily.php?family_id=$family_id';</script>";
            exit();
        }
    }

    // Update the family details in the database
    $sql = "UPDATE families SET familyname = '$family_name', phonenumber = '$phone_number' WHERE family_id = '$family_id'";
    if (mysqli_query($conn, $sql)) {
        // Success message
        echo "<script>alert('Family details updated successfully'); window.location.href = '../../../FrontEnd/Customer/modifyFamily.php?family_id=$family_id';</script>";
        exit();
    } else {
        // Error message
        echo "<script>alert('Failed to update family details'); window.location.href = '../../../FrontEnd/Customer/modifyFamily.php?family_id=$family_id';</script>";
        exit();
    }    
} else {
    // Redirect back to the modifyFamily page if form is not submitted
    header("Location: ../../../FrontEnd/Customer/modifyFamily.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
