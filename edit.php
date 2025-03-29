<?php
require_once 'dbconnection.php';
$db = new Database();
$conn = $db->getDb();

if (isset($_POST['update'])) {
    $userid = intval($_GET['id']);
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $student_id = $_POST['student_id'];

    $sql = "UPDATE users SET fname = :fname, lname = :lname, student_id = :student_id WHERE id = :uid";
    $query = $conn->prepare($sql);

    $query->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query->bindParam(':lname', $lname, PDO::PARAM_STR);
    $query->bindParam(':student_id', $student_id, PDO::PARAM_STR);
    $query->bindParam(':uid', $userid, PDO::PARAM_INT);

    $query->execute();
    echo "<script>alert('Record updated successfully');</script>";
    echo "<script>window.location.href = 'attendancechecker.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        .transition-transform {
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 overflow-hidden">
    <div class="flex justify-end p-4">
        <div class="relative">
            <button id="dropdownButton" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
            <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 transition-transform transform -translate-y-2">
                <a href="form.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Home</a>
                <a href="attendancechecker.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Check Attendance</a>
                <a href="index.php" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Log-out</a>
            </div>
        </div>
    </div>
    <div class="flex flex-col justify-center items-center h-screen px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg px-6 py-8 shadow-2xl ring-2 ring-gray-900/10 max-w-md w-full space-y-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-center shadow-text">EDIT ATTENDANCE</h1>
            </div>
            <?php
            $userid = intval($_GET['id']);
            $sql = "SELECT * FROM users WHERE id = :uid";
            $query = $conn->prepare($sql);

            $query->bindParam('uid', $userid, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            $cnt = 1;
            if ($query->rowCount() > 0) {
                foreach ($result as $results) {
            ?>
                    <form id="userForm" method="POST" class="space-y-6">
                        <div>
                            <label for="fname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                            <input type="text" name="fname" id="fname" value="<?php echo htmlentities($results['fname']) ?>" required class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="lname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                            <input type="text" name="lname" id="lname" value="<?php echo htmlentities($results['lname']) ?>" required class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Student ID</label>
                            <input type="text" name="student_id" required pattern="\d{8}" maxlength="8" value="<?php echo htmlentities($results['student_id']) ?>" id="student_id" placeholder="20030315" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm">
                        </div>
                <?php
                    $cnt++;
                }
            }
                ?>
                <div class="flex justify-between">
                    <input type="submit" id="update" name="update" value="Submit" class="px-6 py-3 bg-green-500 text-white rounded-md shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <a href="attendancechecker.php"><input type="button" value="Cancel" class="px-6 py-3 bg-red-500 text-white rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"></a>
                </div>
                    </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#dropdownButton').click(function() {
                $('#dropdownMenu').toggleClass('hidden');
                $('#dropdownMenu').toggleClass('translate-y-0');
            });
        });
    </script>
</body>

</html>