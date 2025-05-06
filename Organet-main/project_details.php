<?php
$TITLE = "Project Details";
include_once 'helper/db_connection.php';
include_once 'system/header/header.php';
$db = $mm_conn;


$project_query = "SELECT id, Name FROM project ORDER BY Priority DESC, is_Active DESC, Start_Date DESC, End_Date DESC";
$project_result = mysqli_query($db, $project_query);
$project_id = 0;
?>

<style>


    #taskStatusChart {
        max-width: 800px;
        max-height: 600px;
        margin: 0 auto;
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


    /*button css end*/
    /* Ensure dropdown submenu appears on hover */
    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu .dropdown-menu {
        display: none;
        position: absolute;
        top: 0;
        left: 100%;
    }

    /* Show the submenu when hovering on the parent item */
    .dropdown-submenu:hover .dropdown-menu {
        display: block;
    }

    /* Hover effect for the main project item */
    .dropdown-submenu > .dropdown-item:hover {
        background-color: #007bff; /* Blue background */
        color: white; /* Change text color to white */
    }

    /* Hover effect for submenu items */
    .dropdown-submenu .dropdown-menu .dropdown-item:hover {
        background-color: #0056b3; /* Darker blue for submenu items */
        color: white; /* Text color turns white on hover */
    }

    /* Optional: Smooth transition for hover effects */
    .dropdown-item, .dropdown-submenu .dropdown-item {
        transition: background-color 0.2s, color 0.2s;
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


<body data-bs-theme="dark">
<div class="container_dashboard " style="display: flex;width: 83%">
    <div class="sidebar">
        <div class="logo">
            <img src="/assets/img/logo/logo.png" alt="OrgNet Logo">
            <h1 class="text-white fw-bold">OrgaNet</h1>
        </div>
        <nav>
            <ul class="text-white">
                <li class="home">
                    <div class="img_div"><img src="/assets/img/home.png" width="20px"><a href="/dashboard.php"><span>Home</span></a>
                    </div>
                </li>
                <li><a href="employeeList.php">
                        <div class="img_div"><img src="/assets/img/user.png" width="20px"> <span>Employee List</span>
                        </div>
                    </a></li>
                <li><a href="create_project_form.php">
                        <div class="img_div"><img src="/assets/img/new-project.png" width="20px">
                            <span>Add New Project</span>
                        </div>
                    </a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarProjectsDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Projects
                    </a>
                    <ul class="dropdown-menu dropend bg-body-tertiary" aria-labelledby="navbarProjectsDropdown">
                        <?php while ($project = mysqli_fetch_assoc($project_result)): ?>
                            <!--                        $project_id = --><?php //=htmlspecialchars($project['id'])?><!--;-->
                            <li class="dropdown-submenu ">
                                <a class="dropdown-item dropdown-toggle text-light" data-bs-toggle="dropdown"
                                   href="project_details.php?project_id=<?= $project['id'] ?>"><?= htmlspecialchars($project['Name']) ?></a>
                                <ul class="dropdown-menu bg-body-tertiary text-light">
                                    <li><a class="dropdown-item text-light"
                                           href="/task_assignment.php?project_id=<?= $project['id']; ?>">Task List</a>
                                    </li>
                                    <li><a class="dropdown-item text-light"
                                           href="project_details.php?project_id=<?= $project['id'] ?>">Details</a>
                                    </li>
                                </ul>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </li>
            </ul>
        </nav>
        <div>

            <button class="profile-btn " onclick="Profile()">Profile</button>

            <!--            <button class="profile-btn " onclick="Profile()">Profile</button>-->
            <a class="profile-btn" href="/logout.php" style="text-decoration: none">Logout</a>
        </div>
    </div>

    <div id="main_bar_div " style="display: flex;width: 100%">
        <main class="main-content">
            <header class="dashboard-header">
                <h1 class="text-light mb-4">Project Details</h1>

                <a class="text-light "
                   href="edit_project.php?project_id=<?= isset($_GET['project_id']) ? intval($_GET['project_id']) : '' ?>">
                    <button class="btn btn-warning ">Edit Project</button>
                </a>
            </header>
            <div style="display: flex;width: 100%;flex-direction: column;align-items: center;justify-content: center;">
                <section class="dashboard-body d-flex flex-row"
                         style="width:100%;flex-direction: column;align-items: center;justify-content: center;">
                    <div class="project_view  px-3 mx-3">
                        <?php

                        if (isset($_GET['project_id']) && is_numeric($_GET['project_id'])) {
                        $project_id = intval($_GET['project_id']);


                        $query = "SELECT * FROM project WHERE id = $project_id";
                        $result = mysqli_query($db, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                        $project_details = mysqli_fetch_assoc($result);

                        echo "<h2 class='text-warning'>" . htmlspecialchars($project_details['Name']) . "</h2>";
                        echo "<p class='text-light'><strong>Project ID:</strong> " . htmlspecialchars($project_details['id']) . "</p>";
                        echo "<p class='text-light'><strong>Project Description:</strong> " . htmlspecialchars($project_details['Project_Description']) . "</p>";
                        echo "<p class='text-light'><strong>Project Start Date:</strong> " . date("F j, Y", strtotime($project_details['Start_Date'])) . "</p>";
                        echo "<p class='text-light'><strong>Project End Date:</strong> " . date("F j, Y", strtotime($project_details['End_Date'])) . "</p>";

                        $priority_level = $project_details['Priority'];
                        if ($priority_level == 3) {
                            echo "<p class='text-light'><strong>Project priority:</strong> <span class='text-danger'>High</span> </p>";
                        } elseif ($priority_level == 2) {
                            echo "<p class='text-light'><strong>Project priority:</strong> <span class='text-warning'>Medium</span> </p>";
                        } elseif ($priority_level == 1) {
                            echo "<p class='text-light'><strong>Project priority:</strong> <span class='text-success'>Low</span> </p>";
                        }

                        $is_active = $project_details['is_Active'];
                        if ($is_active == 1) {
                            echo "<p class='text-light'><strong>Project Status :</strong><span class='text-danger fs-4'> Active</span> </p>";
                        } else {
                            echo "<p class='text-light'><strong>Project Status :</strong><span class='text-success fs-4'> Complete</span> </p>";
                        }
                        echo "


<label class='tgl' for='projectStatusSwitch'>
    <input type='checkbox' id='projectStatusSwitch' " . ($is_active == 1 ? '' : 'checked') . ">
    <span class='slider'></span>
</label>


                            
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
                        ?>
                    </div>

                    <?php

                    $total_tasks_query = "
    SELECT 
    SUM(task_done = 1) AS done_tasks,
    SUM(task_done = 2) AS in_progress_tasks,
    SUM(task_done = 0) AS due_tasks,
    COUNT(*) AS total_tasks
FROM task
WHERE Project_ID  = $project_id";
                    $total_tasks_result = mysqli_query($db, $total_tasks_query);
                    $total_tasks_data = mysqli_fetch_assoc($total_tasks_result);


                    $done_tasks = $total_tasks_data['done_tasks'];
                    $in_progress_tasks = $total_tasks_data['in_progress_tasks'];
                    $due_tasks = $total_tasks_data['due_tasks'];
                    $total_tasks = $total_tasks_data['total_tasks'];
                    ?>
                    <div class="chart-container" style=" margin:40px;">
                        <canvas id="taskStatusChart"></canvas>
                    </div>

                    <div class="employee">
                        <?php

                        echo "
                        <div class='employee-list' style='max-height: 330px; overflow-y: auto; display: block;'>
                            <h2 class='text-light text-center pb-3'>Employee List</h2>
                            <table class='table table-bordered text-light bg-body-tertiary text-center ' >
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Assigned As</th>
                                </tr>
                                </thead>
                                <tbody>
                                ";
                        $employee_query = "
                                     SELECT *
                                     FROM project p
                                     JOIN assigned_dev ad ON p.id = ad.Project_ID
                                     JOIN user_type ut ON ad.user_ID = ut.id
                                     WHERE p.id = $project_id
                                      ORDER BY
        CASE
            WHEN ad.assigned_as = 'Project Manager' THEN 1
            ELSE 2
        END,
        ut.Name ASC;
                                      ";
                        $employee_result = mysqli_query($db, $employee_query);

                        while ($employee = mysqli_fetch_assoc($employee_result)):
                            $assigned_as_class = $employee['assigned_as'] === 'Project Manager' ? 'text-warning' : 'text-light';
                            $employee_name_class = $employee['assigned_as'] === 'Project Manager' ? 'text-warning' : 'text-light';
                            echo "
         <tr>
                <td>
                    <a href='/profile.php?id=" . htmlspecialchars($employee['id']) . "'  class='$employee_name_class''>
                    " . htmlspecialchars($employee['Name']) . "
                 </a>
              </td>
               <td>" . htmlspecialchars($employee['Email']) . "</td>
               <td class='$assigned_as_class'>" . htmlspecialchars($employee['assigned_as']) . "</td>
         </tr>
          ";
                        endwhile;

                        echo "
                                </tbody>
                            </table>
                        </div>";
                        } else {
                            echo "<h1>Invalid Project ID or Project Not Found</h1>";
                        }
                        } else {
                            echo "<h1>No Project ID Received or Invalid ID</h1>";
                        }
                        ?>
                    </div>
                </section>

                <div class="task_list py-2 container my-3" style="width: 100%; height: 50%; flex-direction: column;">
                    <h2 class="pb-2 text-center">Task Lists</h2>
                    <div class="w-100" style="max-height: 210px; overflow-y: auto; display: block;width: 100%;">
                        <?php

                        $task_query = "SELECT 
                                              t.Task_Name, 
                                                 t.task_done,
                                                  t.Description,
                                                   t.Start_Date,
                                                   t.Complete_Date,
                                                       GROUP_CONCAT(ud.Name SEPARATOR ', ') AS Assigned_Users
                                                                FROM 
                                                             task t

                                                                JOIN 
                                         user_type ud ON t.Assigned_user = ud.id
                                                    WHERE       
                                                          t.Project_ID = $project_id
                                                                GROUP BY 
                                           t.Task_Name, t.Description, t.Start_Date, t.Complete_Date, t.task_done
                                                    ORDER BY 
                                                     t.Task_Name;

                                                        ";


                        $task_result = mysqli_query($db, $task_query);


                        if ($task_result && mysqli_num_rows($task_result) > 0): ?>
                            <table class="table table-bordered text-light text-center ">
                                <thead>
                                <tr>
                                    <th>Task Name</th>
                                    <th>Task Done</th>
                                    <th>Start Date-End Date</th>
                                    <th>Duration</th>
                                    <th>Description</th>
                                    <th>Assigned Users</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while ($task = mysqli_fetch_assoc($task_result)): ?>
                                    <tr>
                                        <td class="<?php
                                        if ($task['task_done'] == 1) {
                                            echo 'text-decoration-line-through text-success';
                                        } elseif ($task['task_done'] == 2) {
                                            echo 'text-warning';
                                        } else {
                                            echo 'text-danger';
                                        }
                                        ?>">
                                            <?= htmlspecialchars($task['Task_Name']) ?>
                                        </td>

                                        <td class="<?php
                                        if ($task['task_done'] == 1) {
                                            echo 'text-success';
                                        } elseif ($task['task_done'] == 2) {
                                            echo 'text-warning';
                                        } else {
                                            echo 'text-danger';
                                        }
                                        ?>">
                                            <?php
                                            if ($task['task_done'] == 1) {
                                                echo 'Yes';
                                            } elseif ($task['task_done'] == 2) {
                                                echo 'in Progress';
                                            } else {
                                                echo 'No';
                                            }
                                            ?>
                                        </td>


                                        <td>
                                            <?= date("F j, Y", strtotime($task['Start_Date'])) ?>
                                            - <?= date("F j, Y", strtotime($task['Complete_Date'])) ?>
                                        </td>
                                        <td>
                                            <?php
                                            $start_date = strtotime($task['Start_Date']);
                                            $complete_date = strtotime($task['Complete_Date']);
                                            $date_diff = ($complete_date - $start_date) / (60 * 60 * 24);


                                            $text_color = $date_diff <= 3 ? 'text-warning' : '';
                                            ?>
                                            <span class="<?= $text_color; ?>">
                <?= $date_diff . " days"; ?>
            </span>
                                        </td>
                                        <td><?= htmlspecialchars($task['Description']) ?></td>
                                        <td><?= htmlspecialchars($task['Assigned_Users']) ?></td>
                                    </tr>
                                <?php endwhile; ?>

                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-light">No tasks found for the selected project.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>


    </div>

    </main>

</div>


</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/assets/js/calendar_script.js"></script>
<script>
    function Profile() {
        window.location.href = "/profile.php";
    }


    // Data for the chart
    const data = {
        labels: ['Task Status'], // Single group
        datasets: [
            {
                label: 'Done',
                data: [<?= $done_tasks ?>],
                backgroundColor: '#378f63',
            },
            {
                label: 'In Progress',
                data: [<?= $in_progress_tasks ?>],
                backgroundColor: '#c8aa40',
            },
            {
                label: 'Due',
                data: [<?= $due_tasks ?>],
                backgroundColor: '#a83f45',
            }
        ]
    };

    // Config for the chart
    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const total = <?= $total_tasks ?>;
                            const value = context.raw;
                            const percentage = ((value / total) * 100).toFixed(2);
                            return `${context.dataset.label}: ${value} (${percentage}%)`;
                        }
                    }
                },
                legend: {
                    labels: {
                        color: '#fff', // White legend text
                    },
                    position: 'top',
                },
            },
            scales: {
                x: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Task Categories',
                        font: {
                            weight: 'bold',
                        },
                        color: '#fff', // White title text
                    },
                    ticks: {
                        color: '#fff', // White axis labels
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.2)', // Light white grid lines
                    },
                },
                y: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Number of Tasks',
                        font: {
                            weight: 'bold',
                        },
                        color: '#fff', // White title text
                    },
                    ticks: {
                        color: '#fff', // White axis labels
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.2)', // Light white grid lines
                    },
                },
            }
        }
    };

    // Render the chart
    const taskStatusChart = new Chart(
        document.getElementById('taskStatusChart'),
        config
    );

</script>
</body>