<?php 
session_start();
require_once 'database/dbconnection.php';


$db = new Database();
$conn = $db->getDb();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['update'])) {
    if (!isset($_GET['student_id'])) {
        echo "<script>
            alert('Error: Missing student ID for update.');
            window.location.href = 'attendanceAdmin.php';
        </script>";
        exit;
    }

    $studentIdToEdit = $_GET['student_id'];

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $section = $_POST['section'];
    $newStudentId = $_POST['student_id'];

    if (empty($fname) || empty($lname) || empty($section) || empty($newStudentId)) {
        echo "<script>
            alert('Error: All fields are required.');
            window.history.back();
        </script>";
        exit;
    }
    if (!preg_match('/^\d{8}$/', $newStudentId)) {
        echo "<script>
            alert('Error: Student ID must be exactly 8 digits.');
            window.history.back();
        </script>";
        exit;
    }

    try {
        $sql = "UPDATE userinfo SET fname = :fname, lname = :lname, section = :section, student_id = :new_student_id, date_attended = NOW() WHERE student_id = :student_id_to_edit";
        $query = $conn->prepare($sql);

        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':lname', $lname, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':new_student_id', $newStudentId, PDO::PARAM_STR);
        $query->bindParam(':student_id_to_edit', $studentIdToEdit, PDO::PARAM_STR);

        $query->execute();

        if ($query->rowCount() > 0) {
            echo "<script>
                alert('Record updated successfully');
                window.location.href = 'attendanceAdmin.php';
            </script>";
        } else {
            echo "<script>
                alert('Record not found or no changes made.');
                window.location.href = 'attendanceAdmin.php';
            </script>";
        }
        exit;
    } catch (PDOException $e) {
        echo "<script>
            alert('Error updating record: " . addslashes($e->getMessage()) . "');
            window.history.back();
        </script>";
        exit;
    }
}

if (!isset($_GET['student_id'])) {
    echo "Error: No student ID provided.";
    exit;
}

$studentIdToFetch = $_GET['student_id'];

try {
    $sql_fetch = "SELECT fname, lname, section, student_id FROM userinfo WHERE student_id = :student_id_to_fetch";
    $query_fetch = $conn->prepare($sql_fetch);
    $query_fetch->bindParam(':student_id_to_fetch', $studentIdToFetch, PDO::PARAM_STR);
    $query_fetch->execute();

    $result = $query_fetch->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo "Error: No user found with the specified Student ID.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error fetching user data: " . $e->getMessage();
    exit;
}
?>
