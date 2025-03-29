<?php
    require_once 'dbcontroller.php';

    function saveUsers($users){
        $db = new DbController();
        
        if($db->getState() == true){
            $conn = $db->getDb();
            $stmt = $conn->prepare("INSERT INTO users (fname, lname, student_id) VALUES (:fname, :lname, :student_id)");
            $stmt->bindParam(':fname', $users['fname']);
            $stmt->bindParam(':lname', $users['lname']);
            $stmt->bindParam(':student_id', $users['student_id']);
            $stmt->execute();
            return true;
        }else{
            return false;
        }

    }
?>