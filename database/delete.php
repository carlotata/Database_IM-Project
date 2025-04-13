<?php
require_once 'database/dbconnection.php';
session_start();

$db = new Database();
$conn = $db->getDb();

    $sql_delete_old = "DELETE FROM userinfo WHERE date(date_attended) < curdate()";
    $query_delete_old=$conn->prepare($sql_delete_old);
    $query_delete_old->execute(); 

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

?>