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

    function renderCalendar(year, month) {
      calendarBody.innerHTML = "";
      const monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];

      const firstDay = new Date(year, month).getDay();
      const daysInMonth = 32 - new Date(year, month, 32).getDate();

      let date = 1;
      for (let i = 0; i < 6; i++) {
        let row = document.createElement("tr");

        for (let j = 0; j < 7; j++) {
          if (i === 0 && j < firstDay) {
            let cell = document.createElement("td");
            cell.classList.add("empty");
            row.appendChild(cell);
          } else if (date > daysInMonth) {
            break;
          } else {
            let cell = document.createElement("td");
            cell.textContent = date;
            if (
              date === today.getDate() &&
              year === today.getFullYear() &&
              month === today.getMonth()
            ) {
              cell.classList.add("today");
            }
            cell.onclick = function () {
              addAppointment(this);
            };
            row.appendChild(cell);
            date++;
          }
        }

        calendarBody.appendChild(row);
      }

      monthAndYear.textContent = `${monthNames[month]} ${year}`;
    }

    function addAppointment(td) {
      let appointment = prompt(
        "Add appointment details for " +
          td.textContent +
          " " +
          monthAndYear.textContent +
          ":",
        ""
      );
      
      // Limit the appointment text to 100 characters
      if (appointment && appointment.length > 30) {
        appointment = appointment.substring(0, 15) + '...'; // Truncate and add ellipsis
      }
      
      if (appointment) {
        td.innerHTML = `${td.textContent}<div>${appointment}</div>`;
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
  </script>
</html>