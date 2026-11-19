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
    <title>Patient Management - Vaccination System</title>
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
        margin-bottom: 15px;
    }
    .mainContent table
    {
        width: 100%;
        border-collapse: collapse;
    }
    .mainContent table, td, th 
    {
        margin-top: 20px;
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
        padding: 6px 12px;
        text-decoration: none;
        border-radius: 5px;
        color: #fff;
        font-size: 13px;
        display: inline-block;
        margin: 2px;
    }
    .mainContent .btnedit
    {
        background-color: #178066;
    }
    .mainContent .btnview
    {
        background-color: #007bff;
    }
    .mainContent .btn
    {
        display: inline-block;
        margin-bottom: 15px;
        padding: 10px 18px;
        background-color: #178066;
        text-decoration: none;
        border-radius: 6px;
        color: #fff;
        font-weight: 500;
    }
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
                <h2 class="title">Patients Directory</h2>

                <a href="add_new_patient.php" class="btn">Register New Patient</a>

                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>City</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = "SELECT tbl_patient.*, tbl_city.name as 'cname' FROM tbl_patient LEFT JOIN tbl_city ON tbl_patient.cid=tbl_city.id ORDER BY tbl_patient.id DESC";
                                $result = mysqli_query($connection,$query);
                                if($result && mysqli_num_rows($result) > 0)
                                {
                                    foreach($result as $row)
                                    {
                                        $cityName = !empty($row['cname']) ? htmlspecialchars($row['cname']) : 'N/A';
                                        echo 
                                        "<tr>
                                            <td>#" . $row['id'] . "</td>
                                            <td>" . htmlspecialchars($row['name']) . "</td>
                                            <td>" . htmlspecialchars($row['contact']) . "</td>
                                            <td>$cityName</td>
                                            <td>
                                                <a href='edit_patient.php?id=" . $row['id'] . "' class='btnedit'>Edit</a>
                                                <a href='view_patient.php?id=" . $row['id'] . "' class='btnview'>View</a>
                                            </td>
                                        </tr>";
                                    }
                                }
                                else
                                {
                                    echo "<tr><td colspan='5' style='color:#777;'>No patients found.</td></tr>";
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
