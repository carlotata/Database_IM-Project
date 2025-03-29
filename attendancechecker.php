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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
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
                    window.location.href = 'attendancechecker.php?del=' + id;
                }
            })
        }
    </script>
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-center text-3xl font-bold text-gray-800">BSIT - 2C ATTENDANCE</h1>
        <div class="flex justify-between items-center mt-4">
            <div></div>
            <a href="form.php" class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded flex items-center">
                <i class="fas fa-plus mr-2"></i> Add new record
            </a>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table id="mytable" class="table-auto w-full border-collapse shadow-lg">
                <thead>
                    <tr class="bg-gray-300">
                        <th class="border px-4 py-2">No.</th>
                        <th class="border px-4 py-2">First Name</th>
                        <th class="border px-4 py-2">Last Name</th>
                        <th class="border px-4 py-2">Student ID</th>
                        <th class="border px-4 py-2">Attendance</th>
                        <th class="border px-4 py-2">Edit / Delete</th>
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
                    ?>
                            <tr class="bg-white border-b hover:bg-gray-100">
                                <td class="border px-4 py-2"><?php echo htmlentities($cnt); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlentities($result['fname']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlentities($result['lname']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlentities($result['student_id']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlentities($result['attendance_check']); ?></td>
                                <td class="border px-4 py-2 text-center">
                                    <a href="edit.php?id=<?php echo htmlentities($result['id']); ?>" class="text-blue-500 hover:text-blue-700"><i class="fas fa-edit"></i></a><span class="px-3"></span>
                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo htmlentities($result['id']); ?>)" class="text-red-500 hover:text-red-700"><i class="fas fa-trash-alt"></i></a>
                                </td>
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