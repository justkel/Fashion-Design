<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You are not allowed to view this page'); window.location.href = '../Index/index.html';</script>";
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $family_id = $_POST["family_id"];
    $clientids = $_POST["clientid"];

    // Check if any client is selected
    if (empty($clientids)) {
        echo "<script>alert('No Clients were selected'); window.location.href = '../../../FrontEnd/Customer/modifyFamily.php?family_id=$family_id';</script>";
        exit();
    }

    $already_associated = false; // Flag to track if any client is already associated

    // Loop through each client id
    foreach ($clientids as $clientid) {
        // Check if the client is already associated with a family
        $check_sql = "SELECT * FROM clients WHERE clientid = $clientid AND (family_id IS NOT NULL OR family_id != '')";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            // Client is already associated with a family
            $already_associated = true;
            continue;
        }

        // Update client records with the family ID
        $update_sql = "UPDATE clients SET family_id = $family_id WHERE clientid = $clientid";
        $update_result = mysqli_query($conn, $update_sql);
        // Handle any errors if needed
    }

    // Update num_clients in families table (after client association)
    $update_num_clients_sql = "UPDATE families SET num_clients = (SELECT COUNT(*) FROM clients WHERE family_id = $family_id) WHERE family_id = $family_id";
    mysqli_query($conn, $update_num_clients_sql);

    // Check if any client is already associated
    if ($already_associated) {
        echo "<script>alert('Some clients are already associated with a family.'); window.location.href = '../../../FrontEnd/Customer/modifyFamily.php?family_id=$family_id';</script>";
    } else {
        echo "<script>alert('Clients added successfully.'); window.location.href = '../../../FrontEnd/Customer/modifyFamily.php?family_id=$family_id';</script>";
    }
} else {
    // Redirect back to the modifyFamily page if form is not submitted
    header("Location: ../../../FrontEnd/Customer/modifyFamily.php?family_id=$family_id");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
