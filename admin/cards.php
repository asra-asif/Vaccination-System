<?php
    include("connection.php");
?>
<div class="cardBox">
    <div class="card">
        <div>
            <?php
                $query = "SELECT COUNT(*) as count FROM tbl_patient";
                $result = mysqli_query($connection,$query);
                $row = mysqli_fetch_assoc($result);
                $patient_count = $row['count'] ?? 0;
            ?>
            <div class="numbers"><?php echo $patient_count;?></div>
            <div class="cardName">Patients</div>
        </div>
    </div>
    <div class="card">
        <div>
            <?php
                $query = "SELECT COUNT(*) as count FROM tbl_hospital";
                $result = mysqli_query($connection,$query);
                $row = mysqli_fetch_assoc($result);
                $hospital_count = $row['count'] ?? 0;
            ?>
            <div class="numbers"><?php echo $hospital_count;?></div>
            <div class="cardName">Hospitals</div>
        </div>
    </div>
    <div class="card">
        <div>
            <?php
                $query = "SELECT COUNT(*) as count FROM tbl_appointment";
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
                $query = "SELECT COUNT(*) as count FROM tbl_test";
                $result = mysqli_query($connection,$query);
                $row = mysqli_fetch_assoc($result);
                $test_count = $row['count'] ?? 0;
            ?>
            <div class="numbers"><?php echo $test_count;?></div>
            <div class="cardName">Covid Tests</div>
        </div>
    </div>
</div>
