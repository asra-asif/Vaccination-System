<?php
    include("connection.php");
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['admin_session']))
    {
        header("Location: login.php");
        exit();
    }
    if(isset($_GET['id']))
    {
        $id = intval($_GET['id']);
        $query = "UPDATE tbl_feedback SET status='hide' WHERE id=$id";
        mysqli_query($connection,$query);
    }
    header("Location: feedback.php");
    exit();
?>
