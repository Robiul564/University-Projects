<?php

$TITLE = "Employee List";
include_once 'helper/db_connection.php';
include_once 'system/header/header.php';
$db = $mm_conn;

// Fetch employee list from the database
$employee_query = "SELECT id, name, email, picture FROM user_type";
$employee_result = mysqli_query($db, $employee_query);

$project_query = "SELECT id, Name FROM project ORDER BY Priority DESC, is_Active DESC, Start_Date ASC, End_Date ASC";
$project_result = mysqli_query($db, $project_query);
?>
<style>

    /* create button */
    .pushable {
        position: relative;
        background: transparent;
        padding: 0px;
        border: none;
        cursor: pointer;
        outline-offset: 3px;
        outline-color: deeppink;
        transition: filter 250ms;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    }

    .shadow {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: hsl(226, 25%, 69%);
        border-radius: 6px;
        filter: blur(1.5px);
        will-change: transform;
        transform: translateY(1.5px);
        transition: transform 600ms cubic-bezier(0.3, 0.7, 0.4, 1);
    }

    .edge {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        border-radius: 6px;
        background: linear-gradient(
                to right,
                hsl(248, 39%, 39%) 0%,
                hsl(248, 39%, 49%) 8%,
                hsl(248, 39%, 39%) 92%,
                hsl(248, 39%, 29%) 100%
        );
    }

    .front {
        display: block;
        position: relative;
        border-radius: 6px;
        background: hsl(248, 53%, 58%);
        padding: 12px 36px; /* Increased padding for wider button */
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-size: 0.9rem;
        transform: translateY(-3px);
        transition: transform 600ms cubic-bezier(0.3, 0.7, 0.4, 1);
        min-width: 120px; /* Ensures a minimum width for the button */
    }

    .pushable:hover {
        filter: brightness(110%);
    }

    .pushable:hover .front {
        transform: translateY(-4px);
        transition: transform 250ms cubic-bezier(0.3, 0.7, 0.4, 1.5);
    }

    .pushable:active .front {
        transform: translateY(-1.5px);
        transition: transform 34ms;
    }

    .pushable:hover .shadow {
        transform: translateY(3px);
        transition: transform 250ms cubic-bezier(0.3, 0.7, 0.4, 1.5);
    }

    .pushable:active .shadow {
        transform: translateY(0.5px);
        transition: transform 34ms;
    }

    .pushable:focus:not(:focus-visible) {
        outline: none;
    }



    /* neon checkbox */
    .neon-checkbox {
        --primary: #00ffaa;
        --primary-dark: #00cc88;
        --primary-light: #88ffdd;
        --size: 30px;
        position: relative;
        width: var(--size);
        height: var(--size);
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
    }

    .neon-checkbox input {
        display: none;
    }

    .neon-checkbox__frame {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .neon-checkbox__box {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.8);
        border-radius: 4px;
        border: 2px solid var(--primary-dark);
        transition: all 0.4s ease;
    }

    .neon-checkbox__check-container {
        position: absolute;
        inset: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .neon-checkbox__check {
        width: 80%;
        height: 80%;
        fill: none;
        stroke: var(--primary);
        stroke-width: 3;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 40;
        stroke-dashoffset: 40;
        transform-origin: center;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .neon-checkbox__glow {
        position: absolute;
        inset: -2px;
        border-radius: 6px;
        background: var(--primary);
        opacity: 0;
        filter: blur(8px);
        transform: scale(1.2);
        transition: all 0.4s ease;
    }

    .neon-checkbox__borders {
        position: absolute;
        inset: 0;
        border-radius: 4px;
        overflow: hidden;
    }

    .neon-checkbox__borders span {
        position: absolute;
        width: 40px;
        height: 1px;
        background: var(--primary);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .neon-checkbox__borders span:nth-child(1) {
        top: 0;
        left: -100%;
        animation: borderFlow1 2s linear infinite;
    }

    .neon-checkbox__borders span:nth-child(2) {
        top: -100%;
        right: 0;
        width: 1px;
        height: 40px;
        animation: borderFlow2 2s linear infinite;
    }

    .neon-checkbox__borders span:nth-child(3) {
        bottom: 0;
        right: -100%;
        animation: borderFlow3 2s linear infinite;
    }

    .neon-checkbox__borders span:nth-child(4) {
        bottom: -100%;
        left: 0;
        width: 1px;
        height: 40px;
        animation: borderFlow4 2s linear infinite;
    }

    .neon-checkbox__particles span {
        position: absolute;
        width: 4px;
        height: 4px;
        background: var(--primary);
        border-radius: 50%;
        opacity: 0;
        pointer-events: none;
        top: 50%;
        left: 50%;
        box-shadow: 0 0 6px var(--primary);
    }

    .neon-checkbox__rings {
        position: absolute;
        inset: -20px;
        pointer-events: none;
    }

    .neon-checkbox__rings .ring {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 1px solid var(--primary);
        opacity: 0;
        transform: scale(0);
    }

    .neon-checkbox__sparks span {
        position: absolute;
        width: 20px;
        height: 1px;
        background: linear-gradient(90deg, var(--primary), transparent);
        opacity: 0;
    }

    /* Hover Effects */
    .neon-checkbox:hover .neon-checkbox__box {
        border-color: var(--primary);
        transform: scale(1.05);
    }

    /* Checked State */
    .neon-checkbox input:checked ~ .neon-checkbox__frame .neon-checkbox__box {
        border-color: var(--primary);
        background: rgba(0, 255, 170, 0.1);
    }

    .neon-checkbox input:checked ~ .neon-checkbox__frame .neon-checkbox__check {
        stroke-dashoffset: 0;
        transform: scale(1.1);
    }

    .neon-checkbox input:checked ~ .neon-checkbox__frame .neon-checkbox__glow {
        opacity: 0.2;
    }

    .neon-checkbox
    input:checked
    ~ .neon-checkbox__frame
    .neon-checkbox__borders
    span {
        opacity: 1;
    }

    /* Particle Animations */
    .neon-checkbox
    input:checked
    ~ .neon-checkbox__frame
    .neon-checkbox__particles
    span {
        animation: particleExplosion 0.6s ease-out forwards;
    }

    .neon-checkbox
    input:checked
    ~ .neon-checkbox__frame
    .neon-checkbox__rings
    .ring {
        animation: ringPulse 0.6s ease-out forwards;
    }

    .neon-checkbox
    input:checked
    ~ .neon-checkbox__frame
    .neon-checkbox__sparks
    span {
        animation: sparkFlash 0.6s ease-out forwards;
    }

    /* Animations */
    @keyframes borderFlow1 {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(200%);
        }
    }

    @keyframes borderFlow2 {
        0% {
            transform: translateY(0);
        }
        100% {
            transform: translateY(200%);
        }
    }

    @keyframes borderFlow3 {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-200%);
        }
    }

    @keyframes borderFlow4 {
        0% {
            transform: translateY(0);
        }
        100% {
            transform: translateY(-200%);
        }
    }

    @keyframes particleExplosion {
        0% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 0;
        }
        20% {
            opacity: 1;
        }
        100% {
            transform: translate(
                    calc(-50% + var(--x, 20px)),
                    calc(-50% + var(--y, 20px))
            ) scale(0);
            opacity: 0;
        }
    }

    @keyframes ringPulse {
        0% {
            transform: scale(0);
            opacity: 1;
        }
        100% {
            transform: scale(2);
            opacity: 0;
        }
    }

    @keyframes sparkFlash {
        0% {
            transform: rotate(var(--r, 0deg)) translateX(0) scale(1);
            opacity: 1;
        }
        100% {
            transform: rotate(var(--r, 0deg)) translateX(30px) scale(0);
            opacity: 0;
        }
    }

    /* Particle Positions */
    .neon-checkbox__particles span:nth-child(1) {
        --x: 25px;
        --y: -25px;
    }

    .neon-checkbox__particles span:nth-child(2) {
        --x: -25px;
        --y: -25px;
    }

    .neon-checkbox__particles span:nth-child(3) {
        --x: 25px;
        --y: 25px;
    }

    .neon-checkbox__particles span:nth-child(4) {
        --x: -25px;
        --y: 25px;
    }

    .neon-checkbox__particles span:nth-child(5) {
        --x: 35px;
        --y: 0px;
    }

    .neon-checkbox__particles span:nth-child(6) {
        --x: -35px;
        --y: 0px;
    }

    .neon-checkbox__particles span:nth-child(7) {
        --x: 0px;
        --y: 35px;
    }

    .neon-checkbox__particles span:nth-child(8) {
        --x: 0px;
        --y: -35px;
    }

    .neon-checkbox__particles span:nth-child(9) {
        --x: 20px;
        --y: -30px;
    }

    .neon-checkbox__particles span:nth-child(10) {
        --x: -20px;
        --y: 30px;
    }

    .neon-checkbox__particles span:nth-child(11) {
        --x: 30px;
        --y: 20px;
    }

    .neon-checkbox__particles span:nth-child(12) {
        --x: -30px;
        --y: -20px;
    }

    /* Spark Rotations */
    .neon-checkbox__sparks span:nth-child(1) {
        --r: 0deg;
        top: 50%;
        left: 50%;
    }

    .neon-checkbox__sparks span:nth-child(2) {
        --r: 90deg;
        top: 50%;
        left: 50%;
    }

    .neon-checkbox__sparks span:nth-child(3) {
        --r: 180deg;
        top: 50%;
        left: 50%;
    }

    .neon-checkbox__sparks span:nth-child(4) {
        --r: 270deg;
        top: 50%;
        left: 50%;
    }

    /* Ring Delays */
    .neon-checkbox__rings .ring:nth-child(1) {
        animation-delay: 0s;
    }

    .neon-checkbox__rings .ring:nth-child(2) {
        animation-delay: 0.1s;
    }

    .neon-checkbox__rings .ring:nth-child(3) {
        animation-delay: 0.2s;
    }


    /* profile button */
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


    .form-check-input {
        width: 30px;
        height: 30px;
    }

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

</style>

<body data-bs-theme="dark">
<div class="container_dashboard" style="display: flex;width: 85% ;">
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
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle text-light" data-bs-toggle="dropdown"
                                   href="project_details.php?project_id=<?= $project['id'] ?>"><?= htmlspecialchars($project['Name']) ?></a>
                                <ul class="dropdown-menu bg-body-tertiary text-light">
                                    <li><a class="dropdown-item text-light"
                                           href="/task_assignment.php?project_id=<?= $project['id']; ?>">Task Assign</a>
                                    </li>
                                    <li><a class="dropdown-item text-light"
                                           href="project_details.php?project_id=<?= $project['id'] ?>">Project & Task details</a>
                                    </li>
                                </ul>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </li>
            </ul>
        </nav>

        <div>
            <button class="profile-btn" onclick="Profile()">Profile</button>
            <a class="profile-btn" href="/logout.php" style="text-decoration: none">Logout</a>
        </div>

    </div>

    <div id="main_bar_div" style="display: flex;width: 100% ;">
        <main class="main-content container-fluid row p-2 g-1">
            <header class="dashboard-header row g-1 container " style="margin-bottom: 5px;">
                <h1 class="text-light" style="padding-top: 5px;">Employee List</h1>
                <p class="text-light">All the team members can found here.</p>
            </header>
            <section class="dashboard-body container p-2 row g-1 ">
                <form action="create_project_form.php" method="POST" style="margin-bottom: 0px;">

                    <div style="max-height: 520px; overflow-y: auto;">
                        <table class="table table-striped table-dark text-center"
                               style="width: 100%; table-layout: fixed; ">
                            <thead>
                            <tr class="text-light align-middle">
                                <th scope="col">Serial No.</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Picture</th>
                                <th scope="col">Select</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (mysqli_num_rows($employee_result) > 0) {
                                $index = 1;
                                while ($row = mysqli_fetch_assoc($employee_result)) {
                                    echo "<tr>";

                                    echo "<td>{$index}</td>";
                                    echo "<td><a href='profile.php?id={$row['id']}' class='text-light'>{$row['name']}</a></td>";
                                    echo "<td>{$row['email']}</td>";
                                    echo "<td><a href='profile.php?id={$row['id']}'><img src='{$row['picture']}' alt='{$row['name']}' width='50' height='50' class='rounded-circle'></a></td>";

                                    echo "<td>

<div class='form-check' style='margin-left:-20px;align-items: center;margin-top: 10px'>
<label class='neon-checkbox form-check-label'>
  <input type='checkbox'  name='employee_ids[]' value='{$row['id']}'/>
  <div class='neon-checkbox__frame'>
    <div class='neon-checkbox__box'>
      <div class='neon-checkbox__check-container'>
        <svg viewBox='0 0 24 24' class='neon-checkbox__check'>
          <path d='M3,12.5l7,7L21,5'></path>
        </svg>
      </div>
      <div class='neon-checkbox__glow'></div>
      <div class='neon-checkbox__borders'>
        <span></span><span></span><span></span><span></span>
      </div>
    </div>
    <div class='neon-checkbox__effects'>
      <div class='neon-checkbox__particles'>
        <span></span><span></span><span></span><span></span> <span></span
        ><span></span><span></span><span></span> <span></span><span></span
        ><span></span><span></span>
      </div>
      <div class='neon-checkbox__rings'>
        <div class='ring'></div>
        <div class='ring'></div>
        <div class='ring'></div>
      </div>
      <div class='neon-checkbox__sparks'>
        <span></span><span></span><span></span><span></span>
      </div>
    </div>
  </div>
</label>
</div> 
 </td>";
                                    echo "</tr>";
                                    $index++;
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center text-light'>No employees found</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>


                    <button type="submit" class="pushable" style="display: block; margin: 20px auto;">
                        <span class="shadow"></span>
                        <span class="edge"></span>
                        <span class="front"> Submit </span>
                    </button>


                    <!--                    <button type="submit" class="btn btn-primary btn-lg" style="display: block; margin: 20px auto;">-->
                    <!--                        Submit-->
                    <!--                    </button>-->
                </form>
            </section>
        </main>
    </div>
</div>

<script>
    function Profile() {
        window.location.href = "/profile.php";
    }
</script>

</body>