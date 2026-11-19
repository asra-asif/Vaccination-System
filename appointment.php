<?php
  require_once __DIR__ . '/includes/db.php';
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  if(!isset($_SESSION['patient_session']))
  {
      header("Location: login.php");
      exit();
  }

  $pid = intval($_SESSION['patient_session']);
  $query = "SELECT * FROM tbl_patient WHERE id=$pid";
  $result = mysqli_query($connection,$query);
  $patient = mysqli_fetch_assoc($result);

  if(!$patient)
  {
      session_destroy();
      header("Location: login.php");
      exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">

  <title>Vaccination Appointments - Vaccination System</title>

  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
  <link href="assets/css/font-awesome.min.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
  <link href="assets/css/responsive.css" rel="stylesheet" />
</head>
<style>
  header
  {
    background-color: #178066;
  }
  .mainContent
  {
    display: flex;
    justify-content: space-between;
    padding: 50px 15px;
    flex-wrap: wrap;
    gap: 30px;
  }
  .mainContent .leftSide
  {
    flex: 1;
    min-width: 320px;
  }
  .mainContent .leftSide input,
  .mainContent .leftSide select
  {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    outline: none;
    background-color: #f9f9f9;
    border-radius: 6px;
    margin: 10px 0px;
    box-sizing: border-box;
  }
  .mainContent .leftSide input[type="submit"]
  {
    background-color: #178066;
    color: #fff;
    font-size: 18px;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s;
  }
  .mainContent .leftSide input[type="submit"]:hover
  {
    background-color: #12634f;
  }
  .mainContent .rightSide
  {
    flex: 1.2;
    min-width: 340px;
  }
  .mainContent .rightSide table
  {
    width: 100%;
    margin-top: 17px;
    text-align: center;
    border-collapse: collapse;
  }
  .mainContent .rightSide table, th, td
  {
    border: 1px solid #ddd;
    padding: 10px;
  }
  .mainContent .rightSide table thead
  {
    background-color: #178066;
    color: #fff;
  }
  .badge-status
  {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 500;
  }
  .badge-pending { background-color: #fff3cd; color: #856404; }
  .badge-booked { background-color: #d4edda; color: #155724; }
  .badge-cancelled { background-color: #f8d7da; color: #721c24; }
</style>
<body>
    <?php
        include("includes/header.php");
    ?>
    <div class="mainContent container">
      <div class="leftSide">
        <h2>Book Vaccination Appointment</h2>
        <form method="post">
            <input type="hidden" value="<?php echo $patient['id'];?>" name="pid">
            <input type="text" readonly value="<?php echo htmlspecialchars($patient['name']);?>">
            <select name="hid" required>
                <option value="" disabled selected>Select Hospital</option>
                <?php 
                    $query = "SELECT * FROM tbl_hospital WHERE status='activate'";
                    $result = mysqli_query($connection,$query);
                    if($result)
                    {
                      foreach($result as $row)
                      {
                          echo "<option value='$row[id]'>" . htmlspecialchars($row['name']) . "</option>";
                      }
                    }
                ?>
            </select>
            <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
            <select name="time" required>
                <option value="" disabled selected>Select Time Slot</option>
                <option value="9-11">09:00 AM - 11:00 AM</option>
                <option value="11-1">11:00 AM - 01:00 PM</option>
                <option value="1-3">01:00 PM - 03:00 PM</option>
                <option value="3-5">03:00 PM - 05:00 PM</option>
                <option value="5-7">05:00 PM - 07:00 PM</option>
            </select>
            <select name="vid" required>
                <option value="" disabled selected>Select Vaccine</option>
                <?php 
                    $query = "SELECT * FROM tbl_vaccine WHERE status='available'";
                    $result = mysqli_query($connection,$query);
                    if($result)
                    {
                      foreach($result as $row)
                      {
                          echo "<option value='$row[id]'>" . htmlspecialchars($row['name']) . "</option>";
                      }
                    }
                ?>
            </select>
            <input type="submit" value="Confirm Appointment" name="btnbook">
        </form>
        <?php
            if(isset($_POST['btnbook']))
            {
                $form_pid = intval($_POST['pid']);
                $hid = intval($_POST['hid']);
                $date = mysqli_real_escape_string($connection, $_POST['date']);
                $time = mysqli_real_escape_string($connection, $_POST['time']);
                $vid = intval($_POST['vid']);
                
                $existing_appointment = mysqli_query($connection,"SELECT * FROM tbl_appointment WHERE h_id=$hid and date='$date' and time='$time' and p_id=$form_pid");

                if($existing_appointment && mysqli_num_rows($existing_appointment) > 0)
                {
                    echo 
                    "<script>
                        alert('You already have an appointment booked at this hospital and time slot.');
                    </script>";
                }
                else
                {
                  $current_vaccine = mysqli_query($connection, "SELECT * FROM tbl_appointment WHERE p_id=$form_pid ORDER BY id DESC LIMIT 1");
                  $existing_vaccine = mysqli_fetch_assoc($current_vaccine);

                  if (empty($existing_vaccine) || intval($existing_vaccine['v_id']) === $vid) 
                  {
                      $query = "INSERT INTO tbl_appointment(p_id, h_id, date, time, v_id, status) VALUES ($form_pid, $hid, '$date', '$time', $vid, 'pending')";
                      $result = mysqli_query($connection, $query);
                      if ($result) {
                          echo "<script>
                                  alert('Appointment Booked Successfully');
                                  window.location.href='appointment.php';
                                </script>";
                          exit();
                      }
                  } 
                  else 
                  {
                      echo "<script>
                              alert('Vaccine consistency requirement: All doses must be of the same vaccine.');
                            </script>";
                  }
                }
            }
        ?>
      </div>
      <div class="rightSide">
        <h2>Your Appointments</h2>
        <div class="table-responsive">
          <table>
            <thead>
                <tr>
                    <th>Hospital</th>
                    <th>Vaccine</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $query = "SELECT tbl_hospital.name as 'hname', tbl_vaccine.name as 'vname', tbl_appointment.* FROM tbl_appointment LEFT JOIN tbl_hospital ON tbl_appointment.h_id = tbl_hospital.id LEFT JOIN tbl_vaccine ON tbl_appointment.v_id = tbl_vaccine.id WHERE tbl_appointment.p_id=$pid ORDER BY tbl_appointment.id DESC";
                    $result = mysqli_query($connection,$query);
                    if($result && mysqli_num_rows($result) > 0)
                    {
                      foreach($result as $row)
                      {
                          $status = strtolower($row['status']);
                          $badgeClass = 'badge-pending';
                          if($status === 'booked') { $badgeClass = 'badge-booked'; }
                          elseif($status === 'cancel') { $badgeClass = 'badge-cancelled'; }
                          echo 
                          "<tr>
                              <td>" . htmlspecialchars($row['hname'] ?? 'N/A') . "</td>
                              <td>" . htmlspecialchars($row['vname'] ?? 'N/A') . "</td>
                              <td>" . htmlspecialchars($row['date']) . "</td>
                              <td>" . htmlspecialchars($row['time']) . "</td>
                              <td><span class='badge-status $badgeClass'>" . htmlspecialchars(ucfirst($row['status'])) . "</span></td>
                          </tr>";
                      }
                    }
                    else
                    {
                      echo "<tr><td colspan='5' class='text-muted'>No appointments found.</td></tr>";
                    }
                ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
</body>
</html>

