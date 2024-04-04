<?php
// Database configuration
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

// Set header to indicate the response type as JSON
header('Content-Type: application/json');

// Check if clientId is provided in the request
if (isset($_GET['clientId'])) {
    $clientId = $_GET['clientId'];

    // Prepare SQL statement excluding MeasurementId, Clientid, and username
    $stmt = $conn->prepare("SELECT shirt_length, chest, neck, back, long_sleeve, short_sleeve, round_sleeve, shoulder, waist, trouser_length, ankle, thigh, knee_length, head, hip, burst FROM measurements WHERE clientid = ?");
    $stmt->bind_param("i", $clientId); // 'i' specifies the variable type => 'integer'
    
    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if any measurements are found
    if ($result->num_rows > 0) {
        $measurements = $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows as an associative array
        echo json_encode($measurements);
    } else {
        // In case no measurements found, return a JSON object with an error message
        echo json_encode(array("error" => "No measurements found for client ID: $clientId"));
    }
    
    // Close the prepared statement
    $stmt->close();
} else {
    // In case clientId is not set, return a JSON object with an error message
    echo json_encode(array("error" => "clientId not provided in the request"));
}

// Close the database connection
$conn->close();
?>
