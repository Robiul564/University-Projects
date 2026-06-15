<?php
// KiChai Web Application - Landing/Entry Point
// Routes to main application pages
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KiChai - Welcome</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { background: white; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); padding: 40px; max-width: 600px; width: 90%; }
        h1 { color: #333; margin-bottom: 10px; text-align: center; }
        .subtitle { color: #666; text-align: center; margin-bottom: 40px; font-size: 14px; }
        .links { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        a { display: block; padding: 15px 20px; text-decoration: none; color: white; border-radius: 5px; text-align: center; font-weight: 500; transition: all 0.3s ease; }
        a:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .user-btn { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .vendor-btn { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .specialist-btn { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .admin-btn { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎯 KiChai</h1>
        <p class="subtitle">Select your role to continue</p>
        <div class="links">
            <a href="/user-SignUp Page-1/" class="user-btn">👤 User Sign Up</a>
            <a href="/Vendor-SignUp Page-1/" class="vendor-btn">🏪 Vendor Sign Up</a>
            <a href="/Specialist-SignUp Page-1/" class="specialist-btn">👨‍💼 Specialist Sign Up</a>
            <a href="/usertype/" class="admin-btn">🔐 Login</a>
        </div>
    </div>
</body>
</html>
