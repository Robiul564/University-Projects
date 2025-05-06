<?php
$TITLE = "Edit Project";

include_once 'helper/db_connection.php';
include_once 'system/header/header.php';
$db = $mm_conn;

if (isset($_GET['project_id']) && is_numeric($_GET['project_id'])) {
    $project_id = intval($_GET['project_id']);

    $query = "SELECT * FROM project WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $project_details = $result->fetch_assoc();
    } else {
        echo "<script>alert('Project not found'); window.location.href='dashboard.php';</script>";
        exit;
    }
    $stmt->close();
} else {
    echo "<script>alert('Invalid Project'); window.location.href='dashboard.php';</script>";
    exit;
}

// Fetch all users and their roles in the current project
function fetchAllUsersWithRoles($db, $project_id)
{
    $query = "SELECT ut.id, ut.Name, ad.assigned_as
              FROM user_type ut
              LEFT JOIN assigned_dev ad ON ut.id = ad.User_ID AND ad.Project_ID = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

$allUsers = fetchAllUsersWithRoles($db, $project_id);
?>

<style>
    .manager-text { color: #DF6D2D; }
    .developer-text { color: #638C6D; }
    .dropdown-menu {
        max-height: 170px;
        overflow-y: auto;
        background-color: #2C3E50; /* Darker background for dropdown */
    }
    .text-white { color: #FFFFFF !important; }

    .btn-outline-primary {
        color: #FFFFFF;
        border-color: #bec7cd;

    }
    .btn-outline-primary:hover {
        background-color: #0c76ed;
        border-color: #176fd5;
    }
    .dropdown-item {
        padding: 0.5rem 1rem; /* Adjust padding for better fit */
    }
    .container {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.7); /* Darker shadow */
    }
    .dropdown-item:hover {
        background-color: inherit; /* Remove hover effect */
    }


    .dropdown-menu > li {
        border-bottom: 1px dotted #e9ecef; /* Adds a line between items */
    }

    .dropdown-menu > li:last-child {
        border-bottom: none; /* Removes border after the last item */
    }
</style>

<body data-bs-theme="dark">

<div class="container d-flex flex-column justify-content-evenly">
    <h2 class="text-center text-white my-3">Update The Project</h2>
    <!-- Project Form -->
    <div class="p-2">
        <form action="/update_project.php" method="POST">
            <input type="hidden" name="project_id" value="<?=htmlspecialchars($project_details['id'])?>">

            <div class="mb-3">
                <label for="projectName" class="form-label text-white">Project Name</label>
                <input type="text" class="form-control bg-dark text-white" id="projectName" name="project_name"
                       value="<?=htmlspecialchars($project_details['Name'])?>" required>
            </div>

            <div class="mb-3">
                <label for="projectDescription" class="form-label text-white">Project Description</label>
                <textarea class="form-control bg-dark text-light" id="projectDescription"
                          name="project_description" required>
                    <?=htmlspecialchars($project_details['Project_Description'])?>
                </textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="startDate" class="form-label text-white">Start Date</label>
                    <input type="date" class="form-control bg-dark text-white" id="startDate" name="start_date"
                           value="<?=$project_details['Start_Date']?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="endDate" class="form-label text-white">End Date</label>
                    <input type="date" class="form-control bg-dark text-white" id="endDate" name="end_date"
                           value="<?=$project_details['End_Date']?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label text-white">Priority</label>
                <select class="form-select bg-dark text-light" id="priority" name="priority" required>
                    <option value="1" <?=$project_details['Priority'] == '1' ? 'selected' : ''?>>Low</option>
                    <option value="2" <?=$project_details['Priority'] == '2' ? 'selected' : ''?>>Medium</option>
                    <option value="3" <?=$project_details['Priority'] == '3' ? 'selected' : ''?>>High</option>
                </select>
            </div>

            <div class="my-3 text-center">
                <h5 class="mb-2 my-4 text-white">For Current Projects</h5>
                <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="d-flex align-items-center me-3">
                        <i class="bi bi-person-badge" style="font-size: 1rem; color: #DF6D2D;"></i>
                        <span class="ms-2" style="font-size: 14px; color: #DF6D2D;">Orange = Manager</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-check" style="font-size: 1rem; color: #638C6D;"></i>
                        <span class="ms-2" style="font-size: 14px; color: #638C6D;">Green = Developer</span>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle w-100" type="button"
                            id="managerDropdownMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Manager
                    </button>
                    <ul class="dropdown-menu w-100 bg-dark-subtle" aria-labelledby="managerDropdownMenu">
                        <?php foreach ($allUsers as $user): ?>
                            <li class="dropdown-item d-flex justify-content-between align-items-center w-100">
                                <div class="<?= $user['assigned_as'] === 'Project Manager' ? 'manager-text' : 'text-white' ?>" style="width: 90%;">
                                    <?= htmlspecialchars($user['Name']) ?>
                                </div>
                                <input type="radio" name="manager" value="<?= $user['id'] ?>"
                                       class="manager-radio ms-2" data-user-id="<?= $user['id'] ?>"
                                    <?= $user['assigned_as'] === 'Project Manager' ? 'checked' : '' ?>>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="mb-3">
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle w-100" type="button"
                            id="developerDropdownMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Developers
                    </button>
                    <ul class="dropdown-menu w-100 bg-dark-subtle " aria-labelledby="developerDropdownMenu">
                        <?php foreach ($allUsers as $user): ?>
                            <li class="dropdown-item d-flex justify-content-between align-items-center w-100">
                                <div class="<?= $user['assigned_as'] === 'Developer' ? 'developer-text' : 'text-white' ?>" style="width: 90%;">
                                    <?= htmlspecialchars($user['Name']) ?>
                                </div>
                                <input type="checkbox" name="users[]" value="<?= $user['id'] ?>"
                                       id="user-<?= $user['id'] ?>" class="developer-checkbox ms-2 "
                                       data-user-id="<?= $user['id'] ?>" <?= !empty($user['assigned_as']) ? 'checked' : '' ?>>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="d-flex justify-content-center p-3">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>

    function validateDates() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
            alert("End date cannot be earlier than start date.");
            return false;
        }
        return true;
    }


    const projectForm = document.querySelector('form');
    projectForm.addEventListener('submit', function (event) {
        if (!validateDates()) {
            event.preventDefault();
        }
    });

    // Disable the checkbox for the selected manager
    document.querySelectorAll('.manager-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            const selectedManagerId = this.value;
            document.querySelectorAll('.developer-checkbox').forEach(checkbox => {
                if (checkbox.value === selectedManagerId) {
                    checkbox.disabled = true;
                } else {
                    checkbox.disabled = false;
                }
            });
        });
    });

    // Trigger change event on page load if a manager is already selected
    document.querySelectorAll('.manager-radio').forEach(radio => {
        if (radio.checked) {
            radio.dispatchEvent(new Event('change'));
        }
    });

    // Disable the checkbox for the selected manager and uncheck it
    document.querySelectorAll('.manager-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            const selectedManagerId = this.value;

            document.querySelectorAll('.developer-checkbox').forEach(checkbox => {
                if (checkbox.value === selectedManagerId) {
                    checkbox.disabled = true;
                    checkbox.checked = false;
                } else {
                    checkbox.disabled = false;
                }
            });
        });
    });

    // Trigger change event again to ensure consistency
    document.querySelectorAll('.manager-radio').forEach(radio => {
        if (radio.checked) {
            radio.dispatchEvent(new Event('change'));
        }
    });
</script>

</body>