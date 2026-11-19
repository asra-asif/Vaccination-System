<?php
    include("connection.php");
    $hid = isset($_SESSION['hospital_session']) ? intval($_SESSION['hospital_session']) : 0;
?>
<div class="cardBox">
    <div class="card">
        <div>
            <?php
                $query = "SELECT COUNT(*) as count FROM tbl_appointment WHERE h_id=$hid";
                $result = mysqli_query($connection,$query);
                $row = mysqli_fetch_assoc($result);
                $appointment_count = $row['count'] ?? 0;
            ?>
            <div class="numbers"><?php echo $appointment_count;?></div>
            <div class="cardName">Appointments</div>
        </div>
    </div>
    <div class="card">
        <div>
            <?php
                $query = "SELECT COUNT(*) as count FROM tbl_test WHERE h_id=$hid";
                $result = mysqli_query($connection,$query);
                $row = mysqli_fetch_assoc($result);
                $test_count = $row['count'] ?? 0;
            ?>
            <div class="numbers"><?php echo $test_count;?></div>
            <div class="cardName">Covid Tests</div>
        </div>
    </div>
</div>
