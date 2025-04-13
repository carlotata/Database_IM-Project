<?php
require_once 'database/delete.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Student Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(student_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'attendanceAdmin.php?del=' + student_id;
                }
            })
        }
    </script>
</head>

<body class="text-gray-800 text-center bg-gray-300">
    <nav class="bg-white shadow fixed-nav">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-3 relative">
                <div class="flex items-center space-x-3">
                    <img src="pic/logo.jpg" alt="Logo" class="w-10 h-10 rounded-full shadow-sm object-cover">
                    <div class="text-lg font-bold text-gray-800 hidden sm:block">
                        <span class="text-sm">ADMIN DASHBOARD [ JOHN CARL AVISO ]</span>
                    </div>
                </div>
                <div class="hidden md:flex space-x-4 nav-links" id="navLinks">
                    <a href="database/logout.php" class="text-red-600 hover:text-red-800 px-3 py-2 rounded-md text-sm font-medium">Log Out</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4 md:p-6 lg:p-8">
        <div class="mt-4">
            <?php
            try {
                $sql_sections = "SELECT DISTINCT section FROM userinfo ORDER BY section ASC";
                $query_sections = $conn->prepare($sql_sections);
                $query_sections->execute();
                $sections = $query_sections->fetchAll(PDO::FETCH_ASSOC);

                if (empty($sections)) {
                    echo "<div class='text-center p-6 bg-white rounded-lg shadow-md'>";
                    echo "<i class='fas fa-info-circle text-blue-500 fa-3x mb-4'></i>";
                    echo "<h2 class='text-xl font-semibold text-gray-700'>No Sections Found</h2>";
                    echo "<p class='text-gray-600 mt-2'>There are currently no student records in the system.</p>";
                    echo "</div>";
                } else {
                    $sections_displayed = 0;

                    foreach ($sections as $section) {
                        $currentSectionName = $section['section'];
                        $currentSectionDisplay = htmlentities($currentSectionName);

                        try {
                            $sql_students = "SELECT student_id, fname, lname, date_attended FROM userinfo WHERE section = :section ORDER BY student_id ASC";
                            $query_students = $conn->prepare($sql_students);
                            $query_students->bindParam(':section', $currentSectionName, PDO::PARAM_STR);
                            $query_students->execute();
                            $results = $query_students->fetchAll(PDO::FETCH_ASSOC);

                            echo "<div class='mb-10 bg-white p-4 sm:p-6 rounded-lg shadow-lg'>";
                            echo "<h2 class='text-center text-xl md:text-2xl font-semibold text-gray-700 mb-4'>Section: " . $currentSectionDisplay . "</h2>";
                            echo "<div class='overflow-x-auto'>";
                            echo "<table class='min-w-full table-auto w-full border-collapse border border-gray-300 text-sm'>";
                            echo "<thead>";
                            echo "<tr class='bg-gray-200 text-gray-600 uppercase leading-normal'>";
                            echo "<th class='border px-3 py-3 text-center'>Student ID</th>";
                            echo "<th class='border px-3 py-3 text-left'>First Name</th>";
                            echo "<th class='border px-3 py-3 text-left'>Last Name</th>";
                            echo "<th class='border px-3 py-3 text-center'>Last Attended Time</th>";
                            echo "<th class='border px-3 py-3 text-center'>Actions</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody class='text-gray-700'>";

                            if ($query_students->rowCount() > 0) {
                                foreach ($results as $result) {
                                    $studentId = htmlentities($result['student_id']);
                                    $timeDisplay = 'N/A';
                                    if (!empty($result['date_attended'])) {
                                        try {
                                            $dateTime = new DateTime($result['date_attended']);
                                            $timeDisplay = $dateTime->format('g:i A');
                                        } catch (Exception $dateError) {
                                            $timeDisplay = '<span class="text-gray-400 italic">Invalid Date</span>';
                                        }
                                    } else {
                                        $timeDisplay = '<span class="text-gray-400 italic">N/A</span>';
                                    }

                                    $displayFname = !empty($result['fname']) ? htmlentities($result['fname']) : '<span class="text-gray-400 italic">[Missing]</span>';
                                    $displayLname = !empty($result['lname']) ? htmlentities($result['lname']) : '<span class="text-gray-400 italic">[Missing]</span>';

                                    echo "<tr class='border-b border-gray-200 hover:bg-gray-100 transition duration-150 ease-in-out'>";
                                    echo "<td class='border px-3 py-2 text-center'>" . $studentId . "</td>";
                                    echo "<td class='border px-3 py-2 text-left'>" . $displayFname . "</td>";
                                    echo "<td class='border px-3 py-2 text-left'>" . $displayLname . "</td>";
                                    echo "<td class='border px-3 py-2 text-center'>" . $timeDisplay . "</td>";
                                    echo "<td class='border px-3 py-2 text-center whitespace-nowrap'>";
                                    echo "<a href='editPage.php?student_id=" . $studentId . "' class='text-blue-600 hover:text-blue-800 mr-3 transition duration-150 ease-in-out' title='Edit Record'><i class='fas fa-edit fa-fw'></i></a>";
                                    echo "<a href='javascript:void(0);' onclick='confirmDelete(" . $studentId . ")' class='text-red-600 hover:text-red-800 transition duration-150 ease-in-out' title='Delete Record'><i class='fas fa-trash-alt fa-fw'></i></a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='border px-4 py-3 text-center italic text-gray-500'>No student records found in this section.</td></tr>";
                            }

                            echo "</tbody></table>";
                            echo "</div>";
                            echo "</div>";
                        } catch (PDOException $e) {
                            error_log("Error fetching student details for section {$currentSectionDisplay} on admin page: " . $e->getMessage());
                            echo "<tr><td colspan='5' class='border px-4 py-3 text-center text-red-500 font-semibold'>Error loading student data for this section.</td></tr>";
                        }
                    }
                }
            } catch (PDOException $e) {
                echo "<div class='text-center p-6 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-md'>";
                echo "<i class='fas fa-exclamation-triangle fa-3x mb-4'></i>";
                echo "<h2 class='text-xl font-semibold'>Database Error</h2>";
                echo "<p class='text-gray-600 mt-2'>" . $e->getMessage() . "</p>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
    </div>
</body>

</html>