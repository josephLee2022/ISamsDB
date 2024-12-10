<?php
include('_front-header.php')
?>

<body class="sub_page">
  <div class="hero_area">
    <!-- header section strats -->
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
          <form action="#">
            <div>
              <input type="text" placeholder="Name" />
            </div>
            <div>
              <input type="email" placeholder="Email" />
            </div>
            <div>
              <input type="text" placeholder="Pone Number" />
            </div>
            <div>
              <input type="text" class="message-box" placeholder="Message" />
            </div>
            <div class="d-flex  mt-4 ">
              <button>
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

          <!-- end map section -->
        </div>
      </div>
    </div>
  </section>

  <!-- end contact section -->



  <!-- contact section -->


  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.js"></script>

  <script>
    // This example adds a marker to indicate the position of Bondi Beach in Sydney,
    // Australia.
    function initMap() {
      var map = new google.maps.Map(document.getElementById("map"), {
        zoom: 11,
        center: {
          lat: 40.645037,
          lng: -73.880224
        }
      });

      var image = "img/maps-and-flags.png";
      var beachMarker = new google.maps.Marker({
        position: {
          lat: 40.645037,
          lng: -73.880224
        },
        map: map,
        icon: image
      });
    }
  </script>
  <!-- google map js -->

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA8eaHt9Dh5H57Zh0xVTqxVdBFCvFMqFjQ&callback=initMap">
  </script>
  <!-- end google map js -->

  <script>
    function openNav() {
      document.getElementById("myNav").style.width = "100%";
    }

    function closeNav() {
      document.getElementById("myNav").style.width = "0%";
    }
  </script>
</body>



  <!-- end contact section -->

<?php
include('_front-footer.php')
?>

