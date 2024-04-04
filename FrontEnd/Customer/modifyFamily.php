<?php
session_start();
if(!isset($_SESSION['username'])) {
    echo "<script>alert('You are not allowed to view this page'); window.location.href = '../Index/index.html';</script>";
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Modify Family</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="create.css">
    <link rel="stylesheet" href="../logout.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        .modify-all {
        padding: 20px;
        border-radius: 10px;
    }

    .modify-all h1 {
        font-size: 20px;
        /* font-weight: 700; */
        margin-bottom: 10px;
        color: #5E4B8B;
    }

    .modify-all h2 {
        font-size: 20px;
        /* font-weight: 700; */
        margin-bottom: 10px;
        margin-top: 10px;
        color: #5E4B8B;
    }

    .modify-all form {
        margin-bottom: 20px;
    }

    .modify-all label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: black;
    }

    .modify-all input[type="text"],
    .modify-all input[type="submit"],
    .modify-all select {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .modify-all input[type="submit"] {
        margin-top: 20px;
        padding: 10px 20px;
        color: #fff;
        border: none;
        cursor: pointer;
        font-size: 16px;
        height: 40px;
        font-weight: 500;
        background-color: #5E4B8B;
        font-weight: 600;
        color: antiquewhite;
        border: none;
        border-radius: 30px;
        cursor: pointer;
        transition: .3s ease;
    }

    .modify-all input[type="submit"]:hover {
        background-color: rgb(158, 102, 141)
    }

    @media screen and (max-width: 768px) {
        .modify-all h1 {
            font-size: 18px;
        }

        .modify-all h2 {
            font-size: 18px;
            margin-top: 5px;
        }

        .modify-all input[type="submit"] {
            padding: 8px 16px;
            height: 35px;
        }

        .modify-all select option {
            font-size : 15px;
        }
    }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo-details">
            <i class="ri-scissors-line"></i>
            <span style="cursor: pointer;" onclick="navigateToRoot()" class="logo_name">TAYLUR</span>
        </div>
        <ul class="nav-links">
            <li>
                <div class="iocn-link">
                    <a href="../Customer/customers.php">
                        <i class='bx bx-collection' ></i>
                        <span class="link_name">Customers</span>
                    </a>
                </div>
            </li>
            <li>
                <a href="../Measurement/measurements.php">
                    <i class='bx bx-pie-chart-alt-2' ></i>
                    <span class="link_name">Measurements</span>
                </a>
            </li>
            <li>
                <a href="../Appointment/appointments.php">
                    <i class='bx bx-line-chart' ></i>
                    <span class="link_name">Appointment</span>
                </a>
            </li>
            <li>
                <a href="../Project/project.php">
                    <i class='bx bx-history'></i>
                    <span class="link_name">Projects</span>
                </a>
            </li>
            <li>
                <div class="profile-details">
                    <div class="profile-content"></div>
                    <div class="name-job">
                        <?php
                        if (isset($_SESSION["username"])) {
                            $user = $_SESSION["username"];
                            echo "<div class='profile_name'>" . $user . "</div>";
                        }
                        ?>
                        <div class="job"> Fashion Designer </div>
                    </div>
                    <form action="" method="post">
                        <button type="submit" name="logout" class="logout-button"><i class='bx bx-log-out'></i></button>
                    </form>
                    <?php
                    include '../../BackEnd/src/controllers/logoutController.php';
                    if (isset($_POST['logout'])) {
                        logout();
                    }
                    ?>
                </div>
            </li>
        </ul>
    </div>
    <section class="home-section">
        <div class="home-content">
            <i class='bx bx-menu' ></i>
            <span class="text"><i class="ri-user-6-line"></i> Welcome</span>
        </div>
        <div class="modify-all">
            <?php
            // Include the database configuration file
            include '../../BackEnd/src/config/db_config.php';

            // Check if family_id parameter is set in the URL
            if(isset($_GET['family_id'])) {
                $family_id = $_GET['family_id'];

                // Fetch the details of the selected family from the database
                $sql = "SELECT * FROM families WHERE family_id = '$family_id'";
                $result = mysqli_query($conn, $sql);
                $family = mysqli_fetch_assoc($result);

                // Check if the family exists
                if($family) {
                    // Display the family details and provide options to modify them
                    ?>
                    <h1>Family Name: <?php echo $family['familyname']; ?></h1>
                    <p>No of Members: <?php echo $family['num_clients']; ?></p>

                    <!-- Additional options to modify family details -->
                    <h2>Modify Family Details</h2>
                    <form action="../../BackEnd/src/controllers/updateFamilyDetails.php" method="post">
                        <input type="hidden" name="family_id" value="<?php echo $family_id; ?>">
                        <label for="family_name">Family Name:</label>
                        <input type="text" id="family_name" name="family_name" value="<?php echo $family['familyname']; ?>" >
                        <label for="phone_number">Phone Number:</label>
                        <input type="text" id="phone_number" name="phone_number" value="<?php echo $family['phonenumber']; ?>" required>
                        <input type="submit" value="Update Family">
                    </form>

                    <!-- Form to add new clients to the family -->
                    <h2>Add New Individual(s)</h2>
                    <form action="../../BackEnd/src/controllers/addFamilyMember.php" method="post">
                        <input type="hidden" name="family_id" value="<?php echo $family_id; ?>">
                        <select name='clientid[]' class='input-select' multiple>
                            <option value=''>Select Existing Customer</option>
                            <?php
                            // Query to select all clients from the database
                            $sql_clients = "SELECT clientid, fullname FROM clients WHERE username = '$user'";
                            $result_clients = $conn->query($sql_clients);
                            if ($result_clients->num_rows > 0) {
                                // Output data of each row
                                while($row_clients = $result_clients->fetch_assoc()) {
                                    echo "<option value='" . $row_clients["clientid"] . "'>" . $row_clients["fullname"] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No clients found</option>";
                            }
                            ?>
                        </select>
                        <input type="submit" value="Add Client">
                    </form>

                    <?php
                } else {
                    // Display an error message if the family doesn't exist
                    echo "<p>Family not found.</p>";
                }

                // Close the database connection
                mysqli_close($conn);
            } else {
                // Redirect back to the main page if family_id parameter is not set
                header("Location: customers.php");
                exit();
            }
            ?>
        </div>
    </section>
    <script>
        // JavaScript code for sidebar toggling
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".bx-menu");
        sidebarBtn.addEventListener("click", ()=>{
            sidebar.classList.toggle("close");
        });

        function navigateToRoot() {
            window.location.href = "../Landing-Page/root.html";
        }
    </script>
</body>
</html>
