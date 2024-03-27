function loadUserMeasurements() {
    const userSelect = document.getElementById('user');
    const selectedUser = userSelect.options[userSelect.selectedIndex];
    const gender = selectedUser.getAttribute('data-gender');
    const selectedValue = selectedUser.value;

    document.getElementById('male-measurements').style.display = "none";
  
    document.getElementById('female-measurements').style.display = "none";


    // Show spinner
    document.getElementById('spinner-container').classList.remove('hidden');

    // Disable select and submit button during loading
    userSelect.disabled = true;
    document.getElementById('submit-btn').disabled = true;

    setTimeout(function() {
        // Hide spinner
        document.getElementById('spinner-container').classList.add('hidden');

        // Enable select and submit button after loading
        userSelect.disabled = false;
        document.getElementById('submit-btn').disabled = false;


        if (gender === 'male') {
            document.getElementById('male-measurements').style.display = 'flex';
            document.getElementById('male-measurements').classList.add('male-add');
        } else if (gender === 'female') {
            document.getElementById('female-measurements').style.display = 'flex';
            document.getElementById('female-measurements').classList.add('female-add');
        }

        // Show the appropriate measurements div based on gender
        // if (selectedValue === '2') {
        //     document.getElementById('male-measurements').style.display = 'flex';
        //     document.getElementById('male-measurements').classList.add('male-add');
        //   } else {
        //     document.getElementById('female-measurements').style.display = 'flex';
        //     document.getElementById('female-measurements').classList.add('female-add');
        //   }

        // Show submit button
        document.getElementById('submit-btn').classList.remove('hidden');
    }, 5000);
}

let sidebar = document.querySelector(".sidebar");
    let sidebarBtn = document.querySelector(".bx-menu");
    console.log(sidebarBtn);
    sidebarBtn.addEventListener("click", () => {
    sidebar.classList.toggle("close");
});
