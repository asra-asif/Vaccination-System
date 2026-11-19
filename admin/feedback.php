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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management - Vaccination System</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<style>
    .mainContent
    {
        padding: 30px;
    }
    .mainContent .title
    {
        font-size: 32px;
        margin-bottom: 20px;
    }
    .mainContent table
    {
        width: 100%;
        border-collapse: collapse;
    }
    .mainContent table, td, th 
    {
        margin-top: 15px;
        border: 1px solid #ddd;
        text-align: center;
        padding: 10px;
    }
    .mainContent table th
    {
        background-color: #178066;
        color: #fff;
    }
    .mainContent table a
    {
        padding: 6px 14px;
        text-decoration: none;
        border-radius: 5px;
        color: #fff;
        font-size: 13px;
        display: inline-block;
    }
    .mainContent .btndeactivate
    {
        background-color: #dc3545;
    }
    .mainContent .btnactivate
    {
        background-color: #28a745;
    }
    .badge-status
    {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
    }
    .badge-show { background-color: #d4edda; color: #155724; }
    .badge-hide { background-color: #f8d7da; color: #721c24; }
</style>

<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <?php
            include("navigation.php");
        ?>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <div class="user">
                    <a href='profile.php'><img src="assets/imgs/customer01.jpg" alt="Profile"></a>
                </div>
            </div>
            <!-- ======================= Content ================== -->
            <div class="mainContent">
                <h2 class="title">Patient Feedback & Reviews</h2>
                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient Name</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = "SELECT tbl_feedback.*, tbl_patient.name as 'pname' FROM tbl_feedback LEFT JOIN tbl_patient ON tbl_feedback.p_id = tbl_patient.id ORDER BY tbl_feedback.id DESC";
                                $result = mysqli_query($connection,$query);
                                if($result && mysqli_num_rows($result) > 0)
                                {
                                    foreach($result as $row)
                                    {
                                        $statusClass = ($row['status'] === 'show') ? 'badge-show' : 'badge-hide';
                                        echo 
                                        "<tr>
                                            <td>#" . $row['id'] . "</td>
                                            <td>" . htmlspecialchars($row['pname'] ?? 'N/A') . "</td>
                                            <td style='max-width:350px; text-align:left;'>" . htmlspecialchars($row['message']) . "</td>
                                            <td><span class='badge-status $statusClass'>" . htmlspecialchars(ucfirst($row['status'])) . "</span></td>
                                            <td>";
                                            if($row['status'] === "hide")
                                            {
                                                echo "<a href='show_feedback.php?id=" . $row['id'] . "' class='btnactivate'>Publish</a>"; 
                                            }
                                            else
                                            {
                                                echo "<a href='hide_feedback.php?id=" . $row['id'] . "' class='btndeactivate'>Hide</a>"; 
                                            }   
                                        echo "</td>
                                        </tr>";
                                    }
                                }
                                else
                                {
                                    echo "<tr><td colspan='5' style='color:#777;'>No feedback records found.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>
