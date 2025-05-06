<?php

$TITLE = "Dashboard";
include_once 'helper/db_connection.php';
include_once 'system/header/header.php';
$db = $mm_conn;


$project_query = "SELECT id, Name FROM project ORDER BY Priority DESC, is_Active DESC, Start_Date ASC, End_Date ASC";
$project_result = mysqli_query($db, $project_query);

$project_table_query = "SELECT id, Name, Start_Date, End_Date, Priority, is_Active FROM project ORDER BY Priority DESC, is_Active DESC, Start_Date ASC, End_Date ASC";
$project_table_result = mysqli_query($db, $project_table_query) or die(mysqli_error($db));

$total_projects_query = "SELECT COUNT(id) AS total FROM project";
$total_projects_result = mysqli_query($db, $total_projects_query);
$total_projects = mysqli_fetch_assoc($total_projects_result)['total'];

$active_projects_query = "SELECT COUNT(id) AS active FROM project WHERE is_Active = 1";
$active_projects_result = mysqli_query($db, $active_projects_query);
$active_projects = mysqli_fetch_assoc($active_projects_result)['active'];

$done_projects = $total_projects - $active_projects;

?>

<script>
    const activeProjects = <?=$active_projects?>;
    const inactiveProjects = <?=$done_projects?>;
</script>

<style>
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

    .dashboard-body {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 10px;
    }


</style>

<body data-bs-theme="dark">
<div class="container_dashboard">
    <div class="sidebar">
        <div class="logo">
            <img src="/assets/img/logo/logo.png" alt="OrgNet Logo">
            <h1 class="text-white fw-bold">OrgaNet</h1>
        </div>
        <nav>
            <ul>
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
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarProjectsDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Projects
                    </a>
                    <ul class="dropdown-menu dropend bg-body-tertiary " aria-labelledby="navbarProjectsDropdown">
                        <?php while ($project = mysqli_fetch_assoc($project_result)): ?>
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle text-light" data-bs-toggle="dropdown"
                                   href="project_details.php?project_id=<?=$project['id']?>"><?=htmlspecialchars($project['Name'])?>
                                </a>
                                <ul class="dropdown-menu bg-body-tertiary text-light">
                                    <li><a class="dropdown-item text-light"
                                           href="/task_assignment.php?project_id=<?=$project['id'];?>">Task Assign</a>
                                    </li>

                                    <li><a class="dropdown-item text-light"
                                           href="project_details.php?project_id=<?=$project['id']?>">Project & Task details</a>
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


    <div id="main_bar_div" style="display: flex;width: 100%">
        <main class="main-content">
            <header class="dashboard-header">
                <h1 class="text-light">Welcome To Your Task Management Area</h1>
                <p class="text-light">Hare you can find all of your tasks.</p>
            </header>
            <section class="dashboard-body ">

                <div class="col-md-12" style="margin-top: 30px;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-primary  bg-opacity-25 text-center text-dark">Total
                                    Projects
                                </div>
                                <div class="card-body bg-primary-subtle text-center">
                                    <?=$total_projects?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-danger bg-opacity-25 text-center text-dark">Active Projects
                                </div>
                                <div class="card-body bg-danger-subtle text-center">
                                    <?=$active_projects?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-success bg-opacity-25 text-center text-dark">Complete
                                    Projects
                                </div>
                                <div class="card-body bg-success-subtle text-center">
                                    <?=$done_projects?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-5 height-100 bg-body">
                        <h3 class="card-header text-center">All the projects <span class="fw-light fs-6">( ✓complete  📌active  ⚠️️️overdue )</span></h3>
                        <div class="card-body table-responsive" style="height: 400px; overflow-y: auto;">

                            <table class="table bg-body-tertiary  text-center  ">

                                <thead class="thead-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Start Date - End Date</th>
                                    <th>Duration</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody >
                                <?php while ($project = mysqli_fetch_assoc($project_table_result)): ?>
                                <?php

                                $start_date = strtotime(htmlspecialchars($project['Start_Date']));
                                $end_date = strtotime(htmlspecialchars($project['End_Date']));
                                    $duration = round(($end_date - $start_date) / (60 * 60 * 24));
                                    $current_date = strtotime(date('Y-m-d'));
                                    $isOverdue = $end_date < $current_date;

                                    ?>
                                    <tr>
                                        <td><a href="/task_assignment.php?project_id=<?=$project['id']?>"
                                               class="text-decoration-none"><?=htmlspecialchars($project['Name'])?></a>
                                            <?php if ($isOverdue): ?>
                                                <div style="position: relative; display: inline-block;">
                                                    <span style="position: absolute;  top: -20px; left: 50%; font-size: 11px;">&#9888;</span>
                                                </div>
                                            <?php elseif (!$project['is_Active']): ?>
                                                <div style="position: relative; display: inline-block;">
                                                    <span style="position: absolute; top: -20px; left: 50%; font-size: 11px;">✓</span>
                                                </div>
                                            <?php else: ?>
                                                <div style="position: relative; display: inline-block;">
                                                    <span style="position: absolute; top: -20px; left: 50%; font-size: 11px;">📌</span>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= date("j M, Y", $start_date) ?>
                                            --
                                            <span class="end_date"><?= date("j M, Y", $end_date) ?></span>
                                        </td>

                                        <td>
                                           <?php   echo $duration . " days"; ?>
                                        </td>


                                        <td>
                                            <?php
$priority = $project['Priority'];
if ($priority == 1) {
    echo 'Regular';
     echo '<div style="position: relative; display: inline-block;">
            <span style="position: absolute; top: -20px; left: 50%; font-size: 11px;">♦</span>
          </div>';
} elseif ($priority == 2) {
    echo 'Medium';
    echo '<div style="position: relative; display: inline-block;">
            <span style="position: absolute; top: -20px; left: 50%; font-size: 11px;">🔶</span>
          </div>';
} elseif ($priority == 3) {
    echo 'Important';
    echo '<div style="position: relative; display: inline-block;">
            <span style="position: absolute; top: -20px; left: 50%; font-size: 11px;">♦️ </span>
          </div>';
} else {
    echo 'Unknown';
}
?>
                                        </td>
                                        <td style="color: <?=$project['is_Active'] ? '#C62300' : '#0A6847'?>;">
                                            <?=$project['is_Active'] ? 'Active' : 'Complete'?>
                                        </td>

                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </section>
        </main>
    </div>

    <div class="right_container text-white" style="width: 25%; margin-left: 2px;">
        <div class="calendar " style="width: 100%; height: 51%;">
            <div class="header">
                <h2 class="current-date"></h2>
                <div class="navigation">
                    <button class="nav-btn" id="prevMonth">&lt;</button>
                    <button class="nav-btn" id="nextMonth">&gt;</button>
                </div>
            </div>
            <div class="calendar-grid">
                <div class="weekday">Sun</div>
                <div class="weekday">Mon</div>
                <div class="weekday">Tue</div>
                <div class="weekday">Wed</div>
                <div class="weekday">Thu</div>
                <div class="weekday">Fri</div>
                <div class="weekday">Sat</div>
            </div>
        </div>
        <div class="body1">
            <div class="tracker-container">
                <canvas id="projectChart"></canvas>
            </div>
        </div>
    </div>
</div>


<script src="/assets/js/calendar_script.js"></script>
<script>
    function Profile() {
        window.location.href = "/profile.php";

    }
    const currentDate = new Date();
    const dueDateCells = document.querySelectorAll('.end_date');

    dueDateCells.forEach(cell => {
        const dueDate = new Date(cell.textContent.trim());


        const diffInDays = (dueDate - currentDate) / (1000 * 60 * 60 * 24);

        if (diffInDays < 0) {

            cell.classList.add('text-danger');
        } else if (diffInDays <= 5) {

            cell.classList.add('text-warning');
        }
    });


</script>
</body>

