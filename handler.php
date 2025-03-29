<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'userrouter.php';

$method = isset($_POST['method']) ? $_POST['method'] : notexists();

if (function_exists($method)) {
    call_user_func($method);
} else {
    notexists();
}
function notexists()
{
    echo json_encode(array("retval" => -1));
}

function saveUser()
{
    $user = array(
        "fname" => $_POST['fname'],
        "lname" => $_POST['lname'],
        "student_id" => $_POST['student_id']
    );
    $ret = saveUsers($user);
    echo json_encode(array("ret" => $ret));
}
?>