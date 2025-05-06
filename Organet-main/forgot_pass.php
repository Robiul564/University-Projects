<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | OrgaNet</title>
    <link rel="stylesheet" href="/assets/css/forgot_pass_css.css">

</head>

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
                <h2>Forgot Password</h2>
                <p>Enter your email address, where we will send you a OTP to reset your password</p>

                <form action="#" method="post">
                    <div class="input-group">
                        <label for="name">Enter your email</label>
                        <input class="input-field" type="text" id="name" name="name" placeholder="Enter your email"
                            pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" required>
                    </div>
                    <button type="submit" class="submit-btn">Send Code</button>
                </form>
                <p class="signin-link">Already have an account? <a href="/login.php">Sign in</a></p>
            </div>
        </div>
    </div>
</body>

</html>