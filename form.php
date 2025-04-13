<?php
require_once 'database/logs.php';
stayIndex();
$loggedInStudentId = $_SESSION['student_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .shadow-text {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);

            color: #1f2937;
        }

        .form-shadow {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);

        }

        .fixed-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;


        }

        body {
            padding-top: 80px;

        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;

                width: 100%;

                position: absolute;
                top: 100%;

                left: 0;
                background-color: white;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .nav-links.active {
                display: block;

            }

            .nav-links a {
                display: block;

                padding: 0.75rem 1rem;

                border-bottom: 1px solid #e5e7eb;
            }

            .nav-links a:last-child {
                border-bottom: none;
            }
        }
    </style>
</head>

<body class="bg-gray-300" style="font-family: 'Roboto', Arial, sans-serif;">

    <nav class="bg-white shadow fixed-nav">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-3 relative">
                <div class="flex items-center space-x-3">
                    <img src="pic/logo.jpg" alt="Logo" class="w-10 h-10 rounded-full shadow-sm object-cover">
                    <div class="text-lg font-bold text-gray-800 hidden sm:block">
                        Attendance Checker
                    </div>
                </div>

                <div class="hidden md:flex space-x-4 nav-links" id="navLinks">
                    <a href="attendanceStudent.php" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Today's Attendance</a>
                    <a href="database/logout.php" class="text-red-600 hover:text-red-800 px-3 py-2 rounded-md text-sm font-medium">Log Out</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex flex-col items-center justify-start px-6 pt-2 pb-10">

        <div class="w-full max-w-md text-center text-gray-600">
            <span id="date-time" class="text-sm"></span>
        </div>

        <div class="bg-white rounded-lg px-6 py-8 shadow-xl ring-1 ring-gray-900/5 w-full max-w-md space-y-6 form-shadow my-3">
            <div class="mb-5">
                <h1 class="text-2xl font-bold text-center shadow-text text-gray-800">ATTEND TODAY</h1>
            </div>
            <form id="userForm" class="space-y-5">
                <div>
                    <label for="student_id_attendance" class="block text-sm font-medium text-gray-700">Student ID</label>
                    <input type="text" name="student_id" id="student_id_attendance" pattern="\d{8}" maxlength="8"
                        value="<?php echo htmlspecialchars($loggedInStudentId); ?>" readonly
                        placeholder="e.g., 20030315"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md shadow-sm focus:outline-none sm:text-sm cursor-not-allowed" />
                </div>

                <div>
                    <label for="fname" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" id="fname" name="fname"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm"
                        placeholder="Enter your first name">
                </div>

                <div>
                    <label for="lname" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" id="lname" name="lname"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent sm:text-sm"
                        placeholder="Enter your last name">
                </div>

                <div>
                    <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
                    <select id="section" name="section"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="" selected disabled>Select your section</option>

                        <option value="2A">2A</option>
                        <option value="2B">2B</option>
                        <option value="2C">2C</option>
                        <option value="2D">2D</option>
                    </select>
                </div>

                <div class="flex justify-between space-x-3 pt-3">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">Submit</button>
                    <button type="reset" id="resetButton" class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">Reset</button>
                </div>
            </form>
        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <?php require_once 'database/time.php'; ?>

    <script>
        $(document).ready(function() {
            $('#resetButton').on('click', function() {
                $('#userForm')[0].reset();
                $('#student_id_attendance').val('<?php echo htmlspecialchars($loggedInStudentId); ?>');
                $('#section').val("");
            });

            $('#userForm').submit(function(e) {
                e.preventDefault();

                const student_id = $('#student_id_attendance').val().trim();
                const section = $('#section').val();
                const fname = $('#fname').val().trim();
                const lname = $('#lname').val().trim();

                if (!student_id || !section || !fname || !lname) {
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

                const checkFrm = new FormData();
                checkFrm.append("method", "checkSection");
                checkFrm.append("student_id", student_id);
                checkFrm.append("section", section);

                axios.post("database/handler.php", checkFrm)
                    .then(function(checkResponse) {

                        if (checkResponse.data.ret == 1) {

                            const saveFrm = new FormData();
                            saveFrm.append("method", "saveUser");
                            saveFrm.append("student_id", student_id);
                            saveFrm.append("fname", fname);
                            saveFrm.append("lname", lname);
                            saveFrm.append("section", section);

                            axios.post("database/handler.php", saveFrm)
                                .then(function(saveResponse) {
                                    if (saveResponse.data.ret == 1) {
                                        Swal.fire({
                                            icon: "success",
                                            title: "Attendance Submitted!",
                                            text: "Your attendance has been recorded successfully.",
                                            showConfirmButton: false,
                                            timer: 1000,
                                            timerProgressBar: true
                                        }).then(() => {

                                            window.location.href = "attendanceStudent.php";
                                        });
                                    } else {

                                        Swal.fire('Submission Failed', saveResponse.data.msg || "Could not record attendance. You might have already submitted today.", 'error');
                                    }
                                })
                                .catch(function(saveError) {
                                    console.error("Save attendance request failed:", saveError);
                                    Swal.fire('Request Error', 'An error occurred while submitting your attendance. Please try again.', 'error');
                                });

                        } else if (checkResponse.data.ret == 0) {

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'warning',
                                title: 'Student ID does not match section',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                            });
                        } else {

                            Swal.fire('Verification Error', checkResponse.data.msg || 'Could not verify section information. Please try again.', 'error');
                        }
                    })
            });
        });
    </script>
</body>

</html>