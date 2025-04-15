<?php
require_once 'database/dbcontroller.php';

$sections = [];
$message = '';
$message_type = 'error';

try {
    $db = new DbController();
    $conn = $db->getDb();

    $sql_sections = "SELECT DISTINCT section FROM userinfo WHERE date(date_attended) = curdate() ORDER BY section ASC";
    $query_sections = $conn->prepare($sql_sections);
    $query_sections->execute();
    $sections = $query_sections->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Database Error: Could not retrieve attendance data.";
    error_log("Database Error on student attendance view (initial query): " . $e->getMessage());
    $sections = [];
}

    function stayForm() {
        session_start();

        if (isset($_SESSION['student_id'])) {
            header('Location: form.php');
            exit;
        }

        session_destroy();
    }

    function stayIndex() {
        session_start();

        if (!isset($_SESSION['student_id']) && !isset($_SESSION['section'])) {
            header('Location: index.php');
            exit; 
        }
    }

?>