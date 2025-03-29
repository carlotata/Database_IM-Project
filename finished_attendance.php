<?php
require_once 'dbconnection.php';

$db = new Database();
$conn = $db->getDb();

if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $sql = "DELETE FROM users WHERE id = :id";
    $query = $conn->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    echo "<script>alert('Record deleted successfully');</script>";
    echo "<script>window.location.href = 'attendancechecker.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">STUDENT ATTENDED TODAY</h3>
            <a href="index.php" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Back to Homepage
            </a>
        </div>

        <div class="mt-4">
            <table id="mytable" class="table-auto w-full border-collapse shadow-lg">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">#</th>
                        <th class="border px-4 py-2">First Name</th>
                        <th class="border px-4 py-2">Last Name</th>
                        <th class="border px-4 py-2">Student ID</th>
                        <th class="border px-4 py-2">Attendance</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Prepare and execute the query
                    $sql = "SELECT * FROM users";
                    $query = $conn->prepare($sql);
                    $query->execute();

                    // Fetch results as an associative array
                    $results = $query->fetchAll(PDO::FETCH_ASSOC);

                    // Initialize a counter for row numbering
                    $cnt = 1;

                    // Check if any rows were returned
                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) {
                            // Determine the status based on attendance_check value
                            $status = $result['attendance_check'] ? '<i class="fas fa-check text-green-500"></i>' : 'Absent';
                    ?>
                            <tr class="bg-white border-b hover:bg-gray-100">
                                <td class="border px-4 py-2"><?php echo htmlentities($cnt); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlentities($result['fname']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlentities($result['lname']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlentities($result['student_id']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlentities($result['attendance_check']); ?></td>
                                <td class="border px-4 py-2"><?php echo $status; ?></td>
                            </tr>
                        <?php
                            $cnt++;
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6" class="border px-4 py-2 text-center">NO RECORD FOUND</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>