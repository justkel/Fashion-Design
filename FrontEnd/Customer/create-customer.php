<?php

// Start the session (if not already started)
session_start();
if(!isset($_SESSION['username'])) {
  // echo "Not authenticated";
  echo "<script>alert('You are not allowed to view this page'); window.location.href = '../Index/index.html';</script>";

}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Customer</title>
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
    <link rel="stylesheet" href="create.css" />
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="../logout.css">
    <link rel="stylesheet" href="../modal.css">
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
          <a href="../Measurement/measurements.php">
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
          <a href="#">
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
    <section class="home-section">
      <div class="home-content">
        <i class="bx bx-menu"></i>
      </div>

      <div class="container-above above">
        <button id="individualButton">Individual</button>
        <button id="familyButton">Family</button>
      </div>



      <div class="individualx">
        <h2 class="individual-header">Individual Registration</h2>
        <!-- Modal HTML structure -->
        <div id="myModal" class="modal">
          <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalMessage"></p>
          </div>
        </div>
        <form action="../../BackEnd/src/controllers/clients/registerClients.php" method="post">
          <label for="fullName">Full Name</label>
          <input
            class="input-select"
            type="text"
            id="fullName"
            name="fullname"
            required
          />

          <label for="phoneNumber">Phone Number</label>
          <input
            class="input-select"
            type="tel"
            id="phoneNumber"
            name="phonenumber"
            required
          />

          <label for="gender">Gender</label>
          <select
            class="input-select"
            id="gender"
            name="gender"
            required
          >
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>

          <label for="address">Address</label>
          <textarea id="address" name="address" rows="4" required></textarea>

          <button class="submit-btn" type="submit">Submit</button>
        </form>
      </div>

      <div class="familyx" style="display: none">
        <h2 class="family-header">Family Account Registration</h2>
        <form id="familyForm" action="../../BackEnd/src/controllers/clients/registerFamily.php" method="post">
          <label for="familyName">Family Name</label>
          <input
            class="input-select"
            type="text"
            id="familyName"
            name="familyname"
            required
          />

          <div id="familyMembers">
            <label>Family Members</label><br />
            <?php
              "<select name='individual-customers' class='input-select' multiple>";
                  "<option value=''>Select Existing Customer</option>";
              "</select>";
              include '../../BackEnd/src/config/db_config.php';
                // Check if the user ID is set in the session

                if(isset($_SESSION['username'])) {
                  // Retrieve the user ID from the session
                  $username = $_SESSION['username'];
  
                  // Query to select all users from the database
                  $sql = "SELECT clientid, fullname FROM clients WHERE username = '$username'"; // Modify the query according to your table structure
  
                  $result = $conn->query($sql);
  
                  
                  echo "<select class='select-measure' id='user' name = 'clientid[]' multiple>"; 
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
            

            <label for="phoneNumber">General Phone No</label>
            <input
              class="input-select"
              type="tel"
              id="familyPhoneNumber"
              name="phonenumber"
              required
            />

            <div id="memberFields"></div>

            <br />
          </div>
          <br />

          <button class="submit-btn" type="submit">Submit</button>
        </form>
      </div>
    </section>
    <!-- <script src="../modal.js"></script> -->
    <script>
   
      let sidebar = document.querySelector(".sidebar");
      let sidebarBtn = document.querySelector(".bx-menu");
      console.log(sidebarBtn);
      sidebarBtn.addEventListener("click", () => {
        sidebar.classList.toggle("close");
      });

      document
        .getElementById("individualButton")
        .addEventListener("click", function () {
          document.querySelector(".individualx").style.display = "block";
          document.querySelector(".familyx").style.display = "none";
        });

      document
        .getElementById("familyButton")
        .addEventListener("click", function () {
          document.querySelector(".individualx").style.display = "none";
          document.querySelector(".familyx").style.display = "block";
        });

      var memberCount = 0; // Initialize member count  


      function removeFamilyMember(button) {
        var memberContainer = button.parentNode;
        memberContainer.parentNode.removeChild(memberContainer);
      }

            // Get the modal
      // var modal = document.getElementById("myModal");

      // // Get the <span> element that closes the modal
      // var span = document.getElementsByClassName("close")[0];

      // // When the user clicks on <span> (x), close the modal
      // span.onclick = function() {
      //   modal.style.display = "none";
      // }

      // // Display the modal with a message
      // function displayModal(message) {
      //   var modalMessage = document.getElementById("modalMessage");
      //   modalMessage.innerText = message;
      //   modal.style.display = "block";
      // }

    </script>
  </body>
</html>
