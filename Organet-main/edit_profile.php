<?php

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];


include_once 'helper/db_connection.php';
include_once 'system/header/header.php';
$db = $mm_conn;


$query = "SELECT * FROM user_type WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found!";
    exit;
}
?>


<body>
<div class="container  d-flex flex-column text-white">

        <div class="shadow-lg p-3 mb-5 text-center  rounded" style="margin:30px">
            <h2>Edit Profile</h2>
        </div>
        <div class="card-body p-2">
            <form action="/api/or_edit_profile.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="<?php echo htmlspecialchars($user['Name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?php echo htmlspecialchars($user['Email']); ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Enter new password (leave blank to keep current)">
                </div>
                <div class="mb-3">
                    <label for="profilePicture" class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" id="profilePicture" name="profile_picture">
                </div>

                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
            </form>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

