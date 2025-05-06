
function Register() {
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;

    if (name.length === 0 || email.length === 0 || password.length === 0 ) {
        alert('Please fill in all fields');
        return;
    }

    let formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('password', password);


    try {
        let res = axios.post('/api/or_register.php', formData);

        console.log(res);
        if (res.statusCode === 200) {

                alert('Registration successful');
                window.location.href = '/login.php';
                // setTimeout(() =>  window.location.href = '/login.php', 1000);

        } else {
            alert("Registration failed");
        }
    } catch (error) {
        console.log(error.message);
    }
}