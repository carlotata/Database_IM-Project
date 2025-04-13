<?php
require_once 'dbcontroller.php';

function saveUsers($userinfo)
{
    $db = new DbController();

    if ($db->getState() == true) {
        $conn = $db->getDb();
        $stmt = $conn->prepare("UPDATE userinfo SET fname = :fname, lname = :lname, section = :section, date_attended = NOW() WHERE student_id = :student_id");
        $stmt->bindParam(':fname', $userinfo['fname']);
        $stmt->bindParam(':lname', $userinfo['lname']);
        $stmt->bindParam(':section', $userinfo['section']);
        $stmt->bindParam(':student_id', $userinfo['student_id']);
        $stmt->execute();
        return true;
    } else {
        return false;
    }
}

function createUsers($userinfo)
{
    $db = new DbController();

    if ($db->getState() == true) {
        $conn = $db->getDb();
        $stmt = $conn->prepare("INSERT INTO userinfo (student_id, pass, section) VALUES (:student_id, :pass, :section)");
        $stmt->bindParam(':student_id', $userinfo['student_id']);
        $stmt->bindParam(':pass', $userinfo['pass']);
        $stmt->bindParam(':section', $userinfo['section']);
        $stmt->execute();
        return true;
    } else {
        return false;
    }
}

function checkSectionExists($student_id, $section)
{
    $db = new DbController();

    if ($db->getState()) {
        $conn = $db->getDb();
        $stmt = $conn->prepare("SELECT * FROM userinfo WHERE student_id = :student_id AND section = :section AND DATE(date_attended) = CURDATE()");
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':section', $section);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    } else {
        return false;
    }
}

function checkUserExists($student_id)
{
    $db = new DbController();

    if ($db->getState()) {
        $conn = $db->getDb();
        $stmt = $conn->prepare("SELECT * FROM userinfo WHERE student_id = :student_id");
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
    
    return false;
}

function checkLogins($student_id, $password)
{
    $db = new DbController();

    if ($db->getState() == true) {
        $conn = $db->getDb();
        $stmt = $conn->prepare("SELECT * FROM userinfo WHERE student_id = :student_id AND pass = :pass");
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':pass', $password);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    } else {
        return false;
    }
}

?>

