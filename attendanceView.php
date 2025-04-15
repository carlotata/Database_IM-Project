<?php
require_once 'database/logs.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="manifest" href="pwa-setup/manifest.json">
</head>

<body class="bg-gray-300" style="font-family: 'Roboto', Arial, sans-serif;">

    <nav class="bg-white sticky top-0 shadow fixed-nav">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-3 relative">
                <div class="flex items-center space-x-3">
                    <img src="pic/logo.jpg" alt="Logo" class="w-10 h-10 rounded-full shadow-sm object-cover">
                    <div class="text-lg font-bold text-gray-800 hidden sm:block">
                        <span id="date-time" class="text-sm"></span>
                    </div>
                </div>
                <div class="hidden md:flex space-x-4 nav-links" id="navLinks">
                    <a href="#2A" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">2A</a>
                    <a href="#2B" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">2B</a>
                    <a href="#2C" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">2C</a>
                    <a href="#2D" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">2D</a>
                    <a href="signup.php" class="text-gray-600 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium">Create Account</a>
                    <a href="index.php" class="text-gray-600 hover:text-blue-800 px-3 py-2 rounded-md text-sm font-medium">Log In</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-4 md:p-6 lg:p-8 pt-20">

        <h1 class="text-xl text-center text-gray-800 m-2">Who Attended Today</h1>

        <?php if ($message): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded text-center">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php
        $initial_sections_found = !empty($sections);
        $any_section_displayed = false;


        if ($initial_sections_found) {
            foreach ($sections as $sectionData) {
                $currentSection = $sectionData['section'];
                $students_found_for_section = false;
                $results = [];

                try {
                    $sql_check = "SELECT COUNT(*) as count FROM userinfo WHERE section = :section AND date(date_attended) = curdate() AND fname IS NOT NULL AND TRIM(fname) <> '' AND lname IS NOT NULL AND TRIM(lname) <> '' ORDER BY lname ASC";
                    $query_check = $conn->prepare($sql_check);
                    $query_check->bindParam(':section', $currentSection, PDO::PARAM_STR);
                    $query_check->execute();
                    $check_result = $query_check->fetch(PDO::FETCH_ASSOC);

                    if ($check_result && $check_result['count'] > 0) {
                        $students_found_for_section = true;
                    }
                } catch (PDOException $e) {
                    error_log("Error checking valid students for section {$currentSection} on public view: " . $e->getMessage());
                }

                if ($students_found_for_section) {
                    $any_section_displayed = true;
        ?>
                    <div id="<?php echo $currentSection; ?>"><br>
                        <div class="my-6 bg-white p-4 rounded shadow-md">
                            <h2 class='text-center text-xl font-semibold text-gray-700 my-2'>Section: <?php echo htmlentities($currentSection); ?></h2>
                            <div class="overflow-x-auto">
                                <table class='table-auto w-full border-collapse border border-gray-300'>
                                    <thead>
                                        <tr class='bg-gray-100 text-gray-600 uppercase text-sm leading-normal'>
                                            <th class='border px-3 py-2 text-center'>No.</th>
                                            <th class='border px-3 py-2 text-left'>First Name</th>
                                            <th class='border px-3 py-2 text-left'>Last Name</th>
                                            <th class='border px-3 py-2 text-center'>Time In</th>
                                            <th class='border px-3 py-2 text-center'>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-700 text-sm">
                                        <?php
                                        try {
                                            $sql_students = "SELECT fname, lname, date_attended
                                                         FROM userinfo
                                                         WHERE section = :section
                                                           AND date(date_attended) = curdate()
                                                           AND fname IS NOT NULL AND TRIM(fname) <> ''
                                                           AND lname IS NOT NULL AND TRIM(lname) <> ''
                                                         ORDER BY lname ASC, fname ASC";
                                            $query_students = $conn->prepare($sql_students);
                                            $query_students->bindParam(':section', $currentSection, PDO::PARAM_STR);
                                            $query_students->execute();
                                            $results = $query_students->fetchAll(PDO::FETCH_ASSOC);
                                            $cnt = 1;

                                            if ($query_students->rowCount() > 0) {
                                                foreach ($results as $result) {
                                                    $timeIn = 'N/A';
                                                    if (!empty($result['date_attended'])) {
                                                        try {
                                                            $time = '7:00AM';
                                                            $dateTime = new DateTime($result['date_attended']);
                                                            $timeIn = $dateTime->format('g:i A');
                                                            if ($timeIn <=  $time) {
                                                                $statusIcon = '<i class="fas fa-times-circle text-red-500 text-lg" title="Late"></i>';
                                                            } else {
                                                                $statusIcon = '<i class="fas fa-check-circle text-green-500 text-lg" title="Present"></i>';
                                                            }
                                                        } catch (Exception $dateError) {
                                                            $timeIn = '<span class="text-gray-400 italic">Invalid Time</span>';
                                                        }
                                                    }
                                        ?>
                                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                        <td class="border px-3 py-2 text-center"><?php echo $cnt; ?></td>
                                                        <td class="border px-3 py-2 text-left"><?php echo htmlentities($result['fname']); ?></td>
                                                        <td class="border px-3 py-2 text-left"><?php echo htmlentities($result['lname']); ?></td>
                                                        <td class="border px-3 py-2 text-center"><?php echo $timeIn; ?></td>
                                                        <td class="border px-3 py-2 text-center"><?php echo $statusIcon; ?></td>
                                                    </tr>
                                            <?php
                                                    $cnt++;
                                                }
                                            }
                                        } catch (PDOException $e) {
                                            error_log("Error fetching student details for section {$currentSection} on public view: " . $e->getMessage());
                                            ?>
                                            <tr>
                                                <td colspan="5" class="border px-4 py-3 text-center text-red-500 font-semibold">Error loading attendance data for this section.</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                <?php
                }
            }
        }


        if (!$message && !$any_section_displayed) {
            if ($initial_sections_found) {
                $no_data_message = "Attendance submitted today, but no records with valid names found.";
            } else {
                $no_data_message = "No students have submitted attendance yet today.";
            }
                ?>
                <div class="text-center text-gray-600 mt-10">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <p class="text-xl"><?php echo $no_data_message; ?></p>
                </div>
            <?php
        }
            ?>

            </div>
            <?php require_once('database/time.php'); ?>
            <script>
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.register('pwa-setup/service-worker.js');
                }
            </script>
</body>

</html>