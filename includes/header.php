<header class="header_section">
  <div class="container">
    <nav class="navbar navbar-expand-lg custom_nav-container">
      <a class="navbar-brand" href="index.php">
        <span>Vaccination System</span>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class=""> </span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#hospitals">Hospitals</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="appointment.php">Appointments</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="covid-test.php">Covid Test</a>
          </li>
          <?php
              if(isset($_SESSION['patient_session']))
              {
                $pname = isset($_SESSION['patient_name']) ? htmlspecialchars($_SESSION['patient_name']) : 'Profile';
                echo 
                "<li class='nav-item'>
                  <a class='nav-link' href='profile.php'>$pname</a>
                </li>
                <li class='nav-item'>
                  <a class='nav-link' href='logout.php' title='Logout'><i class='fa fa-sign-out' style='font-size: 20px;'></i></a>
                </li>";
              }
              else
              {
                echo 
                '<li class="nav-item">
                  <a class="nav-link" href="login.php" title="Login"><i class="fa fa-user" style="font-size: 20px;"></i></a>
                </li>';
              }
          ?>
        </ul>
      </div>
    </nav>
  </div>
</header>

