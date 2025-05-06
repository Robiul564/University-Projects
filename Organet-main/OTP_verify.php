<?php
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
                <h2>Enter code</h2>
                <p>Enter the OTP sent to your mail</p>

                <form action="#" method="post">
                    <div class="input-group">
                        <label for="name">OTP</label>
                        <input class="input-field" type="text" id="name" name="name" placeholder="Enter the OTP"
                            required>
                    </div>
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
                <p class="signin-link">Already have an account? <a href="/login.php">Sign in</a></p>
            </div>
        </div>
    </div>
</body>

