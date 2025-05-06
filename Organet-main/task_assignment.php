<?php
$TITLE = "Task Assignment";
include_once 'helper/db_connection.php';
include_once 'system/header/header.php';
$db = $mm_conn;
?>
<style>
    /*btn-css-start*/

    .btn_submit {
        font-size: 16px;
        padding: 1em 2.7em;
        font-weight: 500;
        background: #1f2937;
        color: white;
        border: none;
        position: relative;
        overflow: hidden;
        border-radius: 0.6em;
        cursor: pointer;
    }

    .gradient {
        position: absolute;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        border-radius: 0.6em;
        margin-top: -0.25em;
        background-image: linear-gradient(
                rgba(0, 0, 0, 0),
                rgba(0, 0, 0, 0),
                rgba(0, 0, 0, 0.3)
        );
    }

    .label_submit {
        position: relative;
        top: -1px;
    }

    .transition {
        transition-timing-function: cubic-bezier(0, 0, 0.2, 1);
        transition-duration: 500ms;
        background-color: rgba(16, 185, 129, 0.6);
        border-radius: 9999px;
        width: 0;
        height: 0;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .btn_submit:hover .transition {
        width: 30em;
        height: 3em;
        border-radius: 0.6em;
    }

    .btn_submit:active {
        transform: scale(0.97);
    }

    /*btn-css-end*/


    .hover-highlight:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
        cursor: pointer;

    }

    .switch {
        font-size: 17px;
        position: relative;
        display: inline-block;
        width: 1.2em;
        height: 3.3em;
    }

    /* Hide default HTML checkbox */
    .switch .chk {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* slider css */
    .tgl {
        position: relative;
        display: inline-block;
        width: 3em; /* Reduced width */
        height: 0.6em; /* Reduced height */
    }

    .tgl input {
        display: none;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #c65a5a;
        transition: .4s;
        border-radius: 15px; /* Reduced border radius */
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 2em; /* Reduced height */
        width: 0.8em; /* Reduced width */
        border-radius: 10px; /* Reduced border radius */
        left: 0.2em; /* Adjusted position */
        bottom: -0.7em; /* Adjusted position */
        background-color: #ffffff;
        box-shadow: 0px 0px 3px #0009; /* Reduced shadow */
        transition: 1s cubic-bezier(0.49, -1.3, 0.45, 2.44);
    }

    .tgl input:checked + .slider {
        background-color: #2cb16b;
    }

    .tgl input:checked + .slider:before {
        transform: translateX(1.5em) rotateZ(-180deg); /* Adjusted translation */
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<body data-bs-theme="dark">
<div class="container py-3">
    <div class="row" style="max-height: 85vh;width: 100%;">
        <!-- Left Section -->
        <div class="col-lg-7">
            <div class="left-section p-2 rounded shadow">
                <?php
                if (isset($_GET['project_id']) && is_numeric($_GET['project_id'])) {
                    $project_id = intval($_GET['project_id']);
                    $query = "SELECT * FROM project WHERE id = $project_id";
                    $result = mysqli_query($db, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        $project = mysqli_fetch_assoc($result);
                        ?>
                        <!-- Project Info Div -->
                        <div class="project-info text-start">
                            <h2 class="text-warning text-center"><?= htmlspecialchars($project['Name']); ?></h2>
                            <p><strong>Project ID:</strong> <?= htmlspecialchars($project['id']); ?></p>
                            <p><strong>Description:</strong> <?= htmlspecialchars($project['Project_Description']); ?>
                            </p>
                            <p><strong>Start Date:</strong> <?= date("F j, Y", strtotime($project['Start_Date'])); ?>
                            </p>
                            <p><strong>End Date:</strong> <?= date("F j, Y", strtotime($project['End_Date'])); ?></p>
                            <p>
                                <strong>Priority:</strong>
                                <?php
                                $priority = ['Low', 'Medium', 'High'];
                                echo "<span class='text-" . ['success', 'warning', 'danger'][$project['Priority'] - 1] . "'>" . $priority[$project['Priority'] - 1] . "</span>";
                                ?>
                            </p>
                            <p>
                                <strong>Status:</strong>
                                <span class="p-1 rounded m-2 fs-5 text-bg-<?= $project['is_Active'] ? 'danger' : 'success'; ?>">
                                    <?= $project['is_Active'] ? 'Active' : 'Complete'; ?>
                                </span>

                                <?php
                                $query = "SELECT * FROM project WHERE id = $project_id";
                                $result = mysqli_query($db, $query);
                                if ($result && mysqli_num_rows($result) > 0) {
                                    $project_details = mysqli_fetch_assoc($result);
                                    $is_active = $project_details['is_Active'];
                                    echo "
<div class='switch' style='margin-top: 30px;'>
  
  <label class='tgl' for='projectStatusSwitch'>
    <input type='checkbox' id='projectStatusSwitch' " . ($is_active == 1 ? '' : 'checked') . ">
    <span class='slider'></span>
</label>
</div>
                             

                              ";

                                    // Script to handle the toggle action using AJAX
                                    echo "
                                 <script>
                                   document.getElementById('projectStatusSwitch').addEventListener('change', function() {
                                   var status = this.checked ? 0 : 1; // If checked (right), it's done (0), if unchecked (left), it's active (1)

                                   // Send AJAX request to update the status in the database
                                   var xhr = new XMLHttpRequest();
                                   xhr.open('POST', 'update_project_status.php', true);
                                   xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                   xhr.onload = function() {
                                   if (xhr.status == 200) {
                                   alert('Project status updated successfully!');
                                   location.reload(); // Reload the page to reflect changes
                                                      }
                                 };
                           xhr.send('project_id=" . $project_id . "&is_active=' + status);
                              });
                               </script>
                             ";
                                }
                                ?>


                            </p>
                        </div>
                        <!--employee List-->
                        <div class="employee-list my-0 p-2">
                            <h3 class="mb-3">Employee List</h3>
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-bordered text-light text-center">
                                    <thead>
                                    <tr>
                                        <th>Profile</th>
                                        <th>Name</th>
                                        <th>Assigned As</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $employee_query = "
    SELECT ad.user_ID, ut.Name, ut.Email, ut.Picture, ad.assigned_as
    FROM assigned_dev ad
    JOIN user_type ut ON ad.user_ID = ut.id
    WHERE ad.Project_ID = $project_id
    ORDER BY
        CASE
            WHEN ad.assigned_as = 'Project Manager' THEN 1
            ELSE 2
        END,
        ut.Name ASC;
";
                                    $employee_result = mysqli_query($db, $employee_query);
                                    while ($employee = mysqli_fetch_assoc($employee_result)) {
                                        ?>
                                        <tr style="cursor: pointer; background-color: rgba(0, 0, 0, 0.5);">
                                            <td>
                                                <a href="profile.php?id=<?= $employee['user_ID']; ?>">
                                                    <img src="<?= htmlspecialchars($employee['Picture']); ?>"
                                                         alt="<?= htmlspecialchars($employee['Name']); ?>"
                                                         width="50" height="50" class="rounded-circle">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="profile.php?id=<?= $employee['user_ID']; ?>"
                                                   class="text-light">
                                                    <?= htmlspecialchars($employee['Name']); ?>
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($employee['assigned_as']); ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo "<h1>Project Not Found</h1>";
                    }
                } else {
                    echo "<h1>Invalid Project ID</h1>";
                }
                ?>
            </div>
        </div>

        <!-- Right Section -->
        <div class="col-lg-5">
            <div class="right-section p-3 text-light rounded shadow">
                <h2 class="text-center">Assign Task</h2>
                <form action="api/or_assign_task.php" method="POST" id="taskForm">
                    <!-- Select User -->
                    <div class="mb-3">
                        <label for="userDropdown" class="form-label">Assign To</label>
                        <div class="dropdown">
                            <button
                                    id="userDropdown"
                                    class="btn btn-dark dropdown-toggle w-100 border-1 border-warning-subtle  from-control "
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                Select Users
                            </button>
                            <ul class="dropdown-menu p-3 text-center bg-dark-subtle"
                                style="max-height: 150px; min-width: 100%; overflow-y: auto;">
                                <?php
                                $users_query = "SELECT ad.user_ID, ut.Name, ut.Email, ut.Picture, ad.assigned_as
                FROM assigned_dev ad
                JOIN user_type ut ON ad.user_ID = ut.id
                WHERE ad.Project_ID = $project_id
                ORDER BY
                    CASE
                        WHEN ad.assigned_as = 'Project Manager' THEN 1
                        ELSE 2
                    END,
                    ut.Name ASC;";
                                $users_result = mysqli_query($db, $users_query);
                                while ($user = mysqli_fetch_assoc($users_result)) {
                                    echo "
                <li class='form-check hover-highlight '>
                    <input 
                        class='form-check-input user-checkbox' 
                        type='checkbox' 
                        id='user_{$user['user_ID']}' 
                        name='user_ids[]' 
                        value='{$user['user_ID']}'>
                    <label class='form-check-label' for='user_{$user['user_ID']}'>
                        " . htmlspecialchars($user['Name']) . "
                    </label>
                </li>";
                                }
                                ?>
                            </ul>
                        </div>

                    </div>


                    <div>
                        <input type="hidden" name="project_id" value="<?= $project_id; ?>">
                    </div>


                    <div class="mb-3">
                        <label for="taskName" class="form-label">Task Name</label>
                        <input type="text" id="taskName" name="task_name" class="form-control text-light bg-dark "
                               placeholder="Enter task name" required>
                    </div>


                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Task Description</label>
                        <textarea
                                name="taskDescription"
                                id="taskDescription"
                                class="form-control taskDescription"
                                rows="4"
                                required>
                        </textarea>
                    </div>


                    <div class="mb-3">
                        <label for="taskStartDate" class="form-label">Task Start Date</label>
                        <input type="date" id="taskStartDate" name="task_start_date"
                               class="form-control bg-dark text-light" required>
                    </div>


                    <div class="mb-3">
                        <label for="taskEndDate" class="form-label">Task Deadline</label>
                        <input type="date" id="taskEndDate" name="task_end_date" class="form-control bg-dark text-light"
                               required>
                    </div>


                    <div class="d-grid">
                        <!--                        <button type="submit" class="btn btn-primary">Assign Task</button>-->

                        <button class="btn_submit" type="submit">
                            <span class="transition"></span>
                            <span class="gradient"></span>
                            <span class="label_submit">Assign Task</span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function () {
        $(".taskDescription").summernote({
            height: 150,
        });
        $('.dropdown-toggle').dropdown();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('#taskForm');
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.statusCode === 200) {
                    alert(result.message);
                    window.location.reload();
                } else {
                    alert(result.message);
                }
            } catch (error) {
                alert('An unexpected error occurred. Please try again later.');
                console.error(error);
            }
        });
    });


    document.addEventListener('DOMContentLoaded', function () {
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        const userDropdown = document.getElementById('userDropdown');
        const selectedUsersDiv = document.getElementById('selectedUsers');

        userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                // Get all checked checkboxes
                const selectedUsers = [];
                userCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        selectedUsers.push(checkbox.nextElementSibling.textContent.trim());
                    }
                });

                // Update the button text and the selected users list
                if (selectedUsers.length > 0) {
                    userDropdown.textContent = selectedUsers.join(', ') + ' (Click to change)';
                } else {
                    userDropdown.textContent = 'Select Users';
                }

            });
        });
    });


</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
