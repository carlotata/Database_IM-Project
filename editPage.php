<?php
require_once 'database/edit.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Attendance Record</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex flex-col font-sans">

    <nav class="bg-white dark:bg-gray-800 shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4 relative">
                <div class="flex items-center space-x-3">
                    <img src="pic/logo.jpg" alt="Logo" class="w-10 h-10 rounded-full shadow-sm object-cover border-2 border-gray-200 dark:border-gray-700">
                    <div class="text-lg font-bold text-gray-800 dark:text-white hidden sm:block">
                        <span class="text-sm tracking-wide">ADMIN DASHBOARD [ EDIT ]</span>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-2" id="navLinks">
                    <a href="attendanceAdmin.php" class="text-gray-600 dark:text-gray-300 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out hover:text-blue-800 ">Today's Attendance</a>
                    <a href="database/logout.php" class="text-red-600 dark:text-red-400 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out hover:text-red-800">Log Out</a>
                </div>
    </nav>

    <div class="flex flex-col justify-center items-center flex-grow px-4 sm:px-6 lg:px-8 pt-24 pb-12">

        <div class="text-center mb-6">
            <span id="date-time" class="text-base text-gray-600 dark:text-gray-400"></span>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg p-8 shadow-xl max-w-md w-full space-y-6 border border-gray-200 dark:border-gray-700">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-white">EDIT ATTENDANCE</h1>
            </div>

            <?php if ($result): ?>
                <form id="editUserForm" method="POST" action="editPage.php?student_id=<?php echo htmlspecialchars($studentIdToFetch); ?>" class="space-y-6">
                    <div>
                        <label for="fname" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                        <input type="text" name="fname" id="fname" value="<?php echo htmlspecialchars($result['fname']); ?>" required
                            class="mt-1 block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 rounded-md shadow-sm  sm:text-sm">
                    </div>

                    <div>
                        <label for="lname" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                        <input type="text" name="lname" id="lname" value="<?php echo htmlspecialchars($result['lname']); ?>" required
                            class="mt-1 block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 rounded-md shadow-sm  sm:text-sm">
                    </div>

                    <div>
                        <label for="section" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Section</label>
                        <select id="section" name="section" required
                            class="mt-1 block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 rounded-md shadow-sm  sm:text-sm appearance-none">
                            <option value="" disabled>Select your section</option>
                            <?php
                            $sections = ['2A', '2B', '2C', '2D'];
                            foreach ($sections as $sec) {
                                $selected = (isset($result['section']) && $result['section'] == $sec) ? 'selected' : '';
                                echo "<option value=\"" . htmlspecialchars($sec) . "\" $selected>" . htmlspecialchars($sec) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student ID</label>
                        <input type="text" name="student_id" id="student_id" required pattern="\d{8}" maxlength="8" value="<?php echo htmlspecialchars($result['student_id']); ?>" placeholder="e.g., 20030315"
                            class="mt-1 block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 rounded-md shadow-sm  sm:text-sm">
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between pt-6 space-y-4 sm:space-y-0 sm:space-x-4">
                        <input type="submit" id="update" name="update" value="Update Record"
                            class="w-full sm:w-auto flex-1 px-6 py-2.5 bg-green-600 text-white font-semibold rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 cursor-pointer transition duration-150 ease-in-out text-center">
                        <a href="attendanceAdmin.php"
                            class="w-full sm:w-auto flex-1 text-center px-6 py-2.5 bg-red-600 text-white font-semibold rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <p class="text-center text-red-600 dark:text-red-400 font-medium">Could not load user data.</p>
                <div class="text-center mt-6">
                    <a href="attendanceAdmin.php"
                        class="inline-block px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out">
                        Back to List
                    </a>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <?php require_once 'database/time.php'; ?>

</body>

</html>