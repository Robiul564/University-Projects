<?php
$TITLE = "Create Project";
include_once 'helper/db_connection.php';
include_once 'system/header/header.php';
$db = $mm_conn;

// Check if employee IDs were passed
$employee_ids = isset($_POST['employee_ids']) ? $_POST['employee_ids'] : [];
?>

<style>
    .hover-text-dark-bg:hover {
        background-color: #171c28;
        color: white;
    }

    /* From Uiverse.io by adamgiebl */
    .profile-btn {
        font-size: 12px;
        padding: 1em 3.3em;
        cursor: pointer;
        transform: perspective(200px) rotateX(15deg);
        color: white;
        font-weight: 800;
        border: none;
        border-radius: 5px;
        background: linear-gradient(
                0deg,
                rgba(63, 94, 251, 1) 0%,
                rgba(70, 135, 252, 1) 100%
        );
        box-shadow: rgba(63, 94, 251, 0.2) 0px 40px 29px 0px;
        will-change: transform;
        transition: all 0.3s;
        border-bottom: 2px solid rgba(70, 135, 252, 1);
    }

    .profile-btn:hover {
        transform: perspective(180px) rotateX(30deg) translateY(2px);
    }

    .profile-btn:active {
        transform: perspective(170px) rotateX(36deg) translateY(5px);
    }


    /*create button*/
    /* From Uiverse.io by adamgiebl */
    .cssbuttons-io {
        position: relative;
        font-family: inherit;
        font-weight: 500;
        font-size: 18px;
        letter-spacing: 0.05em;
        border-radius: 0.8em;
        cursor: pointer;
        border: none;
        background: linear-gradient(to right, #645373, #5413e4);
        color: ghostwhite;
        overflow: hidden;
    }

    .cssbuttons-io svg {
        width: 1.2em;
        height: 1.2em;
        margin-right: 0.5em;
    }

    .cssbuttons-io span {
        position: relative;
        z-index: 10;
        transition: color 0.4s;
        display: inline-flex;
        align-items: center;
        padding: 0.8em 1.2em 0.8em 1.05em;
    }

    .cssbuttons-io::before,
    .cssbuttons-io::after {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
    }

    .cssbuttons-io::before {
        content: "";
        background: #0b5ed7;
        width: 120%;
        left: -10%;
        transform: skew(30deg);
        transition: transform 0.4s cubic-bezier(0.3, 1, 0.8, 1);
    }

    .cssbuttons-io:hover::before {
        transform: translate3d(100%, 0, 0);
    }

    .cssbuttons-io:active {
        transform: scale(0.95);
    }

</style>
<body data-bs-theme="dark">
<div class="container">
    <div class="row w-100">
        <!-- Sidebar Section -->
        <div class="col-12 col-md-3 col-lg-3 p-4">
            <div class="logo">
                <img src="/assets/img/logo/logo.png" alt="OrgNet Logo">
                <h1 class="text-white fw-bold">OrgaNet</h1>
            </div>
            <nav>
                <ul class="text-white">
                    <li class="home">
                        <div class="img_div"><img src="/assets/img/home.png" width="20px"> <a
                                    href="/dashboard.php"><span>Home</span></a></div>
                    </li>
                    <li><a href="employeeList.php">
                            <div class="img_div"><img src="/assets/img/user.png" width="20px">
                                <span>Employee List</span></div>
                        </a></li>
                    <li><a href="create_project_form.php">
                            <div class="img_div"><img src="/assets/img/new-project.png" width="20px"> <span>Add New Project</span>
                            </div>
                        </a></li>
                </ul>
            </nav>
            <div>
                <button class="profile-btn" onclick="Profile()">Profile</button>
                <a class="profile-btn" href="/logout.php" style="text-decoration: none">Logout</a>
            </div>


        </div>

        <!-- Main Content Section -->
        <div class="col-12 col-md-9 col-lg-9">
            <div class="card border-0  bg-transparent ps-4 text-white">
                <h2 class="my-4" style="margin-left: 250px"> Create a New Project</h2>
                <form method="POST" action="api/or_createProject.php">
                    <div class="row d-flex flex-row">
                        <!-- Left Section -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="projectName" class="form-label">Project Name</label>
                                <input type="text" class="form-control bg-dark text-light" id="projectName"
                                       name="projectName" placeholder="Enter project name" required>
                            </div>

                            <div class="mb-3">
                                <label for="projectDescription" class="form-label">Project Description</label>
                                <textarea class="form-control bg-dark text-light" id="projectDescription"
                                          name="projectDescription" rows="4"
                                          placeholder="Write a brief description of the project" required></textarea>
                            </div>

                            <!-- Admin Dropdown -->
                            <div class="mb-4">
                                <h4 class="card-title">Assign Project Manager</h4>
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary dropdown-toggle w-100" type="button"
                                            id="adminDropdownMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                        Select Admin
                                    </button>
                                    <ul class="dropdown-menu w-100 bg-dark text-light"
                                        aria-labelledby="adminDropdownMenu">
                                        <?php
                                        foreach ($employee_ids as $id) {
                                            $user_query = "SELECT * FROM user_type WHERE id = $id";
                                            $user_result = mysqli_query($db, $user_query);
                                            if ($user = mysqli_fetch_assoc($user_result)) {
                                                echo '<li class="dropdown-item d-flex justify-content-between align-items-center text-light hover-text-dark-bg">';
                                                echo htmlspecialchars($user['Name']);
                                                echo '<div><input type="radio" name="admin" value="' . $user['id'] . '" class="admin-radio ms-2 hover-text-dark-bg" data-user-id="' . $user['id'] . '" data-name="' . htmlspecialchars($user['Name']) . '"></div>';
                                                echo '</li>';
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>

                            <!-- Developer Dropdown -->
                            <div class="mb-4">
                                <h4 class="card-title">Assign Developer(s)</h4>
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary dropdown-toggle w-100" type="button"
                                            id="developerDropdownMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                        Select Users
                                    </button>
                                    <ul class="dropdown-menu w-100 bg-dark text-light"
                                        aria-labelledby="developerDropdownMenu">
                                        <?php
                                        foreach ($employee_ids as $id) {
                                            $user_query = "SELECT * FROM user_type WHERE id = $id";
                                            $user_result = mysqli_query($db, $user_query);
                                            if ($user = mysqli_fetch_assoc($user_result)) {
                                                echo '<li class="dropdown-item d-flex justify-content-between align-items-center text-light hover-text-dark-bg ">';
                                                echo htmlspecialchars($user['Name']);
                                                echo '<div><input type="checkbox" name="users[]" value="' . $user['id'] . '" class="developer-checkbox ms-2 hover-text-dark-bg" data-user-id="' . $user['id'] . '" data-name="' . htmlspecialchars($user['Name']) . '"></div>';
                                                echo '</li>';
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control bg-dark text-light" id="startDate"
                                       name="startDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control bg-dark text-light" id="endDate" name="endDate"
                                       required>
                            </div>
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select bg-dark text-light" id="priority" name="priority" required>
                                    <option selected disabled>Select priority</option>
                                    <option value="3">Important</option>
                                    <option value="2">Medium</option>
                                    <option value="1">Regular</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>

                        <!--                        <button type="submit" class="btn btn-primary px-5 py-2 my-5" style="margin-left:0;">Create-->
                        <!--                            Project-->
                        <!--                        </button>-->


                        <button class="cssbuttons-io" type="submit" style="margin-left:300px;margin-top: 80px">
  <span
  ><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path d="M0 0h24v24H0z" fill="none"></path>
      <path
              d="M24 12l-5.657 5.657-1.414-1.414L21.172 12l-4.243-4.243 1.414-1.414L24 12zM2.828 12l4.243 4.243-1.414 1.414L0 12l5.657-5.657L7.07 7.757 2.828 12zm6.96 9H7.66l6.552-18h2.128L9.788 21z"
              fill="currentColor"
      ></path>
    </svg>
    Create Project </span
  >
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.admin-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            const selectedAdminId = this.getAttribute('data-user-id');
            document.querySelectorAll('.developer-checkbox').forEach(checkbox => {
                if (checkbox.getAttribute('data-user-id') === selectedAdminId) {
                    checkbox.checked = false;
                    checkbox.disabled = true;
                } else {
                    checkbox.disabled = false;
                }
            });
        });
    });

    document.querySelectorAll('.developer-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const selectedDeveloperId = this.getAttribute('data-user-id');
            if (this.checked) {
                document.querySelectorAll('.admin-radio').forEach(radio => {
                    if (radio.getAttribute('data-user-id') === selectedDeveloperId) {
                        radio.checked = false;
                    }
                });
            }
        });
    });

    function validateDates() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        // If the end date is before the start date
        if (new Date(endDate) <= new Date(startDate)) {
            alert('End Date cannot be before Start Date!');
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }

    // Add event listener to the form submission
    const form = document.querySelector('form');
    form.addEventListener('submit', function (event) {
        // If validation fails, prevent form submission
        if (!validateDates()) {
            event.preventDefault();
        }
    });

    function Profile() {
        window.location.href = "/profile.php";

    }


    // admin dropdown
    document.querySelectorAll('.admin-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            const selectedAdminName = this.getAttribute('data-name');
            const adminButton = document.getElementById('adminDropdownMenu');
            adminButton.textContent = selectedAdminName; // Update the button text with the selected admin's name
        });
    });



// dev dropdown
    const developerButton = document.getElementById('developerDropdownMenu');
    const selectedDevelopers = [];

    document.querySelectorAll('.developer-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const developerName = this.getAttribute('data-name');
            if (this.checked) {
                // Add developer's name to the selected list
                selectedDevelopers.push(developerName);
            } else {
                // Remove developer's name from the selected list
                const index = selectedDevelopers.indexOf(developerName);
                if (index > -1) {
                    selectedDevelopers.splice(index, 1);
                }
            }

            // Update the button text with the selected developers' names, without changing the button height
            if (selectedDevelopers.length > 0) {
                developerButton.textContent = selectedDevelopers.join(', ') + (selectedDevelopers.length > 1 ? ' (' + selectedDevelopers.length + ')' : '');
            } else {
                developerButton.textContent = 'Select Users'; // Default text
            }
        });
    });
</script>
</body>
