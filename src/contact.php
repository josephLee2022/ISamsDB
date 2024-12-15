<?php
// Include PHPMailer files
require 'PHPMailer/src/PHPMailer.php'; 
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = 'kemoygallimore@gmail.com';
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Create a new PHPMailer instance
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'profile11170@gmail.com';
        $mail->Password = 'Sidedrum2!';
        $mail->SMTPSecure = 'tls';

        $mail->setFrom('profile11170@gmail.com', 'Your Name');
        $mail->addAddress('kemoygallimore@gmail.com');

        $mail->Subject = 'Test Email';
        $mail->Body = 'This is a test email sent using PHPMailer.';

        if (!$mail->send()) {
            echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
      
        // Server settings
        /*$mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to use
        $mail->SMTPAuth = true;
        $mail->Username = 'profile11170@gmail.com'; // Your Gmail address
        $mail->Password = 'eiht kttf lxkk iqxd!'; // Your Gmail password (consider using App Password for security)
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465; // TCP port to connect to*

        // Recipients
        $mail->setFrom('profile11170@gmail.com', 'Your Name');
        $mail->addAddress('kemoygallimore@gmail.com'); // Your email
        //$mail->addAddress($email); // Recipient's email (from the form)

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Call Back Request';
        $mail->Body    = "You received a request for a callback from:<br><strong>Name:</strong> $name<br><strong>Email:</strong> $email<br><strong>Phone:</strong> $phone<br><strong>Message:</strong><br>$message";

        // Send email to you
        $mail->send();

        // Send email to the user who submitted the form
        $mail->clearAddresses();
        $mail->addAddress($email);
        $mail->Subject = 'Thank you for your Request';
        $mail->Body    = "Thank you for your call back request, $name. We will contact you soon.";

        // Send the email to the user
        $mail->send();

        echo 'Message has been sent to both you and the user.';
*/
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$e->getMessage()}";
    }
}

include('_front-header.php')
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
