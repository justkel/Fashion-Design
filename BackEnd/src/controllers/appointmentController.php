<?php
session_start();
// Database configuration
$servername = "localhost"; // Database server name
$username = "root"; // Database username
$password = "Iwuhkel12#"; // Database password
$dbname = "taylors"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit; // Stop script execution if connection fails
}

$conn->query("SET time_zone='+00:00';");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Prepare the statement with a placeholder for the username
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE username = ?");
    $stmt->bind_param("s", $username); // 's' specifies the variable type => 'string'
    
    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch all matching rows as an associative array
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
    
    // Check if any appointments were found
    if ($appointments) {
        echo json_encode($appointments);
    } else {
        echo json_encode(["error" => "No appointments found for username: $username"]);
    }

    // Close the prepared statement
    $stmt->close();

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    $date = isset($data['date']) ? $data['date'] : '';
    $details = isset($data['details']) ? $data['details'] : '';

    // Basic validation (very simplified)
    if (!empty($username) && !empty($date) && !empty($details)) {
        $stmt = $conn->prepare("INSERT INTO appointments (username, appointment_date, details) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $date, $details); // 'sss' indicates all parameters are strings
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        
        // Close the prepared statement
        $stmt->close();
    } else {
        // Handle invalid input
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
} else {
    // Not a GET or POST request
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
}

// Close the database connection
$conn->close();
?>
