<?php
    include("connection.php");
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['hospital_session']))
    {
        header("Location: login.php");
        exit();
    }
    if(isset($_GET['id']))
    {
        $id = intval($_GET['id']);
        $hid = intval($_SESSION['hospital_session']);
        $query = "UPDATE tbl_appointment SET status='Cancel' WHERE id=$id AND h_id=$hid";
        mysqli_query($connection,$query);
    }
    header("Location: appointment.php");
    exit();
?>
