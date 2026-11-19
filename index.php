<?php
  require_once __DIR__ . '/includes/db.php';
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  $active_patient = null;
  if(isset($_SESSION['patient_session']))
  {
      $pid = intval($_SESSION['patient_session']);
      $fetch_patient_query = "SELECT * FROM tbl_patient WHERE id = $pid";
      $p_res = mysqli_query($connection, $fetch_patient_query);
      if($p_res && mysqli_num_rows($p_res) > 0)
      {
          $active_patient = mysqli_fetch_assoc($p_res);
      }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="keywords" content="vaccination, healthcare, hospital, covid test" />
  <meta name="description" content="Online Vaccination Management and Healthcare Booking System" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">

  <title>Vaccination System - Preventive Healthcare Portal</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!-- owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- font awesome style -->
  <link href="assets/css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="assets/css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="assets/css/responsive.css" rel="stylesheet" />

</head>
<style>
  .hideContact
  {
    display: none;
  }
</style>
<body>

  <div class="hero_area">

    <div class="hero_bg_box">
      <img src="assets/images/hero-bg.png" alt="">
    </div>

    <!-- header section -->
    <?php
      include("includes/header.php");
    ?>
    <!-- end header section -->

    <!-- slider section -->
    <section class="slider_section">
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-7">
                  <div class="detail-box">
                    <h1>
                      Protecting Communities Through Vaccination
                    </h1>
                    <p>
                      Schedule vaccination doses, find registered hospitals nearby, and track your COVID test reports securely online in one unified healthcare portal.
                    </p>
                    <div class="btn-box">
                      <a href="appointment.php" class="btn1">
                        Book Appointment
                      </a>
                      <a href="covid-test.php" class="btn1">
                        Covid Test
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end slider section -->
  </div>

  <!-- department section -->
  <section class="department_section layout_padding" id="departments">
    <div class="department_container">
      <div class="container">
        <div class="heading_container heading_center">
          <h2>
            Key Services
          </h2>
          <p>
            Comprehensive preventive healthcare services designed for patient safety and convenience.
          </p>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="box ">
              <div class="img-box">
                <img src="assets/images/s1.png" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Vaccine Scheduling
                </h5>
                <p>
                  Book first and second dose appointments across verified medical centers at your preferred timing.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="box ">
              <div class="img-box">
                <img src="assets/images/s2.png" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Covid Diagnostics
                </h5>
                <p>
                  Request diagnostic tests and access verified digital reports directly from your dashboard.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="box ">
              <div class="img-box">
                <img src="assets/images/s3.png" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Hospital Network
                </h5>
                <p>
                  Locate accredited hospitals, view available vaccine supplies, and track appointment statuses.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="box ">
              <div class="img-box">
                <img src="assets/images/s4.png" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  Medical Records
                </h5>
                <p>
                  Maintain accurate vaccination histories and print test certificates whenever needed.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end department section -->

  <!-- about section -->
  <section class="about_section layout_margin-bottom" id="about">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="img-box">
            <img src="assets/images/about-img.jpg" alt="About Healthcare">
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                About <span>Us</span>
              </h2>
            </div>
            <p>
              The Vaccination Management System provides patients and healthcare facilities with a streamlined coordination platform. Our goal is to make immunizations accessible, trackable, and safe for every citizen through direct digital records.
            </p>
            <a href="appointment.php">
              Get Started
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end about section -->

  <!-- hospital section -->
  <section class="doctor_section layout_padding" id="hospitals">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>
          Registered Hospitals
        </h2>
        <p class="col-md-10 mx-auto px-0">
          Partner healthcare centers providing verified immunization and testing facilities.
        </p>
      </div>
      <div class="row">
        <?php 
          $query = "SELECT tbl_hospital.*, tbl_city.name as 'cname' FROM tbl_hospital LEFT JOIN tbl_city ON tbl_hospital.cid=tbl_city.id WHERE tbl_hospital.status='activate'";
          $result = mysqli_query($connection,$query);
          if($result && mysqli_num_rows($result) > 0)
          {
            foreach($result as $row)
            {
              $img_path = "assets/images/s1.png";
              if(!empty($row['image']))
              {
                $cleanName = basename($row['image']);
                if(file_exists(__DIR__ . "/uploads/hospitals/" . $cleanName)) {
                  $img_path = "uploads/hospitals/" . $cleanName;
                } elseif(file_exists(__DIR__ . "/" . $row['image'])) {
                  $img_path = $row['image'];
                } elseif(file_exists(__DIR__ . "/admin/" . $row['image'])) {
                  $img_path = "admin/" . $row['image'];
                }
              }
              $city_name = !empty($row['cname']) ? $row['cname'] : 'City';
              echo '<div class="col-sm-6 col-lg-4 mx-auto">
              <div class="box">
                <div class="img-box">
                  <img src="'.htmlspecialchars($img_path).'" alt="'.htmlspecialchars($row['name']).'" style="height:260px; width:100%; object-fit:cover;">
                </div>
                <div class="detail-box">
                  <h5>'.htmlspecialchars($row['name']).'</h5>
                  <h6>'.htmlspecialchars($city_name).'</h6>
                  <p class="text-muted" style="font-size:14px; margin-top:5px;">Contact: '.htmlspecialchars($row['contact']).'</p>
                </div>
              </div>
            </div>';
            }
          }
        ?>
      </div>
    </div>
  </section><br><br>
  <!-- end hospital section -->

  <!-- contact section -->
  <section class='contact_section layout_padding <?php if(!isset($_SESSION['patient_session'])){ echo "hideContact";} ?>' id="contact">
    <div class="container">
      <div class="heading_container">
        <h2>
          Feedback & Inquiries
        </h2>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form_container contact-form">
            <form method="post">
              <div class="form-row">
                <div class="col-lg-6">
                  <div>
                    <input type="hidden" value="<?php echo $active_patient ? $active_patient['id'] : '';?>" name="pid">
                    <input type="text" placeholder="Your Name" value="<?php echo $active_patient ? htmlspecialchars($active_patient['name']) : '';?>" readonly/>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div>
                    <input type="text" placeholder="Phone Number" value="<?php echo $active_patient ? htmlspecialchars($active_patient['contact']) : '';?>" readonly/>
                  </div>
                </div>
              </div>
              <div>
                <input type="email" placeholder="Email" value="<?php echo $active_patient ? htmlspecialchars($active_patient['email']) : '';?>" readonly/>
              </div>
              <div>
                <input type="text" class="message-box" placeholder="Your Message / Feedback" name="message" required/>
              </div>
              <div class="btn_box">
                <button type="submit" name="btnsend">Send Feedback</button>
              </div>
            </form>
            <?php
                if(isset($_POST['btnsend']) && isset($_SESSION['patient_session']))
                {
                  $pid = intval($_SESSION['patient_session']);
                  $message = mysqli_real_escape_string($connection, trim($_POST['message']));
                  if(!empty($message))
                  {
                    $result = mysqli_query($connection,"INSERT INTO tbl_feedback(p_id,message) VALUES($pid,'$message')");
                    if($result)
                    {
                      echo "<script>alert('Feedback Sent Successfully'); window.location.href='index.php';</script>";
                    }
                  }
                }
            ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="map_container">
            <div class="map">
              <div id="googleMap"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end contact section -->

  <!-- testimonial section -->
  <section class="client_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center ">
        <h2>
          Patient Testimonials
        </h2>
      </div>
      <div id="carouselExample2Controls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <?php 
            $result = mysqli_query($connection,"SELECT tbl_feedback.*, tbl_patient.name as 'pname', tbl_patient.image FROM tbl_feedback INNER JOIN tbl_patient ON tbl_feedback.p_id = tbl_patient.id WHERE tbl_feedback.status='show'");

            $firstFeedback = true;

            if($result && mysqli_num_rows($result) > 0)
            {
              foreach($result as $feedback)
              {
                $activeClass = $firstFeedback ? 'active' : '';
                $fbImg = "uploads/patients/user.jpg";
                if(!empty($feedback['image'])) {
                  $cleanName = basename($feedback['image']);
                  if(file_exists(__DIR__ . "/uploads/patients/" . $cleanName)) {
                    $fbImg = "uploads/patients/" . $cleanName;
                  } elseif(file_exists(__DIR__ . "/" . $feedback['image'])) {
                    $fbImg = $feedback['image'];
                  }
                }
                echo '<div class="carousel-item '.$activeClass.'">
                <div class="row">
                  <div class="col-md-11 col-lg-10 mx-auto">
                    <div class="box">
                      <div class="img-box">
                        <img src="'.htmlspecialchars($fbImg).'" alt="'.htmlspecialchars($feedback['pname']).'" style="width:120px; height:120px; border-radius:50%; object-fit:cover;" />
                      </div>
                      <div class="detail-box">
                        <div class="name">
                          <h6>'.htmlspecialchars($feedback['pname']).'</h6>
                        </div>
                        <p>'.htmlspecialchars($feedback['message']).'</p>
                        <i class="fa fa-quote-left" aria-hidden="true"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>';
                $firstFeedback = false;
              }
            }
          ?>
        </div>
        <div class="carousel_btn-container">
          <a class="carousel-control-prev" href="#carouselExample2Controls" role="button" data-slide="prev">
            <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExample2Controls" role="button" data-slide="next">
            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
  </section>
  <!-- end testimonial section -->

  <!-- footer section -->
  <footer class="footer_section">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-lg-3 footer_col">
          <div class="footer_contact">
            <h4>
              Reach Us
            </h4>
            <div class="contact_link_box">
              <a href="#">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>
                  National Healthcare Center
                </span>
              </a>
              <a href="tel:+011234567890">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                  Call +01 1234567890
                </span>
              </a>
              <a href="mailto:info@vaccinationsystem.org">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                  info@vaccinationsystem.org
                </span>
              </a>
            </div>
          </div>
          <div class="footer_social">
            <a href="#">
              <i class="fa fa-facebook" aria-hidden="true"></i>
            </a>
            <a href="#">
              <i class="fa fa-twitter" aria-hidden="true"></i>
            </a>
            <a href="#">
              <i class="fa fa-linkedin" aria-hidden="true"></i>
            </a>
            <a href="#">
              <i class="fa fa-instagram" aria-hidden="true"></i>
            </a>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 footer_col">
          <div class="footer_detail">
            <h4>
              About
            </h4>
            <p>
              Dedicated to delivering reliable vaccination services, verified medical appointments, and real-time COVID diagnostic records.
            </p>
          </div>
        </div>
        <div class="col-md-6 col-lg-2 mx-auto footer_col">
          <div class="footer_link_box">
            <h4>
              Links
            </h4>
            <div class="footer_links">
              <a class="active" href="index.php">
                Home
              </a>
              <a class="" href="index.php#about">
                About
              </a>
              <a class="" href="index.php#hospitals">
                Hospitals
              </a>
              <a class="" href="appointment.php">
                Appointments
              </a>
              <a class="" href="covid-test.php">
                Covid Test
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 footer_col ">
          <h4>
            Portals
          </h4>
          <div class="footer_links">
            <a href="hospital/login.php">Hospital Staff Portal</a>
            <a href="admin/login.php">Admin Control Panel</a>
            <a href="user-register.php">Patient Registration</a>
          </div>
        </div>
      </div>
      <div class="footer-info">
        <p>
          &copy; <span id="displayYear"><?php echo date('Y'); ?></span> Vaccination Management System. All Rights Reserved.
        </p>
      </div>
    </div>
  </footer>
  <!-- footer section -->

  <!-- jQuery -->
  <script type="text/javascript" src="assets/js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script type="text/javascript" src="assets/js/bootstrap.js"></script>
  <!-- owl slider -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- custom js -->
  <script type="text/javascript" src="assets/js/custom.js"></script>

</body>

</html>
