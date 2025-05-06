<?php
$TITLE = "Task List";
include_once 'helper/db_connection.php';
include_once 'system/header/header.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
} else {
    $user_id = $_SESSION['id'];
}

$db = $mm_conn;


$user_query = "SELECT * FROM user_type WHERE id = ?";
$stmt = $db->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

$project_id = null;

if (isset($_GET['id'])) {
    $project_id = $_GET['id'];

    $project_query = "SELECT * FROM project WHERE id = ?";
    $stmt = $db->prepare($project_query);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $project_result = $stmt->get_result();

    if ($project_result && $project_result->num_rows > 0) {
        $project = $project_result->fetch_assoc();

    } else {
        echo "Project not found.";
    }
} else {
    echo "No project ID provided.";
}


if ($project_id !== null) {
    $total_tasks_query = "SELECT id, Task_Name, task_done, Start_date, Complete_Date, Description FROM task WHERE Assigned_user = ? AND Project_ID = ?";
    $stmt = $db->prepare($total_tasks_query);
    $stmt->bind_param("ii", $user_id, $project_id);
    $stmt->execute();
    $total_tasks_result = $stmt->get_result();
    $total_tasks = $total_tasks_result->fetch_all(MYSQLI_ASSOC);
} else {
    $total_tasks = [];
}
?>

<?php
if (isset($_GET['message']) && isset($_GET['status'])) {
    $message = htmlspecialchars($_GET['message']);
    echo "<script>alert('$message');</script>";
}
?>


<style>

    /*profile button css*/
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

    /*profile btn css ended*/
    .main-content {
        padding: 30px 20px;
    }

    .dashboard-header {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        border-bottom: 2px solid #3498db; /* Underline effect */
        padding-bottom: 12px;
        margin-bottom: 20px;
    }

    .dashboard-header h1 {
        color: white;
        font-size: 2rem;
        margin: 0;
    }

    .dashboard-header p {
        color: #ecf0f1;
        font-size: 1.1rem;
        margin: 5px 0 0;
    }

    /* card view */

    .container {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 5px;
    }

    .container .glass {
        position: relative;
        width: 150px; /* Reduced width */
        height: 170px; /* Reduced height */
        background: linear-gradient(#fff2, transparent);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 20px 20px rgba(0, 0, 0, 0.2); /* Reduced shadow size */
        display: flex;
        justify-content: center;
        align-items: center;
        transition: 0.4s; /* Slightly faster transition */
        border-radius: 8px; /* Reduced border radius */
        margin: 0 -35px; /* Adjusted margin */
        backdrop-filter: blur(8px); /* Reduced blur intensity */
        transform: rotate(calc(var(--r) * 1deg));
    }

    .container:hover .glass {
        transform: rotate(0deg);
        margin: 0 8px; /* Adjusted hover margin */
    }

    .container .glass::before {
        content: attr(data-text);
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 30px; /* Reduced height */
        background: rgba(255, 255, 255, 0.05);
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        font-size: 0.9em; /* Smaller text */
    }

    .container .glass svg {
        font-size: 2em; /* Reduced icon size */
        fill: #fff;
    }

    /*task_comment and toggle settings*/

    /*radio button css*/
    .radio-input {
        display: flex;
        flex-direction: row;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        font-size: 16px;
        font-weight: 600;
        color: white;
    }

    .radio-input input[type="radio"] {
        display: none;
    }

    .radio-input label {
        display: flex;
        align-items: center;
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #212121;
        border-radius: 5px;
        margin-right: 12px;
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease-in-out;
    }

    .radio-input label:before {
        content: "";
        display: block;
        position: absolute;
        top: 50%;
        left: 0;
        transform: translate(-50%, -50%);
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #fff;
        border: 2px solid #ccc;
        transition: all 0.3s ease-in-out;
    }

    .radio-input input[type="radio"]:checked + label:before {
        background-color: green;
        top: 0;
    }

    .radio-input input[type="radio"]:checked + label {
        background-color: royalblue;
        color: #fff;
        border-color: rgb(129, 235, 129);
        animation: radio-translate 0.5s ease-in-out;
    }

    @keyframes radio-translate {
        0% {
            transform: translateX(0);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateX(0);
        }
    }


</style>

<body data-bs-theme="dark">
<div class="container_dashboard" style="width:80%">
    <div class="sidebar" style="display: flex; flex-direction: column; width: 13%">
        <div class="text-center">
            <div class="profile_pic">
                <img src="<?php echo htmlspecialchars($user['Picture']); ?>" alt="User Picture" class="rounded-circle"
                     style="width: 100px; height: 100px; border: 3px solid #ecf0f1;">
                <h3 class="text-light my-2"><?php echo htmlspecialchars($user['Name']); ?></h3>
                <p class="text-light"><strong>User ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
                <p class="text-light"><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
            </div>
        </div>
        <nav class="mt-5" style="display: flex; flex-direction: column; align-items: center; height: 300px;">
            <div class=" border bg-dark text-center rounded-pill " style="width: 150px;height:40px;">
                <a class="nav-link text-light" href="/user_dashboard.php" role="button" style="padding-top: 5px;">
                    🏚️Home
                </a>
            </div>
        </nav>

        <div>
            <button class="profile-btn " onclick="Profile()">Profile</button>
            <a class="profile-btn" href="/logout.php" style="text-decoration: none">Logout</a>
        </div>
    </div>

    <div id="main_bar_div" style="display: flex; width: 70%">
        <main class="main-content">
            <header class="dashboard-header">
                <h1 class="text-light">Welcome To Your Task Management Area</h1>
                <p class="text-light">Here you can find all of your tasks.</p>
            </header>

            <div class="mb-2">

                <div class="container">
                    <div data-text="Project End Date " style="--r:-15;" class="glass text-center">
                        <h3 class=""> <?php echo date('d M Y', strtotime(htmlspecialchars($project['End_Date']))) ?></h3>
                    </div>
                    <div data-text="Project Name" style="--r:5;" class="glass bg-warning-subtle p-1 text-center">
                        <h3 class=""> <?php echo htmlspecialchars($project['Name']); ?></h3>
                    </div>
                    <div data-text="Project Status" style="--r:25;" class="glass">
                        <h3 class="text-center"><?= $project['is_Active'] ? 'Active' : 'Complete' ?></h3>

                    </div>
                </div>


            </div>
            <div class="table-responsive text-light">
                <table class="table">
                    <thead class=" text-center bg-dark  fs-5">
                    <tr>
                        <td>Task Name</td>
                        <td>Start & End Date</td>
                        <td>Duration</td>
                        <td>Status</td>
                    </tr>
                    </thead>
                    <tbody class="border-warning-subtle text-center">
                    <?php if (!empty($total_tasks)) : ?>
                        <?php foreach ($total_tasks as $task) :
                            $start_date = strtotime(htmlspecialchars($task['Start_date']));
                            $end_date = strtotime(htmlspecialchars($task['Complete_Date']));

                            $duration = round(($end_date - $start_date) / (60 * 60 * 24));
                            $current_date = strtotime(date('Y-m-d'));
                            $isOverdue = $end_date < $current_date;
                            ?>
                            <tr>
                                <td>

                                    <?php echo htmlspecialchars($task['Task_Name']); ?>
                                    <?php if ($isOverdue): ?>
                                        <div style="position: relative; display: inline-block;">
                                            <span style="position: absolute;  top: -20px; left: 50%; font-size: 11px;">&#9888;</span>
                                        </div>
                                    <?php elseif ($task['task_done']==1): ?>
                                        <div style="position: relative; display: inline-block;">
                                            <span style="position: absolute; top: -20px; left: 50%; font-size: 11px;">&#10004;</span>
                                        </div>
                                    <?php elseif ($task['task_done']==2): ?>
                                        <div style="position: relative; display: inline-block;">
                                            <span style="position: absolute; top: -20px; left: 50%; font-size: 11px;">⌛</span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center"><?php echo date('d M Y', $start_date); ?>
                                    -- <span class="end_date"><?php echo date('d M Y', $end_date); ?></span>
                                </td>
                                <td class="text-center <?php echo $duration < 4 ? 'text-warning' : ''; ?>">
                                    <?php echo $duration . ' days'; ?>
                                </td>
                                <td class="<?php
                                if ($task['task_done'] === 2) {
                                    echo 'text-warning';
                                } elseif ($task['task_done'] === 0) {
                                    echo 'text-danger';
                                } elseif ($task['task_done'] === 1) {
                                    echo 'text-success';
                                } else {
                                    echo 'text-danger'; // Fallback for unknown status
                                }
                                ?>">
                                    <?php
                                    if ($task['task_done'] === 2) {
                                        echo 'In Progress';
                                    } elseif ($task['task_done'] === 0) {
                                        echo 'To Do';
                                    } elseif ($task['task_done'] === 1) {
                                        echo 'Completed';
                                    } else {
                                        echo 'Unknown';
                                    }
                                    ?>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center text-light">No tasks found</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </main>
    </div>
    <form method="POST" action="/api/or_task_update.php" style="width: 22%; padding: 10px; border-radius: 8px; margin-top: 10px;">
        <div class="form-group text-white" style="margin: 10px 0;">
            <label for="taskSelect">Select Task</label>
            <select
                    class="form-control bg-dark text-white"
                    id="taskSelect"
                    name="task_id"
                    onchange="fetchTaskDetails(this.value)">
                <option value="" disabled selected>Select a task</option>
                <?php
                $tasks_query = "SELECT * FROM task WHERE Assigned_user = ? AND Project_ID = ?";
                $stmt = $db->prepare($tasks_query);
                $stmt->bind_param("ii", $user_id, $project_id);
                $stmt->execute();
                $tasks_result = $stmt->get_result();

                if ($tasks_result->num_rows > 0) {
                    while ($task = $tasks_result->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($task['id']) . '">' . htmlspecialchars($task['Task_Name']) . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>No tasks available</option>';
                }
                ?>
            </select>
        </div>

        <div>
            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        </div>

        <div class="form-group text-white" style="margin: 10px 0;">
            <label for="description">Description</label>
            <textarea
                    class="form-control bg-dark text-white"
                    id="description"
                    name="description"
                    rows="4"
                    placeholder="Task description" readonly>
        </textarea>
        </div>

        <div class="radio-input" style="margin: 10px 0;">
            <input value="0" name="task_status" id="value-1" type="radio">
            <label for="value-1">ToDo</label>
            <input value="2" name="task_status" id="value-2" type="radio">
            <label for="value-2">In Progress</label>
            <input value="1" name="task_status" id="value-3" type="radio">
            <label for="value-3">Done</label>
        </div>

        <div class="form-group text-white" style="margin: 10px 0;">
            <label for="comment">Comment</label>
            <textarea
                    class="form-control bg-dark text-white"
                    id="comment"
                    name="comment"
                    rows="3"
                    placeholder="Add your comment">
        </textarea>
        </div>

        <button
                type="submit"
                class="btn btn-primary btn-block">
            Submit
        </button>
    </form>



</div>


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

    function fetchTaskDetails(taskId) {
        if (!taskId) return;

        fetch(`/api/or_get_task_description.php?task_id=${taskId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('description').value = data.task.Description || '';
                } else {
                    alert('Failed to fetch task details.');
                }
            })
            .catch(error => {
                console.error('Error fetching task details:', error);
                alert('An error occurred while fetching task details.');
            });
    }


</script>

</body>
