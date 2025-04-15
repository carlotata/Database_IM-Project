<?php
require_once 'db_connection.php';

function archiveOldAttendance(PDO $conn, int $days_threshold = 90): array
{
    $threshold_date_str = date('Y-m-d H:i:s', strtotime("-{$days_threshold} days"));
    $archived_count = 0;

    try {
        $conn->beginTransaction();

        $sql_insert = "INSERT INTO userinfo_archive (student_id, fname, lname, section, date_attended)
                       SELECT student_id, fname, lname, section, date_attended
                       FROM userinfo
                       WHERE date_attended < :threshold_date";

        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindParam(':threshold_date', $threshold_date_str);
        $stmt_insert->execute();

        $archived_count = $stmt_insert->rowCount();

        if ($archived_count > 0) {
            $sql_delete = "DELETE FROM userinfo WHERE date_attended < :threshold_date";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bindParam(':threshold_date', $threshold_date_str);
            $stmt_delete->execute();

            if ($stmt_delete->rowCount() != $archived_count) {
                throw new Exception("Mismatch between inserted and deleted record counts during archive.");
            }
        }

        $conn->commit();
        return ['success' => true, 'archived_count' => $archived_count, 'error' => null];
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Archiving Error: " . $e->getMessage());
        return ['success' => false, 'archived_count' => 0, 'error' => $e->getMessage()];
    }
}

