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

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $query = "SELECT tbl_patient.*, tbl_city.name as 'cname' FROM tbl_patient LEFT JOIN tbl_city ON tbl_patient.cid=tbl_city.id WHERE tbl_patient.id=$id";
    $result = mysqli_query($connection,$query);
    $row = mysqli_fetch_assoc($result);

    if(!$row)
    {
        header("Location: patient.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details - Vaccination System</title>
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
        font-size: 30px;
        margin-bottom: 20px;
    }
    .detailsCard
    {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
    }
    .mainContent ul
    {
        list-style: none;
        padding: 0;
        margin: 0;
        flex: 1;
        min-width: 280px;
    }
    .mainContent ul li
    {
        padding: 12px 0;
        font-size: 16px;
        border-bottom: 1px solid #eee;
    }
    .mainContent ul li strong
    {
        display: inline-block;
        width: 160px;
        color: #555;
    }
    .image-box
    {
        width: 240px;
        height: 240px;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        background-color: #eee;
    }
    .image-box img
    {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }
    .btn-back
    {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 18px;
        background-color: #178066;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
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
            <!-- ======================= Cards ================== -->
            <div class="mainContent">
                <h2 class="title">Patient Detailed Profile</h2>
                
                <div class="detailsCard">
                    <div class="image-box">
                        <?php
                            $patImg = "../uploads/patients/user.jpg";
                            if(!empty($row['image']))
                            {
                                $cleanName = basename($row['image']);
                                if(file_exists(dirname(__DIR__) . "/uploads/patients/" . $cleanName)) {
                                    $patImg = "../uploads/patients/" . $cleanName;
                                } elseif(file_exists(dirname(__DIR__) . "/" . $row['image'])) {
                                    $patImg = "../" . $row['image'];
                                }
                            }
                        ?>
                        <img src="<?php echo htmlspecialchars($patImg);?>" alt="Patient Photo">
                    </div>
                    <ul>
                        <li><strong>Patient ID:</strong> #<?php echo $row['id']?></li>
                        <li><strong>Full Name:</strong> <?php echo htmlspecialchars($row['name'])?></li>
                        <li><strong>Contact:</strong> <?php echo htmlspecialchars($row['contact'] ?: 'Not provided')?></li>
                        <li><strong>City:</strong> <?php echo htmlspecialchars($row['cname'] ?: 'Not assigned')?></li>
                        <li><strong>Email Address:</strong> <?php echo htmlspecialchars($row['email'])?></li>
                        <li><strong>Residential Address:</strong> <?php echo htmlspecialchars($row['address'] ?: 'Not provided')?></li>
                        <li><strong>Gender:</strong> <?php echo htmlspecialchars(ucfirst($row['gender'] ?: 'Not specified'))?></li>
                        <li><strong>Account Status:</strong> <?php echo htmlspecialchars(ucfirst($row['status']))?></li>
                    </ul>
                </div>
                <a href="patient.php" class="btn-back">Return to Patients List</a>
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
