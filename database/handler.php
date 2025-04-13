<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'userrouter.php';

$method = isset($_POST['method']) ? $_POST['method'] : 'notexists';

if (function_exists($method)) {
    call_user_func($method);
} else {
    notexists();
}

function notexists() {
    http_response_code(404);
    echo json_encode(array("ret" => -1, "msg" => "Method not found"));
}

function saveUser() {
    if (!isset($_POST['student_id'], $_POST['section'], $_POST['fname'], $_POST['lname']) || empty($_POST['student_id']) || empty($_POST['section']) || empty($_POST['fname']) || empty($_POST['lname'])) {
        http_response_code(400);
        echo json_encode(array("ret" => -2, "msg" => "Missing required fields for saveUser."));
        return;
    }

    $userinfo = array(
        "student_id" => $_POST['student_id'],
        "fname" => $_POST['fname'],
        "lname" => $_POST['lname'],
        "section" => $_POST['section']
    );
    $ret = saveUsers($userinfo);
    $msg = "";
    if ($ret == 0) $msg = "Failed to save attendance.";
    if ($ret == -1) $msg = "Already attended today.";

    echo json_encode(array("ret" => $ret, "msg" => $msg));
}

function createUser() {
    if (!isset($_POST['student_id'], $_POST['section'], $_POST['pass']) || empty($_POST['student_id']) || empty($_POST['section']) || empty($_POST['pass'])) {
        http_response_code(400);
        echo json_encode(array("ret" => -2, "msg" => "Missing required fields for createUser."));
        return;
    }

    $userinfo = array(
        "student_id" => $_POST['student_id'],
        "section" => $_POST['section'],
        "pass" => $_POST['pass']

    );
    $ret = createUsers($userinfo);
    $msg = ($ret == 0) ? "Student ID already exists or creation failed." : "User created successfully.";
    echo json_encode(array("ret" => $ret, "msg" => $msg));
}

function checkSection() {
    if (!isset($_POST['student_id'], $_POST['section']) || empty($_POST['student_id']) || empty($_POST['section'])) {
        http_response_code(400);
        echo json_encode(array("ret" => -2, "msg" => "Missing required fields for checkSection."));
        return;
    }
    $student_id = $_POST['student_id'];
    $section = $_POST['section'];
    $ret = checkSectionExists($student_id, $section);
    echo json_encode(array("ret" => $ret));
}

function checkUser() {
    if (!isset($_POST['student_id']) || empty($_POST['student_id'])) {
        http_response_code(400);
        echo json_encode(array("ret" => -2, "msg" => "Missing student_id for checkUser."));
        return;
    }
    $student_id = $_POST['student_id'];
    $ret = checkUserExists($student_id);
    echo json_encode(array("ret" => $ret));
}

function checkLogin() {
    if (!isset($_POST['student_id'], $_POST['pass']) || empty($_POST['student_id']) || empty($_POST['pass'])) {
        http_response_code(400);
        echo json_encode(array("ret" => -2, "msg" => "Missing required fields for checkLogin."));
        return;
    }

    $student_id = $_POST['student_id'];
    $password = $_POST['pass'];

    $ret = checkLogins($student_id, $password);

    if ($ret == 1) {
        session_regenerate_id(true);
        $_SESSION['student_id'] = $student_id;
    }

    echo json_encode(array("ret" => $ret));
}
?>

