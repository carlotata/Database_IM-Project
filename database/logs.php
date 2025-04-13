<?php
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

        if (!isset($_SESSION['student_id'])) {
            header('Location: index.php');
            exit; 
        }
    }
?>