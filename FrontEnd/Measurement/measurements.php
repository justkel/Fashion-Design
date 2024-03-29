<?php

// Start the session (if not already started)
session_start();
if(!isset($_SESSION['username'])) {
  echo "<script>alert('You are not allowed to view this page'); window.location.href = '../Index/index.html';</script>";

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Measurements Page</title>
<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="../Customer/create.css">
<link
      href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link
      href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap"
    />
    <link rel="stylesheet" href="../logout.css">
</head>
<body>

    <div class="sidebar">
        <div class="logo-details">
          <i class="ri-scissors-line"></i>
          <span class="logo_name">TAYLUR</span>
        </div>
        <ul class="nav-links">
         
          <li>
            <div class="iocn-link">
              <a href="../Customer/customers.php">
                <i class="bx bx-collection"></i>
                <span class="link_name">Customers</span>
              </a>
           
            </div>
          </li>
          <li>
            <a href="measurements.php">
              <i class="bx bx-pie-chart-alt-2"></i>
              <span class="link_name">Measurements</span>
            </a>
          </li>
          <li>
            <a href="../Appointment/appointments.php">
              <i class="bx bx-line-chart"></i>
              <span class="link_name">Appointment</span>
            </a>
          </li>
          <li>
            <a href="../Project/project.php">
              <i class="bx bx-history"></i>
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
                      
                      // Further processing based on the user session
                      echo "<div class='profile_name'>" . $user . "</div>";
                    }
                  ?>
                  <div class="job"> Fashion Designer </div>
              </div>
              <form action="" method="post">
                <button type="submit" name="logout" class='logout-button'><i class='bx bx-log-out'></i></button>
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

    <div class="home-section section">
        
        <div class="home-content">
            <i class="bx bx-menu"></i>
        </div>

        <div class="measurement-div">
           <h2 class="measure-header">Measurements Page</h2>
           <form id="measurements-form" action='../../BackEnd/src/controllers/measurement.controller.php' method="post">
        
            <label for="user">Select User:</label>
            <?php
              include '../../BackEnd/src/config/db_config.php';

              
              if(isset($_SESSION['username'])) {
                // Retrieve the user ID from the session
                $username = $_SESSION['username'];

                // Query to select all users from the database
                $sql = "SELECT clientid, fullname FROM clients WHERE username = '$username'"; // Modify the query according to your table structure

                $result = $conn->query($sql);

                function getClientid(){}
                echo "<select class='select-measure' id='user' name = 'clientid'>";
                echo "<option value=''>Select client</option>"; 
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                      echo "<option value='" . $row["clientid"] . "'>" . $row["fullname"] . "</option>";

                    }
                } else {
                    echo "<option value=''>No users found</option>";
                }
                echo "</select>";
              }


              ?>
            
    
            <div class="measurements">
                <label class="measurement-span">Measurements</label>
    
                <div id="female-measurements" class="female-div female-add">
                  <div class="form-group">
                    <label class="head-first" for="head">Head (HD)</label>
                    <input
                      type="text"
                      id="head"
                      name="head"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="shoulder">Shoulder (SH)</label>
                    <input
                      type="text"
                      id="shoulder"
                      name="shoulder"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="chest">Chest (CH)</label>
                    <input
                      type="text"
                      id="chest"
                      name="chest"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="waist">Waist (WA)</label>
                    <input
                      type="text"
                      id="waist"
                      name="waist"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="hip">Hip (HP)</label>
                    <input
                      type="text"
                      id="hip"
                      name="hip"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="ankle">Ankle (AN)</label>
                    <input
                      type="text"
                      id="ankle"
                      name="ankle"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="neck">Neck (NK)</label>
                    <input
                      type="text"
                      id="neck"
                      name="neck"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="burst">Burst (BS)</label>
                    <input
                      type="text"
                      id="burst"
                      name="burst"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="shirt_length">shirt_length (SL)</label>
                    <input
                      type="text"
                      id="shirt_length"
                      name="shirt_length"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="long_sleeve">long_sleeve (lS)</label>
                    <input
                      type="text"
                      id="long_sleeve"
                      name="long_sleeve"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="short_sleeve">Short Sleeve (SS)</label>
                    <input
                      type="text"
                      id="short_sleeve"
                      name="short_sleeve"
                      class="measurement-input"
                    />
                  </div>
                  
                  <div class="form-group">
                    <label for="round_sleeve">round_sleeve (rS)</label>
                    <input
                      type="text"
                      id="round_sleeve"
                      name="round_sleeve"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="thigh">Thigh (TH)</label>
                    <input
                      type="text"
                      id="thigh"
                      name="thigh"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="trouser_length">Trouser Length (TL)</label>
                    <input
                      type="text"
                      id="trouser_length"
                      name="trouser_length"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="knee_length">knee Length (KL)</label>
                    <input
                      type="text"
                      id="knee_length"
                      name="knee_length"
                      class="measurement-input"
                    />
                  </div>
                  <div class="form-group">
                    <label for="back">Back (Bk)</label>
                    <input
                      type="text"
                      id="back"
                      name="back"
                      class="measurement-input"
                    />
                  </div>
                </div>
              </div>

          

            <button type="submit" class="submit-btn" id="submit-btn" class="hidden">Submit</button>
            
        </form> 
        </div>
        

    </div>    

    
<!-- 
<script src="../scripts.js"></script> -->

<script>
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".bx-menu");
    console.log(sidebarBtn);
    sidebarBtn.addEventListener("click", ()=>{
      sidebar.classList.toggle("close");
    });
</script>
</body>
</html>