<?php

session_start();
include '../config/db_config.php';
include '../model/measurement.php';
include '../model/client.model.php';

function saveMeasurement($shirt_length, $chest, $neck, $back, $long_sleeve, $short_sleeve, $round_sleeve, $shoulder, $waist, $trouser_length, $ankle, $thigh, $knee_length, $head, $hip, $burst, $clientid, $conn) {

    // Get form data and update client model
    $measurement['shirt_length'] = $shirt_length;
    $measurement['chest'] = $chest;
    $measurement['neck'] = $neck;
    $measurement['back'] = $back;
    $measurement['long_sleeve'] = $long_sleeve;
    $measurement['short_sleeve'] = $short_sleeve;
    $measurement['round_sleeve'] = $round_sleeve;
    $measurement['shoulder'] = $shoulder;
    $measurement['waist'] = $waist;
    $measurement['trouser_length'] = $trouser_length;
    $measurement['ankle'] = $ankle;
    $measurement['thigh'] = $thigh;
    $measurement['knee_length'] = $knee_length;
    $measurement['head'] = $head;
    $measurement['hip'] = $hip;
    $measurement['burst'] = $burst;
    $measurement['clientid'] = $clientid;


     // Check if the user is logged in
     if(isset($_SESSION['username'])) {
        $measurement['username'] = $_SESSION['username'];
    }else{
        echo "<script>alert('User not logged in'); window.location.href = '../../../FrontEnd/Index/index.html';</script>";
        return;
    }

    $sql = "SELECT * FROM measurements WHERE clientid = '" . $measurement['clientid'] . "' AND username = '" . $_SESSION['username'] . "'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        // User already registered.
        echo "<script>alert('Clients measurement has been taken'); window.location.href = '../../../FrontEnd/Measurement/measurements.php';</script>";
        return;
    }else{
        // Insert new client into the database
        $sql = "INSERT INTO measurements (shirt_length, chest, neck, back, long_sleeve, short_sleeve, round_sleeve, shoulder, waist, trouser_length, ankle, thigh, knee_length, head, hip, burst, clientid, username) VALUES ('" . $measurement['shirt_length'] . "', '" .  $measurement['chest'] . "', '" . $measurement['neck'] . "', '" . $measurement['back'] . "', '". $measurement['long_sleeve'] ."', '" . $measurement['short_sleeve'] . "', '" . $measurement['round_sleeve'] . "', '" . $measurement['shoulder'] . "', '" . $measurement['waist'] . "', '" . $measurement['trouser_length'] . "', '" . $measurement['ankle'] . "', '" . $measurement['thigh'] . "', '" . $measurement['knee_length'] . "', '" . $measurement['head'] . "', '" . $measurement['hip'] . "', '" . $measurement['burst'] . "', '" . $measurement['clientid'] . "', '". $measurement['username']."' )";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Measurement added successful
            echo "<script>alert('Measurement has been successfully stored'); window.location.href = '../../../FrontEnd/Measurement/measurements.php';</script>";
        } else {
            // Insertion failed
            echo "<script>alert('Error adding measurement to the database'); window.location.href = '../../../FrontEnd/Measurement/measurements.php';</script>";

        }
    }
 
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Call the registerClient function
    $measurementResult = saveMeasurement($_POST["shirt_length"], $_POST["chest"], $_POST["neck"], $_POST["back"],$_POST["long_sleeve"] ,$_POST["short_sleeve"],$_POST["round_sleeve"],$_POST["shoulder"], $_POST["waist"], $_POST["trouser_length"],$_POST["ankle"],$_POST["thigh"],$_POST["knee_length"],$_POST["head"],$_POST["hip"],$_POST["burst"], $_POST["clientid"], $conn);

    // Encode and output the result
    echo json_encode($measurementResult);
}
?>
