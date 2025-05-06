document.getElementById('login-form').addEventListener('submit', async function (event) {
    event.preventDefault(); // Prevent default form submission

    // Collect form data
    const form = event.target;
    const formData = new FormData(form);

    // // Handle "Remember Me" functionality
    // const rememberCheckbox = document.getElementById('remember');
    // const emailInput = document.getElementById('email');
    // const passwordInput = document.getElementById('password');
    //
    // if (rememberCheckbox.checked) {
    //     localStorage.setItem('email', emailInput.value);
    //     localStorage.setItem('rememberMe', 'true');
    // } else {
    //     localStorage.removeItem('email');
    //     localStorage.removeItem('rememberMe');
    // }

    try {
        // Send form data to the backend
        const response = await fetch('/api/mm_login.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json(); // Parse the JSON response

        // Handle the response
        if (result.statusCode === 6) {
            alert('Login successful! Redirecting to dashboard...');
            window.location.href = '../../dashboard.php';
        } else if (result.statusCode === 7) {
            alert('Incorrect password. Please try again.');
        } else if (result.statusCode === 3) {
            alert('User not found. Please check your email or sign up.');
        } else {
            alert(result.message || 'An error occurred. Please try again.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An unexpected error occurred. Please try again later.');
    }
});

// // On Page Load: Populate Email if "Remember Me" was Checked
// window.onload = function () {
//     const savedEmail = localStorage.getItem('email');
//     const rememberMe = localStorage.getItem('rememberMe');
//
//     if (savedEmail && rememberMe === 'true') {
//         document.getElementById('email').value = savedEmail;
//         document.getElementById('remember').checked = true;
//     }
// };
