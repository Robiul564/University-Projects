<!DOCTYPE html>
<html lang="en">

<?php
$TITLE = "Reset_Password";
include_once 'helper/db_connection.php';
include_once 'system/header/header.php';
$db = $mm_conn;
?>
<body>
    <div class="container">
        <div class="left-section">
            <div class="logo">
                <div class="icon"></div>
                <h1>OrgaNet</h1>
            </div>
        </div>
        <div class="right-section">
            <div class="form-container">
                <h2>Reset Password</h2>

                <form id="reset-form" action="#" method="post">
                    <div class="input-group">
                        <label for="pass">New Password</label>
                        <input class="input-field" type="password" id="pass" name="pass"
                            placeholder="Enter your password" required>
                    </div>
                    <div class="input-group">
                        <label for="pass-confirm">Confirm Password</label>
                        <input class="input-field" type="password" id="pass-confirm" name="pass-confirm"
                            placeholder="Confirm your password" required>
                        <p id="error-message" class="error-message">Passwords do not match</p>
                    </div>
                    <button type="submit" class="submit-btn">Reset</button>
                </form>
                <p class="signin-link">Already have an account? <a href="/login.php">Sign in</a></p>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('reset-form');
        const password = document.getElementById('pass');
        const confirmPassword = document.getElementById('pass-confirm');
        const errorMessage = document.getElementById('error-message');

        form.addEventListener('submit', function (event) {
            if (password.value !== confirmPassword.value) {
                event.preventDefault(); // Prevent form submission
                errorMessage.style.display = 'block'; // Show the error message
            } else {
                errorMessage.style.display = 'none'; // Hide the error message if they match
            }
        });
    </script>
</body>
