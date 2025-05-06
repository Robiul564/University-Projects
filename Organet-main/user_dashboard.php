<?php
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
} else {
    $user_id = $_SESSION['id'];
}

$TITLE = "User_Dashboard";
include_once 'helper/db_connection.php';
include_once 'system/header/header.php';

$db = $mm_conn;

// Fetch projects from the database filtered by session user ID
$project_query = "SELECT p.id,p.Name, p.Start_Date, p.End_Date, p.Priority, p.is_Active
                  FROM user_type u
                  JOIN assigned_dev a ON u.id = a.User_ID
                  JOIN project p ON a.Project_ID = p.id
                  WHERE u.id = ?
                  ORDER BY Priority DESC, is_Active DESC, Start_Date ASC, End_Date ASC";

$stmt = $db->prepare($project_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$project_result = $stmt->get_result();

// Query to fetch user details
$user_query = "SELECT * FROM user_type WHERE id = ?";
$stmt = $db->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Total projects
$total_projects_query = "SELECT COUNT(p.id) AS total_Projects
                         FROM user_type u
                         JOIN assigned_dev a ON u.id = a.User_ID
                         JOIN project p ON a.Project_ID = p.id
                         WHERE u.id = ?";
$stmt = $db->prepare($total_projects_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_projects_result = $stmt->get_result();
$total_projects = mysqli_fetch_assoc($total_projects_result)['total_Projects'];

//total done projects
$total_projects_done_query = "SELECT COUNT(p.id) AS total_done_Projects
                         FROM user_type u
                         JOIN assigned_dev a ON u.id = a.User_ID
                         JOIN project p ON a.Project_ID = p.id
                         WHERE u.id = ? AND p.is_Active = 0";
$stmt = $db->prepare($total_projects_done_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$total_done_projects_result = $stmt->get_result();
$total_done_projects = mysqli_fetch_assoc($total_done_projects_result)['total_done_Projects'];

// todo tasks
$todo_tasks_query = "SELECT COUNT(t.id) AS total_todo_tasks
FROM task t
JOIN user_type u ON u.id = t.Assigned_user
JOIN project p ON p.id = t.Project_ID
WHERE t.Assigned_user = ? AND t.task_done = 0;
";
$stmt = $db->prepare($todo_tasks_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$todo_tasks_result = $stmt->get_result();
$todo_tasks = mysqli_fetch_assoc($todo_tasks_result)['total_todo_tasks'];

// Done tasks
$done_tasks_query = "SELECT COUNT(t.id) AS total_tasks_done
FROM task t
JOIN user_type u ON u.id = t.Assigned_user
JOIN project p ON p.id = t.Project_ID
WHERE t.Assigned_user = ? AND t.task_done = 1;
";
$stmt = $db->prepare($done_tasks_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$done_tasks_result = $stmt->get_result();
$done_tasks = mysqli_fetch_assoc($done_tasks_result)['total_tasks_done'];


//in progress tasks
$total_active_query = "SELECT COUNT(t.id) AS total_active_tasks
FROM task t
JOIN user_type u ON u.id = t.Assigned_user
JOIN project p ON p.id = t.Project_ID
WHERE t.Assigned_user = ? AND t.task_done = 2;
";
$stmt = $db->prepare($total_active_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$active_tasks_result = $stmt->get_result();
$active_tasks = mysqli_fetch_assoc($active_tasks_result)['total_active_tasks'];

?>


<!-- Styles -->
<style>
    /*search Button css*/
    /* From Uiverse.io by LightAndy1 */
    .group {
        display: flex;
        line-height: 28px;
        align-items: center;
        position: relative;
        max-width: 190px;
    }

    .input {
        font-family: "Montserrat", sans-serif;
        width: 100%;
        height: 45px;
        padding-left: 2.5rem;
        box-shadow: 0 0 0 1.5px #2b2c37, 0 0 25px -17px #000;
        border: 0;
        border-radius: 12px;
        background-color: #16171d;
        outline: none;
        color: #bdbecb;
        transition: all 0.25s cubic-bezier(0.19, 1, 0.22, 1);
        cursor: text;
        z-index: 0;
    }

    .input::placeholder {
        color: #bdbecb;
    }

    .input:hover {
        box-shadow: 0 0 0 2.5px #2f303d, 0px 0px 25px -15px #000;
    }

    .input:active {
        transform: scale(0.95);
    }

    .input:focus {
        box-shadow: 0 0 0 2.5px #2f303d;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        fill: #bdbecb;
        width: 1rem;
        height: 1rem;
        pointer-events: none;
        z-index: 1;
    }

    /*search button end*/
    /* button css */
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


</style>


<body data-bs-theme="dark">
<div class="container_dashboard " style="height:90%">
    <div class="sidebar">
        <div class="text-center">
            <div class="profile_pic">
                <img src="<?php echo htmlspecialchars($user['Picture']); ?>" alt="User Picture" class="rounded-circle"
                     style="width: 100px; height: 100px; border: 3px solid #ecf0f1;">
                <h3 class="text-light my-2"><?php echo htmlspecialchars($user['Name']); ?></h3>
                <p class="text-light"><strong>User ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
                <p class="text-light"><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
            </div>
        </div>

        <nav class="mt-5">
            <div class=" border bg-dark text-center rounded-pill " style="width: 200px;height:40px;">
                <a class="nav-link text-light" href="/user_dashboard.php" role="button" style="padding-top: 5px;">
                    🏚️Home
                </a>
            </div>
        </nav>
        <!-- Search button -->
        <div class="group " style="margin: 20px 0 0 6px;">
            <svg viewBox="0 0 24 24" aria-hidden="true" class="search-icon">
                <g>
                    <path
                            d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"
                    ></path>
                </g>
            </svg>

            <input
                    id="query"
                    class="input"
                    type="search"
                    placeholder="Search..."
                    name="searchbar"
                    autocomplete="off"
            />
            <div class="suggestions" id="suggestions" style="position: absolute;top: 50px;width: 100%;"></div>
        </div>


        <div class="buttons" style="margin-top: 250px">
            <button class="profile-btn" onclick="Profile()">Profile</button>
            <a class="profile-btn" href="/logout.php" style="text-decoration: none">Logout</a>
        </div>
    </div>

    <div id="main_bar_div" style="display: flex; width: 90%">
        <main class="main-content">
            <header class="dashboard-header">
                <h1 class="text-light">Welcome To Your Project Management Area</h1>
                <p class="text-light">Here you can find all of your Projects.</p>
            </header>
            <section class="dashboard-body d-flex p-4">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-3">
                            <div class="card">
                                <div class="card-header bg-primary bg-opacity-25 text-center text-dark">Total Projects
                                </div>
                                <div class="card-body bg-primary-subtle text-center">
                                    Total :: <?= $total_projects ?>
                                    -->Done :: <?= $total_done_projects ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card">
                                <div class="card-header bg-danger bg-opacity-25 text-center text-dark">ToDo Tasks
                                </div>
                                <div class="card-body bg-danger-subtle text-center">
                                    <?= $todo_tasks ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card">
                                <div class="card-header bg-warning bg-opacity-25 text-center text-dark">Active/in
                                    progress Tasks
                                </div>
                                <div class="card-body bg-warning-subtle text-center">
                                    <?= $active_tasks ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card">
                                <div class="card-header bg-success bg-opacity-25 text-center text-dark">Completed Tasks
                                </div>
                                <div class="card-body bg-success-subtle text-center">
                                    <?= $done_tasks ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="card mt-5 bg-body">
                <h3 class="card-header text-center">All the projects <span class="fw-light fs-6">( ✓complete  📌active  ⚠️️️overdue )</span>
                </h3>
                <div class="card-body table-responsive" style="height: 400px; overflow-y: auto;">
                    <table class="table table-dark table-striped text-center table-hover border-warning-subtle ">

                        <thead class="thead-dark">
                        <tr>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Priority</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        <?php if ($project_result && $project_result->num_rows > 0): ?>
                            <?php mysqli_data_seek($project_result, 0); // Reset pointer for reuse ?>
                            <?php while ($project = $project_result->fetch_assoc()): ?>
                                <?php

                                $start_date = strtotime(htmlspecialchars($project['Start_Date']));
                                $end_date = strtotime(htmlspecialchars($project['End_Date']));
                                $duration = round(($end_date - $start_date) / (60 * 60 * 24));
                                $current_date = strtotime(date('Y-m-d'));
                                $isOverdue = $end_date < $current_date;

                                ?>
                                <tr>
                                    <td>
                                        <a href="/user_project.php?id=<?= htmlspecialchars($project['id']) ?>"
                                           class="text-light text-decoration-none">
                                            <?= htmlspecialchars($project['Name']) ?>
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

                                        </a>
                                    </td>

                                    <td><?= date("j M, Y", $start_date) ?></td>
                                    <td class="end_date"><?= date("j M, Y", $end_date) ?></td>


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
                                            echo 'Unknown'; // Fallback in case of invalid priority
                                        }
                                        ?>
                                    </td>
                                    <td style="color: <?= $project['is_Active'] ? '#C62300' : '#0A6847' ?>;">
                                        <?= $project['is_Active'] ? 'Active' : 'Complete' ?>
                                    </td>

                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No projects found</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <div class="right_container text-white" style="width: 25%; margin-left: 2px;">
            <div class="calendar" style="width: 100%; height: 51%;">
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
            <div class="card mt-5 bg-transparent">
                <div class="card-header text-center text-white fs-5">Task Overview</div>
                <div class="card-body">
                    <canvas id="taskChart"></canvas>
                </div>
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

    const ctx = document.getElementById('taskChart').getContext('2d');
    const taskChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Projects', 'To-Do Tasks', 'Active Tasks', 'Completed Tasks'],
            datasets: [{
                label: 'Tasks Overview',
                data: [<?= $total_projects ?>, <?= $todo_tasks ?>, <?= $active_tasks ?>, <?= $done_tasks ?>],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.9)', // Total Projects
                    'rgba(255, 99, 132, 0.9)', // To-Do Tasks
                    'rgba(255, 206, 86, 0.9)', // Active Tasks
                    'rgba(75, 192, 192, 0.9)'  // Completed Tasks
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, // Ensures the chart scales with the canvas
            maintainAspectRatio: false, // Allows flexibility in sizing
            scales: {
                x: {
                    ticks: {
                        color: 'white' // X-axis label color
                    },
                    grid: {
                        color: 'rgb(160,165,165)', // X-axis grid line color
                        borderColor: 'white' // X-axis border line color
                    }
                },
                y: {
                    ticks: {
                        color: 'white' // Y-axis label color
                    },
                    grid: {
                        color: 'rgb(160,165,165)', // Y-axis grid line color
                        borderColor: 'white' // Y-axis border line color
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: 'white' // Legend text color
                    }
                },
                tooltip: {
                    bodyColor: 'white', // Tooltip text color
                    titleColor: 'white', // Tooltip title color
                    backgroundColor: 'rgba(0, 0, 0, 0.8)' // Tooltip background color
                }
            }
        }
    });

    //search script
    const searchBox = document.getElementById('query');
    const suggestionsBox = document.getElementById('suggestions');

    searchBox.addEventListener('input', function () {
        const query = this.value.trim();

        if (query.length > 0) {
            fetch(`/api/or_search.php?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsBox.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const suggestionItem = document.createElement('div');
                            suggestionItem.textContent = item.name; // Use item.name for display
                            suggestionItem.classList.add('list-group-item', 'list-group-item-action', 'text-light', 'bg-transparent', 'rounded-2');
                            suggestionItem.addEventListener('click', function () {
                                // console.log("Clicked item:", item);
                                // console.log("Project ID:", item.id);
                                window.location.href = `/user_project.php?id=${item.id}`;
                            });
                            suggestionsBox.appendChild(suggestionItem);
                        });
                    } else {
                        suggestionsBox.innerHTML = `<div class="list-group-item text-muted bg-transparent bg-dark">No suggestions found</div>`;
                    }
                })
                .catch(err => {
                    console.error('Error fetching suggestions:', err);
                    suggestionsBox.innerHTML = `<div class="list-group-item text-muted bg-transparent bg-dark">Error fetching suggestions</div>`;
                });
        } else {
            suggestionsBox.innerHTML = '';
        }
    });


</script>
</body>
