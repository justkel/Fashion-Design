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
  <link rel="stylesheet" href="../Customer/create.css" />
  <link rel="stylesheet" href="../style.css" />
  <link rel="stylesheet" href="../logout.css">
<title>Taylur|Projects</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background: #f4f4f4;
    margin: 0;
    padding: 0;
  }
  #board {
    display: flex;
    justify-content: center;
    height: 100vh;
    align-items: flex-start;
    padding-top: 50px;
  }
  .column {
    background: #5E4B8B;
    width: 300px;
    margin: 0 10px;
    border-radius: 7px;
    padding: 10px;
  }
  .column-header {
    text-align: center;
    padding: 10px;
    font-weight: bold;
    color: #F5F5F5;
  }
  .task {
    background: #fff;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 3px;
    cursor: pointer;
  }
  .task.dragging {
    opacity: 0.5;
  }

  .add-btn {
    border-radius: 20px;
    padding: 7px 10px;
    cursor: pointer;
    margin-bottom: 20PX;
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
            <button type="submit" name="logout" class='logout-button' class='logout-button'><i class='bx bx-log-out'></i></button>
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


    <div id="board">
      <div class="column" ondrop="drop(event)" ondragover="allowDrop(event)">
        <div class="column-header">To Do</div>
        <button class="add-btn" onclick="addTask(event, 'To Do')" style="background-color: #E7A9BE;">+ Add a task</button>
      </div>
      <div class="column" ondrop="drop(event)" ondragover="allowDrop(event)">
        <div class="column-header">Doing</div>
        <!-- <button onclick="addTask(event, 'Doing')">+ Add a task</button> -->
      </div>
      <div class="column" ondrop="drop(event)" ondragover="allowDrop(event)">
        <div class="column-header">Done</div>
        <!-- <button onclick="addTask(event, 'Done')">+ Add a task</button> -->
      </div>
    </div>
  </section>    

<script>
let idCounter = 0;

function allowDrop(ev) {
  ev.preventDefault();
}

function drag(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev) {
  ev.preventDefault();
  var data = ev.dataTransfer.getData("text");
  ev.target.appendChild(document.getElementById(data));
}

function addTask(ev, column) {
  const taskText = prompt("Enter the task description:");
  if (taskText) {
    const newTask = document.createElement("div");
    newTask.classList.add("task");
    newTask.id = "task-" + idCounter++;
    newTask.draggable = true;
    newTask.ondragstart = drag;
    newTask.innerText = taskText;
    ev.target.parentNode.appendChild(newTask);
    newTask.style.cssText = 'background-color: #F5F5F5; color: #000000; '
  }
}

function navigateToRoot() {
    window.location.href = "../Landing-Page/root.html";
}

let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".bx-menu");
console.log(sidebarBtn);
sidebarBtn.addEventListener("click", ()=>{
  sidebar.classList.toggle("close");
});
</script>

</body>
</html>