<?php
require_once 'database/logs.php';
stayForm();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Attendance Checker</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <meta name="theme-color" content="#007bff">
</head>

<body class="min-h-screen bg-cover bg-no-repeat flex items-center justify-center p-4 text-white" style="background-image: url('pic/cpc.jpg'); background-position: center bottom;">
    <div class="absolute top-4 right-4">
        <img src="pic/logo.jpg" alt="Logo" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full shadow-md object-cover">
    </div>
    <div class="absolute top-4 left-4 text-xl text-white">
        <span id="date-time"></span>
    </div>

    <div id="login-form-container" class="p-8 rounded-lg shadow-lg w-full max-w-md mx-auto mt-24 backdrop-filter backdrop-blur-2xl">
        <h2 class="text-center text-2xl font-bold text-black-800 mb-6">Student Login</h2>
        <form id="loginFormActual" action="#" method="POST">
            <div class="mb-4 border-t pt-4">
                <label for="login-student_idname" class="block text-black-700 text-sm font-bold mb-2">Student ID</label>
                <input type="text" id="login-student_idname" name="student_id"
                    placeholder="Enter your 8 digit student ID"
                    maxlength="8"
                    pattern="[0-9]{8}"
                    title="Student ID must be 8 digits"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="mb-6">
                <label for="login-password" class="block text-black-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="login-password" name="pass"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-black mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="******************">
            </div>
            <div class="flex items-center justify-between mb-4">
                <button type="submit" id="login-submit-btn"
                    class="bg-red-800 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    LOG IN
                </button>
            </div>
            <div class="text-center mb-6">
                <a href="signup.php" class="block text-center text-gray-600 text-sm mt-4">
                    Don't have an account? <span class="font-semibold text-blue-600 hover:text-blue-800">Create Account</span>
                </a>
            </div>
        </form>
        <div class="mt-6 text-center border-t pt-4">
            <p class="text-sm text-gray-600 mb-2">Check class attendance:</p>
            <a href="attendanceView.php">
                <button class="bg-blue-900 text-white py-2 px-6 rounded hover:bg-blue-700 text-sm transition duration-150 ease-in-out">
                    VIEW TODAY'S ATTENDANCE
                </button>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <?php require_once 'database/time.php'; ?>

    <script>
        $(document).ready(function() {
            const loginForm = document.getElementById('loginFormActual');
            if (loginForm) {
                loginForm.addEventListener('submit', (e) => {
                    e.preventDefault();

                    const userAdmin = document.getElementById('login-student_idname').value.trim();
                    const passAdmin = document.getElementById('login-password').value.trim();

                    if (userAdmin === '11111111' && passAdmin === "admin" || passAdmin === "ADMIN") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Admin Login Success',
                            text: 'Redirecting to Admin Panel...',
                            showConfirmButton: false,
                            timer: 1000

                        }).then(() => {
                            window.location.href = "attendanceAdmin.php";
                        });
                        return;
                    }

                    const student_idname = userAdmin;
                    const password = passAdmin;

                    if (!student_idname || !password) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'warning',
                            title: 'Fields required',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: true,
                        });
                        return;
                    }

                    const frm = new FormData();
                    frm.append("method", "checkLogin");
                    frm.append("student_id", student_idname);
                    frm.append("pass", password);

                    axios.post("database/handler.php", frm)
                        .then(function(response) {
                            Swal.close();
                            if (response.data.ret == 0) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'warning',
                                    title: 'Student ID or Password is wrong',
                                    showConfirmButton: false,
                                    timer: 1000,
                                    timerProgressBar: true,
                                });
                                document.getElementById('login-student_idname').value = '';
                                document.getElementById('login-password').value = '';
                            } else if (response.data.ret == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Login Successful!',
                                    text: 'Redirecting...',
                                    showConfirmButton: false,
                                    timer: 1000
                                }).then(() => {
                                    window.location.href = "form.php";
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Login Error',
                                    text: 'An unexpected error occurred.'
                                });
                            }
                        })
                        .catch(function(error) {
                            Swal.close();
                            console.error("Login request failed:", error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Network Error',
                                text: 'Could not connect to server.'
                            });
                        });
                });
            }
        });
    </script>
</body>

</html>