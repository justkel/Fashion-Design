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
    <title>Tailor's Appointment Calendar</title>
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
    <link rel="stylesheet" href="../Customer/create.css" />
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="../logout.css">

    <style>
      body {
        font-family: "Arial", sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
      }
      #calendar {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
      }
      #calendar header {
        background-color: #5e4b8b;
        color: #fff;
        padding: 20px;
        text-align: center;
        position: relative;
      }
      #calendar header h2 {
        margin: 0;
        font-size: 24px;
      }
      .button {
        position: absolute;
        top: 20px;
        background-color: rgba(255, 255, 255, 0.3);
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
      }
      .button.prev {
        left: 10px;
      }
      .button.next {
        right: 10px;
      }
      #calendar table {
        width: 100%;
        border-collapse: collapse;
      }
      #calendar th,
      #calendar td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
      }
      #calendar th {
        background-color: #e7a9be;
      }
      #calendar td {
        cursor: pointer;
      }
      #calendar td:not(.empty):hover {
        background-color: #f0f0f0;
      }
      #calendar .today {
        background-color: #5e4b8b;
        color: white;
      }

      #calendar-body {
        overflow-x: scroll; /* Always shows horizontal scrollbar */
        -webkit-overflow-scrolling: touch; /* Smooth scrolling on touch devices */
        margin: 0 20px; /* Adjust the margin as needed */
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
          <a href="#">
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

    <section class="home-section app">
      <div class="home-content">
        <i class="bx bx-menu"></i>
      </div>

      <div id="calendar">
        <header>
          <h2 id="monthAndYear"></h2>
          <button class="button prev" onclick="moveMonth(-1)">&#10094;</button>
          <button class="button next" onclick="moveMonth(1)">&#10095;</button>
        </header>
        <table>
          <thead>
            <tr>
              <th>Sun</th>
              <th>Mon</th>
              <th>Tue</th>
              <th>Wed</th>
              <th>Thu</th>
              <th>Fri</th>
              <th>Sat</th>
            </tr>
          </thead>
          <tbody id="calendar-body"></tbody>
        </table>
      </div>
    </section>
  </body>
  <script>
    let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".bx-menu");
    console.log(sidebarBtn);
    sidebarBtn.addEventListener("click", () => {
      sidebar.classList.toggle("close");
    });

    const today = new Date();
    let currentMonth = today.getMonth();
    let currentYear = today.getFullYear();

    const monthAndYear = document.getElementById("monthAndYear");
    const calendarBody = document.getElementById("calendar-body");

    // function renderCalendar(year, month) {
    //   calendarBody.innerHTML = ""; // Clear existing calendar entries
    //   const monthNames = [
    //     "January",
    //     "February",
    //     "March",
    //     "April",
    //     "May",
    //     "June",
    //     "July",
    //     "August",
    //     "September",
    //     "October",
    //     "November",
    //     "December",
    //   ];

    //   const firstDay = new Date(year, month).getDay();
    //   const daysInMonth = 32 - new Date(year, month, 32).getDate();

    //   let date = 1;
    //   for (let i = 0; i < 6; i++) {
    //     let row = document.createElement("tr");

    //     for (let j = 0; j < 7; j++) {
    //       let cell = document.createElement("td");
    //       if (i === 0 && j < firstDay) {
    //         cell.classList.add("empty");
    //       } else if (date > daysInMonth) {
    //         cell.classList.add("empty"); // Continue filling row with empty cells if needed
    //       } else {
    //         cell.textContent = date;
    //         cell.setAttribute('data-date', `${year}-${month + 1}-${date}`); // Ensure dates are properly formatted (YYYY-MM-DD)

    //         if (
    //           date === today.getDate() &&
    //           year === today.getFullYear() &&
    //           month === today.getMonth()
    //         ) {
    //           cell.classList.add("today");
    //         }

    //         cell.onclick = function () {
    //           addAppointment(this);
    //         };

    //         date++;
    //       }
    //       row.appendChild(cell);
    //     }
    //     calendarBody.appendChild(row);
    //   }

    //   monthAndYear.textContent = `${monthNames[month]} ${year}`;

    //   // Fetch and display appointments after rendering the calendar
    //   fetchAppointmentsAndRender(year, month);
    // }

    function renderCalendar(year, month) {
    calendarBody.innerHTML = ""; // Clear existing calendar entries
    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    const firstDay = new Date(year, month).getDay();
    const daysInMonth = 32 - new Date(year, month, 32).getDate();

    let date = 1;
    for (let i = 0; i < 6; i++) {
        let row = document.createElement("tr");

        for (let j = 0; j < 7; j++) {
            let cell = document.createElement("td");
            if (i === 0 && j < firstDay) {
                cell.classList.add("empty");
            } else if (date > daysInMonth) {
                cell.classList.add("empty"); // Continue filling row with empty cells if needed
            } else {
                cell.innerHTML = `<div class="date-number">${date}</div><div class="appointment-details"></div>`;
                cell.setAttribute('data-date', `${year}-${(month + 1).toString().padStart(2, '0')}-${date.toString().padStart(2, '0')}`); // Ensure dates are properly formatted (YYYY-MM-DD)

                if (date === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                    cell.classList.add("today");
                }

                cell.onclick = function() {
                    addAppointment(this);
                };

                date++;
            }
            row.appendChild(cell);
        }
        calendarBody.appendChild(row);
    }

    monthAndYear.textContent = `${monthNames[month]} ${year}`;

    // Fetch and display appointments after rendering the calendar
    fetchAppointmentsAndRender(year, month);
}



    // function addAppointment(td) {
    //   let date = td.textContent;
    //   let yearMonth = monthAndYear.textContent;
    //   let appointmentDate = new Date(`${yearMonth} ${date}`);

    //   let appointment = prompt("Add appointment details for " + date + " " + yearMonth + ":", "");
    //   if (appointment) {
    //     var xhr = new XMLHttpRequest();
    //     xhr.open("POST", "../../BackEnd/src/controllers/appointmentController.php", true);
    //     xhr.setRequestHeader("Content-Type", "application/json"); // Set the content type to JSON
        
    //     // Define what happens on successful data submission
    //     xhr.onload = function () {
    //         if (xhr.status == 200) {
    //             var data = JSON.parse(xhr.responseText);
    //             if(data.success) {
    //                 // Update the cell only on successful save
    //                 td.innerHTML = `${date}<div>${appointment}</div>`;
    //             } else {
    //                 alert('Failed to save appointment.');
    //             }
    //         } else {
    //             // Handle non-200 status
    //             console.error("Server returned status:", xhr.status);
    //             alert('Failed to save appointment. Please try again.');
    //         }
    //     };
        
    //     // Define what happens in case of an error
    //     xhr.onerror = function () {
    //         console.error("Request failed");
    //         alert('Error making the request.');
    //     };
        
    //     // Set up and send the request
    //     xhr.send(JSON.stringify({ date: appointmentDate.toISOString().split('T')[0], details: appointment }));
    //  }

    // }

    function addAppointment(cell) {
      let dateString = cell.getAttribute('data-date'); // Use the data-date attribute
      let appointmentDate = new Date(dateString);

      let promptMessage = `Add appointment details for ${appointmentDate.toLocaleDateString()}:`;
      let appointment = prompt(promptMessage, "");

      if (appointment) {
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "../../BackEnd/src/controllers/appointmentController.php", true);
          xhr.setRequestHeader("Content-Type", "application/json");

          xhr.onload = function () {
              if (xhr.status == 200) {
                  var data = JSON.parse(xhr.responseText);
                  if(data.success) {
                      // Consider fetching and re-rendering the appointments to reflect the new addition
                      fetchAppointmentsAndRender(appointmentDate.getFullYear(), appointmentDate.getMonth());
                  } else {
                      alert('Failed to save appointment.');
                  }
              } else {
                  console.error("Server returned status:", xhr.status);
                  alert('Failed to save appointment. Please try again.');
              }
          };

          xhr.onerror = function () {
              console.error("Request failed");
              alert('Error making the request.');
          };

          // Set up and send the request with the correct date format
          xhr.send(JSON.stringify({ 
              date: dateString, // Use dateString directly since it's already in the correct format
              details: appointment 
          }));

          // Limit the appointment text to 100 characters
          if (appointment && appointment.length > 30) {
            appointment = appointment.substring(0, 15) + '...'; // Truncate and add ellipsis
          }
          
          if (appointment) {
            cell.innerHTML = `${cell.textContent}<div>${appointment}</div>`;
          }
      }
   }




    function moveMonth(step) {
      currentMonth += step;
      if (currentMonth < 0) {
        currentMonth = 11;
        currentYear -= 1;
      } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear += 1;
      }
      renderCalendar(currentYear, currentMonth);
    }

    renderCalendar(currentYear, currentMonth); // Initial call to display the current month's calendar

    function navigateToRoot() {
        window.location.href = "../Landing-Page/root.html";
    }

    document.addEventListener("DOMContentLoaded", function() {
      renderCalendar(currentYear, currentMonth);
      fetchAppointmentsAndRender(currentYear, currentMonth);
    });

    // function fetchAppointmentsAndRender(year, month) {

    //   var xhr = new XMLHttpRequest();
    //   xhr.open("GET", "../../BackEnd/src/controllers/appointmentController.php", true);

    //   xhr.onload = function() {
    //       if (this.status == 200) {
    //           console.log("Server Response:", this.responseText);
    //           try {
    //               var appointments = JSON.parse(this.responseText);

    //               // Check if appointments is an array and not empty
    //               if (Array.isArray(appointments) && appointments.length > 0) {
    //                   appointments.forEach(function(appointment) {
    //                       let appointmentDate = new Date(appointment.appointment_date);
    //                       if (appointmentDate.getFullYear() === year && appointmentDate.getMonth() === month) {
    //                           let formattedDate = `${appointmentDate.getFullYear()}-${appointmentDate.getMonth() + 1}-${appointmentDate.getDate()}`;
    //                           let cell = document.querySelector(`#calendar-body td[data-date="${formattedDate}"]`);
    //                           if (cell) {
    //                               let details = appointment.details.length > 30 ? appointment.details.substring(0, 27) + '...' : appointment.details;
    //                               cell.innerHTML += `<div>${details}</div>`; // Append the appointment details to the cell
    //                           }
    //                       }
    //                   });
    //               } else {
    //                   // Handle cases where no appointments are found or the response is not an array
    //                   console.log("No appointments found or the response is not an array");
    //                   // Optional: Update the UI to reflect that no appointments are available
    //               }
    //           } catch (e) {
    //               console.error('Error parsing appointments:', e);
    //           }
    //       } else {
    //           console.error("Server returned status:", this.status);
    //       }
    //   };


    //   xhr.onerror = function() {
    //       console.error("Error fetching appointments. Network issue.");
    //   };

    //   xhr.send();
    // }   
    
    function fetchAppointmentsAndRender(year, month) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../../BackEnd/src/controllers/appointmentController.php", true);

    xhr.onload = function() {
        if (this.status == 200) {
            try {
                var appointments = JSON.parse(this.responseText);

                // Clear previous appointment details
                document.querySelectorAll('.appointment-details').forEach(function(detailContainer) {
                    detailContainer.innerHTML = '';
                });

                // Add new appointment details
                appointments.forEach(function(appointment) {
                    let appointmentDate = new Date(appointment.appointment_date);
                    if (appointmentDate.getFullYear() === year && appointmentDate.getMonth() === month) {
                        let formattedDate = `${appointmentDate.getFullYear()}-${(appointmentDate.getMonth() + 1).toString().padStart(2, '0')}-${appointmentDate.getDate().toString().padStart(2, '0')}`;
                        let cell = document.querySelector(`#calendar-body td[data-date="${formattedDate}"] .appointment-details`);
                        if (cell) {
                            let details = appointment.details.length > 30 ? appointment.details.substring(0, 27) + '...' : appointment.details;
                            cell.innerHTML += `<div>${details}</div>`;
                        }
                    }
                });
            } catch (e) {
                console.error('Error parsing appointments:', e);
            }
        } else {
            console.error("Server returned status:", this.status);
        }
    };

    xhr.onerror = function() {
        console.error("Error fetching appointments. Network issue.");
    };

    xhr.send();
}


  </script>
</html>