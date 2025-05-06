<?php
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}


if (!isset($_GET['id'])) {
    $user_id = $_SESSION['id'];
} else {
    $user_id = $_GET['id'];
}

$TITLE = "Profile";
include_once 'helper/db_connection.php';
include_once 'system/header/header.php';
$db = $mm_conn;


$user_query = "SELECT * FROM user_type WHERE id = ?";
$stmt = $db->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();


    $priority_query = "SELECT Priority, COUNT(*) AS priority_count
                       FROM project p
                       JOIN assigned_dev ad ON p.id = ad.Project_ID
                       WHERE ad.User_ID = ?
                       GROUP BY Priority";
    $priority_stmt = $db->prepare($priority_query);
    $priority_stmt->bind_param("i", $user_id);
    $priority_stmt->execute();
    $priority_result = $priority_stmt->get_result();

    $priority_labels = [];
    $priority_counts = [];
    while ($row = $priority_result->fetch_assoc()) {
        $priority_labels[] = ($row['Priority'] == 3) ? 'Important' :
            (($row['Priority'] == 2) ? 'Medium' : 'Regular');
        $priority_counts[] = $row['priority_count'];
    }


    $role_query = "SELECT assigned_as, COUNT(*) AS role_count
                   FROM assigned_dev
                   WHERE User_ID = ?
                   GROUP BY assigned_as";
    $role_stmt = $db->prepare($role_query);
    $role_stmt->bind_param("i", $user_id);
    $role_stmt->execute();
    $role_result = $role_stmt->get_result();

    $roles = [];
    $counts = [];
    while ($row = $role_result->fetch_assoc()) {
        $roles[] = $row['assigned_as'];
        $counts[] = $row['role_count'];
    }
    ?>

    <style>
        /* Custom style for limiting container size */
        .custom-container {
            max-width: 1200px; /* Adjust this value to change container width */
            margin: 0 auto; /* Center the container */
        }
    </style>

    <body data-bs-theme="dark">
    <div class="custom-container shadow p-4 ">
        <div class="container-fluid text-light">
            <div class="row justify-content-center mt-2">

                <!-- Profile Card -->
                <div class="col-3 mb-2">
                    <div class="card bg-dark" style="border-radius: 15px;">
                        <div class="card-body text-center ">
                            <img src="<?php echo htmlspecialchars($user['Picture']); ?>" alt="User Picture"
                                 class="rounded-circle" style="width: 150px; height: 150px; border: 3px solid #ecf0f1;">
                            <h2 class="text-light"><?php echo htmlspecialchars($user['Name']); ?></h2>
                            <p class="text-light"><strong>User ID:</strong> <?php echo htmlspecialchars($user['id']); ?>
                            </p>
                            <p class="text-light">
                                <strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?>
                            </p>
                            <?php if ($_SESSION['id'] != 19): ?>
                                <a href="edit_profile.php?user_id=<?php echo urlencode($user['id']); ?>"
                                   style="text-decoration: none; color: #d9dfc1;">
                                    Edit Profile
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>


                <div class="col-3 mb-2">
                    <div class="card bg-dark" style="border-radius: 15px;">
                        <div class="card-body">
                            <h3 class="text-light mb-4 text-center">Priority Distribution</h3>
                            <canvas id="priorityChart"></canvas>
                        </div>
                    </div>
                </div>


                <div class="col-4 mb-2">
                    <div class="card bg-dark" style="border-radius: 15px;">
                        <div class="card-body">
                            <h3 class="text-light mb-4 text-center">Role Assignment</h3>
                            <canvas id="roleChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row justify-content-center mt-2">
                <div class="col-md-12">
                    <div class="card bg-dark" style="border-radius: 15px;">
                        <div class="card-body">
                            <h3 class="text-light mb-4 text-center">Assigned Projects</h3>

                            <?php
                            $project_query = "SELECT
                                                p.id AS Project_ID,
                                                p.Name AS Project_Name,
                                                p.Start_Date,
                                                p.End_Date,
                                                p.Priority,
                                                p.Project_Description,
                                                p.is_Active,
                                                ad.assigned_as
                                              FROM
                                                project p
                                              JOIN
                                                assigned_dev ad ON p.id = ad.Project_ID
                                              WHERE
                                                ad.User_ID = ?
                                                 ORDER BY Priority DESC, is_Active DESC, Start_Date ASC, End_Date ASC";
                            $project_stmt = $db->prepare($project_query);
                            $project_stmt->bind_param("i", $user_id);
                            $project_stmt->execute();
                            $project_result = $project_stmt->get_result();

                            if ($project_result->num_rows > 0) {
                                echo "<div class='table-responsive ' style='max-height: 400px; overflow-y: auto;'>";
                                echo "<table class='table  table-dark table-hover table-striped text-center border-warning-subtle'>";
                                echo "<thead><tr><th>Project Name</th><th>Start Date</th><th>End Date</th><th>Priority</th><th>Description</th><th>Assigned As</th></tr></thead>";
                                echo "<tbody>";
                                while ($row = $project_result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['Project_Name']) . "</td>";
                                    echo "<td>" . date("F j, Y", strtotime($row['Start_Date'])) . "</td>";
                                    echo "<td>" . date("F j, Y", strtotime($row['End_Date'])) . "</td>";


                                    echo "<td>";
                                    if ($row['Priority'] == 3) {
                                        echo 'Important';
                                        echo '<div style="position: relative; display: inline-block;">
            <span style="position: absolute; top: -20px; left: 50%; font-size: 11px;">♦️</span>
          </div>';
                                    } elseif ($row['Priority'] == 2) {
                                        echo 'Medium';
                                        echo '<div style="position: relative; display: inline-block;">
            <span style="position: absolute; top: -20px; left: 50%; font-size: 11px;">🔶</span>
          </div>';
                                    } else {
                                        echo 'Regular';
                                        echo '<div style="position: relative; display: inline-block;">
            <span style="position: absolute; top: -20px; left: 50%; font-size: 11px;">♦</span>
          </div>';
                                    }
                                    echo "</td>";

                                    echo "<td>" . htmlspecialchars($row['Project_Description']) . "</td>";
                                    echo "<td>";
                                    if ($row['assigned_as'] == 'Developer') {
                                        echo htmlspecialchars($row['assigned_as']);
                                        echo '<div style="position: relative; display: inline-block;">
                                                                 <span style="position: absolute; top: -20px; left: 50%; font-size: 12px;">⚡</span>
                                                                      </div>';
                                    }elseif ($row['assigned_as'] == 'Project Manager'){
                                        echo htmlspecialchars("Project Manager");
                                        echo '<div style="position: relative; display: inline-block;">
                                                                 <span style="position: absolute; top: -20px; left: 50%; font-size: 12px;">🌟</span>
                                                                      </div>';
                                    }
                                    echo "</td>";

                                    echo "</tr>";
                                }
                                echo "</tbody></table></div>";
                            } else {
                                echo "<p class='text-center'>This user is not assigned to any projects.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Priority Chart
            var ctx = document.getElementById('priorityChart').getContext('2d');
            var priorityChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($priority_labels); ?>,
                    datasets: [{
                        data: <?php echo json_encode($priority_counts); ?>,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                    }]
                }
            });

            // Role Chart
            var ctx = document.getElementById('roleChart').getContext('2d');
            var roleChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($roles); ?>, // Role names (e.g., Developer, Manager)
                    datasets: [{
                        label: 'Number of Assignments',
                        data: <?php echo json_encode($counts); ?>, // Role counts
                        backgroundColor: '#b8cedd',
                        borderColor: '#3c4955',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#fff2f2' // Dark color for y-axis ticks
                            },
                            grid: {
                                color: '#b1acac' // White color for the y-axis line
                            }
                        },
                        x: {
                            ticks: {
                                color: '#d5942f' // Dark color for x-axis ticks
                            },
                            grid: {
                                color: '#9c9595' // White color for the y-axis line
                            }
                        }
                    },
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#d6c8c8' // Dark color for legend text
                            }
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                title: function (tooltipItem) {
                                    return tooltipItem[0].label;
                                },
                                label: function (tooltipItem) {
                                    return 'Assignments: ' + tooltipItem.raw;
                                }
                            },
                            bodyColor: '#ffffff' // Dark color for tooltip body text
                        }
                    }
                }
            });

        </script>
    </div>
    </body>

    <?php
} else {
    echo "<script>alert('No user details found for the given ID.');</script>";
} ?>