<?php
require_once 'database/logs.php';
stayForm();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Creation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="manifest" href="pwa-setup/manifest.json">
</head>

<body class="h-screen bg-cover bg-no-repeat flex items-center justify-center font-[Roboto]" style="background-image: url('pic/cpc.jpg'); background-position: center calc(100% + 316px); font-family: 'Roboto', Arial, sans-serif;">
    <div class="absolute top-4 right-4">
        <img src="pic/logo.jpg" alt="Logo" class="w-20 h-auto rounded-full shadow-md">
    </div>
    <div class="absolute top-4 left-4 text-xl">
        <span id="date-time" class="text-white"></span>
    </div>

    <div id="signup-form-container" class="p-8 rounded-lg shadow-lg w-full max-w-lg mx-auto mt-24 backdrop-filter backdrop-blur-2xl">
        <form id="signupFormActual" action="#" method="POST">
            <h2 class="text-2xl font-bold mb-6 text-center text-white">Create Student Account</h2>
            <div class="mb-4 border-t pt-4">
                <label for="signup-student_id" class="block text-white text-sm font-bold mb-2">Student ID</label>
                <input type="text" id="signup-student_id" name="student_id"
                    maxlength="8"
                    pattern="[0-9]{8}"
                    title="Student ID must be 8 digits"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter your 8 digit student ID">
            </div>

            <div class="mb-4">
                <label for="signup-section" class="block text-white text-sm font-bold mb-2">Section</label>
                <select id="signup-section" name="section"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="" disabled selected>Select your section</option>
                    <option value="2A">2A</option>
                    <option value="2B">2B</option>
                    <option value="2C">2C</option>
                    <option value="2D">2D</option>
                </select>
            </div>

            <div class="flex flex-row items-center mb-10">
                <div class="w-1/2 pr-2">
                    <label for="signup-password" class="block text-white text-sm font-bold mb-2">Password</label>
                    <input type="password" id="signup-password" name="password"
                        minlength="5"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Minimum 5 characters">
                </div>
                <div class="w-1/2 pl-2">
                    <label for="signup-confirm-password" class="block text-white text-sm font-bold mb-2">Confirm Password</label>
                    <input type="password" id="signup-confirm-password" name="confirm_password"
                        minlength="5"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-black leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Re-enter password">
                </div>
            </div>

            <div class="flex items-center justify-between mb-4 mt-8">
                <button type="submit" id="signup-submit-btn"
                    class="bg-green-700 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition duration-150 ease-in-out">
                    SIGN UP
                </button>
            </div>
            <a href="index.php" class="block text-center text-gray-400 text-sm">
                Already have an account? <span class="font-semibold text-blue-600 hover:text-blue-900">Log In</span>
            </a>
        </form>
        <div class="mt-6 text-center border-t pt-4">
            <p class="text-sm text-gray-400 mb-2">Check class attendance:</p>
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
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('pwa-setup/service-worker.js');
        }
    </script>

    <script>
        $(document).ready(function() {
            const signupForm = document.getElementById('signupFormActual');
            signupForm.addEventListener('submit', (e) => {
                e.preventDefault();

                const student_id = document.getElementById('signup-student_id').value
                const section = document.getElementById('signup-section').value;
                const password = document.getElementById('signup-password').value
                const confirm_password = document.getElementById('signup-confirm-password').value

                if (!student_id || !section || !password || !confirm_password) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Please fill up the needed fields',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                    return;
                }

                if (password !== confirm_password) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Passwords do not match',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                    document.getElementById('signup-password').value = '';
                    document.getElementById('signup-confirm-password').value = '';
                    document.getElementById('signup-password').focus();
                    return;
                }

                const checkData = new FormData();
                checkData.append("method", "checkUser");
                checkData.append("student_id", student_id);

                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                axios.post("database/handler.php", checkData)
                    .then(function(response) {
                        Swal.close();
                        if (response.data.ret == 1) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Student ID already exists',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                            });
                        } else if (response.data.ret == 0) {
                            const createData = new FormData();
                            createData.append("method", "createUser");
                            createData.append("student_id", student_id);
                            createData.append("pass", password);
                            createData.append("section", section);

                            axios.post("database/handler.php", createData)
                                .then(function(responseCreate) {
                                    if (responseCreate.data.ret == 1) {
                                        Swal.fire({
                                            icon: "success",
                                            title: "Sign Up Successful!",
                                            text: "You can now log in.",
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then(() => {
                                            window.location.href = 'index.php';
                                        });
                                    } else {
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            icon: 'error',
                                            title: 'Student ID could not be created',
                                            showConfirmButton: false,
                                            timer: 2000,
                                            timerProgressBar: true,
                                        });
                                    }
                                })
                        } else {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: response.data.msg || 'An unexpected error occurred while checking the Student ID.',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,

                            });
                        }
                    })
                    .catch(function(error) {
                        Swal.close();
                        console.error("Check User request failed:", error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Network Error',
                            text: 'Could not reach server to check Student ID. Please check your connection.'
                        });
                    });
            });
        });
    </script>
</body>

</html>