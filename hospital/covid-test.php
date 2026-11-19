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
    $hid = intval($_SESSION['hospital_session']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Covid Test Requests - Vaccination System</title>
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
        padding: 6px 12px;
        text-decoration: none;
        border-radius: 5px;
        color: #fff;
        font-size: 13px;
        display: inline-block;
        background-color: #178066;
    }
    .badge-status
    {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
    }
    .badge-process { background-color: #fff3cd; color: #856404; }
    .badge-negative { background-color: #d4edda; color: #155724; }
    .badge-positive { background-color: #f8d7da; color: #721c24; }
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
                <h2 class="title">COVID-19 Test Requests</h2>
                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Test ID</th>
                                <th>Patient Name</th>
                                <th>Contact</th>
                                <th>Date</th>
                                <th>Result</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $query = "SELECT tbl_patient.name as 'pname', tbl_patient.contact as 'pcontact', tbl_hospital.name as 'hname', tbl_test.* FROM tbl_test LEFT JOIN tbl_patient ON tbl_test.p_id = tbl_patient.id LEFT JOIN tbl_hospital ON tbl_test.h_id=tbl_hospital.id WHERE tbl_test.h_id=$hid ORDER BY tbl_test.id DESC";
                                $result = mysqli_query($connection,$query);
                                if($result && mysqli_num_rows($result) > 0)
                                {
                                    foreach($result as $row)
                                    {
                                        $resLower = strtolower($row['result']);
                                        $badgeClass = 'badge-process';
                                        if($resLower === 'negative') { $badgeClass = 'badge-negative'; }
                                        elseif($resLower === 'positive') { $badgeClass = 'badge-positive'; }

                                        $testDate = !empty($row['date']) ? htmlspecialchars($row['date']) : 'Pending';

                                        echo 
                                        "<tr>
                                            <td>#" . $row['id'] . "</td>
                                            <td>" . htmlspecialchars($row['pname'] ?? 'N/A') . "</td>
                                            <td>" . htmlspecialchars($row['pcontact'] ?? 'N/A') . "</td>
                                            <td>$testDate</td>
                                            <td><span class='badge-status $badgeClass'>" . htmlspecialchars(ucfirst($row['result'])) . "</span></td>
                                            <td>
                                                <a href='generate_covid_report.php?id=" . $row['id'] . "&pname=" . urlencode($row['pname'] ?? '') . "'>Update Report</a>
                                            </td>
                                        </tr>";
                                    }
                                }
                                else
                                {
                                    echo "<tr><td colspan='6' style='color:#777;'>No test requests registered.</td></tr>";
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
