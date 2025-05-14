<?php
require_once 'database/dbconnection.php'; 
session_start(); 

$db = new Database(); 
$conn = $db->getDb();

$sql_delete_old = "UPDATE userinfo SET fname = NULL, lname = NULL, date_attended = NULL WHERE date_attended < NOW() - INTERVAL 1 DAY";
$query_delete_old = $conn->prepare($sql_delete_old);
$query_delete_old-> execute();

if (isset($_GET['del']) && !empty($_GET['del'])) {
    $student_id = intval($_GET['del']);
    if ($student_id > 0) {
        $sql = "DELETE FROM userinfo WHERE student_id = :student_id";
        $query = $conn->prepare($sql);
        $query->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $query->execute();
        header('Location: attendanceAdmin.php');
        exit;
    }
}

if (isset($_GET['delSection']) && !empty($_GET['delSection'])) {
    $section = filter_var($_GET['delSection']);

    if (!empty($section)) {
        $sql_update = "UPDATE userinfo SET fname = NULL, lname = NULL, date_attended = NULL WHERE section = :section";

        $query_update = $conn->prepare($sql_update);
        $query_update->bindParam(':section', $section, PDO::PARAM_STR);

        try {
            $query_update->execute();

            header('Location: attendanceAdmin.php?status=cleared'); 
            exit;
        } catch (PDOException $e) {
            error_log("Error clearing attendance for section {$section}: " . $e->getMessage());
            header('Location: attendanceAdmin.php?status=clear_error');
            exit;
        }
    } else {
        header('Location: attendanceAdmin.php?status=invalid_id');
        exit;
    }
}

?>