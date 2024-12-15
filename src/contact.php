<?php
// Load PHPMailer
require 'PHPMailer/PHPMailer/src/PHPMailer.php'; 
require 'PHPMailer/PHPMailer/src/Exception.php';
require 'PHPMailer/PHPMailer/src/SMTP.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate inputs
  $name = htmlspecialchars($_POST['name']);
  $email = 'kemoygallimore@gmail.com';
  $phone = htmlspecialchars($_POST['phone']);
  $message = htmlspecialchars($_POST['message']);

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address");
  }

  try {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp-mail.outlook.com'; // Outlook SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'kemoy_gallimore@hotmail.com';
    $mail->Password = 'Gallimore2!';
    $mail->Port = 587;
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;

    // Function to send email
    function sendEmail($mail, $to, $subject, $body) {
      $mail->clearAddresses();
      $mail->addAddress($to);
      $mail->Subject = $subject;
      $mail->Body = $body;
      $mail->send();
    }

    // Send email to admin

    // Send confirmation email to user
    $userBody = "Thank you for your call back request, $name. We will contact you soon.";
    sendEmail($mail, $email, 'Thank you for your Request', $userBody);

    echo 'Emails sent successfully!';
  } catch (Exception $e) {
    // Log error and show generic message
    error_log("Mailer Error: {$e->getMessage()}");
    echo 'There was an error sending your request. Please try again later. '.$e->getMessage();
  }
}

include('_front-header.php');
?>


<body class="sub_page">
  <div class="hero_area">
    <!-- header section starts -->
    <?php include('_front-nav.php')?>
  </div>
    <!-- end header section -->

    <!-- contact section -->
    <section class="contact_section layout_padding">
    <div class="container ">
      <div class="heading_container ">
        <h2 class="">
          Request
          <span>
            A call Back
          </span>
        </h2>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-6 ">
          <form action="" method="POST">
            <div>
              <input type="text" name="name" placeholder="Name"  />
            </div>
            <div>
              <input type="email" name="email" placeholder="Email"  />
            </div>
            <div>
              <input type="text" name="phone" placeholder="Phone Number"  />
            </div>
            <div>
              <input type="text" name="message" class="message-box" placeholder="Message"  />
            </div>
            <div class="d-flex  mt-4 ">
              <button type="submit">
                SEND
              </button>
            </div>
          </form>
        </div>
        <div class="col-md-6">
          <!-- map section -->
          <div class="map_section">
            <div id="map" class="w-100 h-100"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end contact section -->

  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>

  <script>
    // Google Map
    function initMap() {
      var map = new google.maps.Map(document.getElementById("map"), {
        zoom: 11,
        center: { lat: 40.645037, lng: -73.880224 }
      });

      var image = "img/maps-and-flags.png";
      var beachMarker = new google.maps.Marker({
        position: { lat: 40.645037, lng: -73.880224 },
        map: map,
        icon: image
      });
    }
  </script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA8eaHt9Dh5H57Zh0xVTqxVdBFCvFMqFjQ&callback=initMap"></script>

  <script>
    function openNav() {
      document.getElementById("myNav").style.width = "100%";
    }

    function closeNav() {
      document.getElementById("myNav").style.width = "0%";
    }
  </script>
</body>

<?php include('_front-footer.php') ?>
