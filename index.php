<?php 

include_once("components/connect.php"); 

if(isset($_COOKIE['user_id'])) {
  $user_id = $_COOKIE['user_id'];
} else {
  $user_id = create_user_id($conn);
  setcookie('user_id', $user_id, time() + 60*60*24*30, '/'); // ✅ Keep this line!
  header('location:index.php');
  exit;
}

if(isset($_POST['check_in'])) {
  $check_in = $_POST['check_in'];
  $check_in = htmlspecialchars($check_in);

  $total_rooms = 0;

  $check_bookings = $conn -> prepare("SELECT * FROM `bookings` WHERE check_in = ?");
  $check_bookings -> execute([$check_in]);

  while($fetch_bookings = $check_bookings -> fetch(PDO::FETCH_ASSOC)) {
    $total_rooms += $fetch_bookings['rooms'];
  }

  // if the hotel has total 30 rooms
  if($total_rooms >= 30) {
    $warning_msg[] = "rooms are not available";
  } else {
    $success_msg[] = "rooms are available";
  }
}

if(isset($_POST['book'])) {
  $booking_id = create_booking_id($conn);
  $user_id = create_user_id($conn); 
  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $number = htmlspecialchars($_POST['number']);
  $rooms = htmlspecialchars($_POST['rooms']);
  $check_in = htmlspecialchars($_POST['check_in']);
  $check_out = htmlspecialchars($_POST['check_out']);
  $adults = htmlspecialchars($_POST['adults']);
  $children = htmlspecialchars($_POST['children']);

  // Check for ANY existing booking with same email OR phone number
  $check_duplicate = $conn->prepare("SELECT * FROM `bookings` WHERE email = ? OR number = ?");
  $check_duplicate->execute([$email, $number]);
  
  if($check_duplicate->rowCount() > 0) {
    $warning_msg[] = "A booking with this email or phone number already exists!";
  } else {
    $total_rooms = 0;

    $check_bookings = $conn -> prepare("SELECT * FROM `bookings` WHERE check_in = ?");
    $check_bookings -> execute([$check_in]);

    while($fetch_bookings = $check_bookings -> fetch(PDO::FETCH_ASSOC)) {
      $total_rooms += $fetch_bookings['rooms'];
    }

    if($total_rooms + $rooms > 30) {
      $warning_msg[] = "Only " . (30 - $total_rooms) . " rooms available for this date!";
    } else {
      $book_room = $conn -> prepare("INSERT INTO `bookings`(booking_id, user_id, name, email, number, rooms, check_in, check_out, adults, children) VALUES(?,?,?,?,?,?,?,?,?,?)");
      
      try {
        $book_room -> execute([$booking_id, $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $children]);
        $success_msg[] = "room booked successfully!";
        setcookie('user_email', $email, time() + 60*60*24*30, '/');
      } catch (PDOException $e) {
        $warning_msg[] = "Booking failed. Please try again.";
      }
    }
  }
}

if(isset($_POST['send'])) {
  $id = create_user_id($conn);
  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $number = htmlspecialchars($_POST['number']);
  
  // FIX: Check if 'message' exists in $_POST before using it
  $message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';

  $verify_message = $conn -> prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
  $verify_message -> execute([$name, $email, $number, $message]);
  
  if($verify_message -> rowCount() > 0) {
      $warning_msg[] = "message sent already!";
  } else {
      $insert_message = $conn -> prepare("INSERT INTO `messages`(id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message -> execute([$id, $name, $email, $number, $message]);
      $success_msg[] = "message send successfully!";
  }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Responsive Hotel Booking System</title>
    <link rel="icon" href="img/MyLogo.webp" type="image/webp" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
  </head>
  <body>

    <?php include("components/user_header.php"); ?>

    <!-- home section starts -->
    <section class="home" id="home">
      <div class="swiper home-slider">
        <div class="swiper-wrapper">
          <div class="box swiper-slide">
            <img src="img/home-img-1.webp" alt="" />
            <div class="flex">
              <h3>Luxurious Rooms</h3>
              <a href="#availability" class="btn">Check Availability</a>
            </div>
          </div>

          <div class="box swiper-slide">
            <img src="img/home-img-2.webp" alt="" />
            <div class="flex">
              <h3>Foods and Drinks</h3>
              <a href="#reservation" class="btn">Make a Reservation</a>
            </div>
          </div>

          <div class="box swiper-slide">
            <img src="img/home-img-3.webp" alt="" />
            <div class="flex">
              <h3>Luxurious Halls</h3>
              <a href="#contact" class="btn">Contact Us</a>
            </div>
          </div>
        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>
    </section>
    <!-- home section ends -->

    <!-- availability section starts -->
    <section class="availability" id="availability">
      <form action="" method="post">
        <div class="flex">
          <div class="box">
            <p>check in <span>*</span></p>
            <input type="date" name="check_in" class="input" required />
          </div>
          <div class="box">
            <p>check out <span>*</span></p>
            <input type="date" name="check_out" class="input" required />
          </div>
          <div class="box">
            <p>adults <span>*</span></p>
            <select name="adults" class="input" required>
              <option value="1">1 adult</option>
              <option value="2">2 adults</option>
              <option value="3">3 adults</option>
              <option value="4">4 adults</option>
              <option value="5">5 adults</option>
              <option value="6">6 adults</option>
            </select>
          </div>
          <div class="box">
            <p>children <span>*</span></p>
            <select name="children" class="input" required>
              <option value="0">0 child</option>
              <option value="1">1 child</option>
              <option value="2">2 children</option>
              <option value="3">3 children</option>
              <option value="4">4 children</option>
              <option value="5">5 children</option>
              <option value="6">6 children</option>
            </select>
          </div>
          <div class="box">
            <p>rooms <span>*</span></p>
            <select name="rooms" class="input" required>
              <option value="1">1 room</option>
              <option value="2">2 rooms</option>
              <option value="3">3 rooms</option>
              <option value="4">4 rooms</option>
              <option value="5">5 rooms</option>
              <option value="6">6 rooms</option>
            </select>
          </div>
        </div>
        <input type="submit" value="check availability" name="check_in" class="btn" />
      </form>
    </section>

    <!-- availability section ends -->

    <!-- about section starts -->
    <section class="about" id="about">
      <div class="row">
        <div class="image">
          <img src="img/about-img-1.webp" alt="" />
        </div>
        <div class="content">
          <h3>best staff</h3>
          <p>
            Lorem ipsum dolor, sit amet consectetur adipisicing elit.
            Exercitationem, quod eum. Eligendi nisi eaque laborum laudantium,
            commodi sit voluptatem animi quasi reiciendis esse tempore et
            blanditiis. Assumenda maiores numquam expedita?
          </p>
          <a href="#reservation" class="btn">make a reservation</a>
        </div>
      </div>

      <div class="row reverse">
        <div class="image">
          <img src="img/about-img-2.webp" alt="" />
        </div>
        <div class="content">
          <h3>best foods</h3>
          <p>
            Lorem ipsum dolor, sit amet consectetur adipisicing elit.
            Exercitationem, quod eum. Eligendi nisi eaque laborum laudantium,
            commodi sit voluptatem animi quasi reiciendis esse tempore et
            blanditiis. Assumenda maiores numquam expedita?
          </p>
          <a href="#contact" class="btn">contact us</a>
        </div>
      </div>

      <div class="row">
        <div class="image">
          <img src="img/about-img-3.webp" alt="" />
        </div>
        <div class="content">
          <h3>swimming pool</h3>
          <p>
            Lorem ipsum dolor, sit amet consectetur adipisicing elit.
            Exercitationem, quod eum. Eligendi nisi eaque laborum laudantium,
            commodi sit voluptatem animi quasi reiciendis esse tempore et
            blanditiis. Assumenda maiores numquam expedita?
          </p>
          <a href="#availability" class="btn">check availability</a>
        </div>
      </div>
    </section>
    <!-- about section ends -->

    <!-- services section starts -->
    <section class="services">
      <div class="box-container">
        <div class="box">
          <img src="img/icon1.svg" alt="" />
          <h3>food & drinks</h3>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor,
            minima.
          </p>
        </div>
        <div class="box">
          <img src="img/icon2.svg" alt="" />
          <h3 style="margin: 2px 0">outdoor dining</h3>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor,
            minima.
          </p>
        </div>
        <div class="box">
          <img src="img/icon3.svg" alt="" />
          <h3>beach view</h3>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor,
            minima.
          </p>
        </div>
        <div class="box">
          <img src="img/icon4.svg" alt="" />
          <h3>decorations</h3>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor,
            minima.
          </p>
        </div>
        <div class="box">
          <img src="img/icon5.svg" alt="" />
          <h3>swimming pool</h3>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor,
            minima.
          </p>
        </div>
        <div class="box">
          <img src="img/icon6.svg" alt="" />
          <h3>resort beach</h3>
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolor,
            minima.
          </p>
        </div>
      </div>
    </section>
    <!-- services section ends -->

    <!-- reservation section starts -->
    <section class="reservation" id="reservation">
      <form action="" method="post">
        <h3>make a reservation</h3>
        <div class="flex">
          <div class="box">
            <p>full name<span>*</span></p>
            <input type="text" name="name" class="input" required />
          </div>
          <div class="box">
            <p>email <span>*</span></p>
            <input type="email" name="email" class="input" required />
          </div>
          <div class="box">
            <p>number<span>*</span></p>
            <input type="text" name="number" class="input" pattern="\d{1,11}" maxlength="11" required onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" />
          </div>
          <div class="box">
            <p>check in <span>*</span></p>
            <input type="date" name="check_in" class="input" required />
          </div>
          <div class="box">
            <p>check out <span>*</span></p>
            <input type="date" name="check_out" class="input" required />
          </div>
          <div class="box">
            <p>adults <span>*</span></p>
            <select name="adults" class="input" required>
              <option value="1">1 adult</option>
              <option value="2">2 adults</option>
              <option value="3">3 adults</option>
              <option value="4">4 adults</option>
              <option value="5">5 adults</option>
              <option value="6">6 adults</option>
            </select>
          </div>
          <div class="box">
            <p>children <span>*</span></p>
            <select name="children" class="input" required>
              <option value="0">0 child</option>
              <option value="1">1 child</option>
              <option value="2">2 children</option>
              <option value="3">3 children</option>
              <option value="4">4 children</option>
              <option value="5">5 children</option>
              <option value="6">6 children</option>
            </select>
          </div>
          <div class="box">
            <p>rooms <span>*</span></p>
            <select name="rooms" class="input" required>
              <option value="1">1 room</option>
              <option value="2">2 rooms</option>
              <option value="3">3 rooms</option>
              <option value="4">4 rooms</option>
              <option value="5">5 rooms</option>
              <option value="6">6 rooms</option>
            </select>
          </div>
        </div>
        <input type="submit" value="book now" name="book" class="btn" />
      </form>
    </section>

    <!-- reservation section ends -->

    <!-- gallery section starts -->
    <section class="gallery" id="gallery">
      <div class="swiper gallery-slider">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <img src="img/gallery-img-1.webp" alt="Gallery image 1" />
          </div>
          <div class="swiper-slide">
            <img src="img/gallery-img-2.webp" alt="Gallery image 2" />
          </div>
          <div class="swiper-slide">
            <img src="img/gallery-img-3.webp" alt="Gallery image 3" />
          </div>
          <div class="swiper-slide">
            <img src="img/gallery-img-4.webp" alt="Gallery image 4" />
          </div>
          <div class="swiper-slide">
            <img src="img/gallery-img-5.webp" alt="Gallery image 5" />
          </div>
          <div class="swiper-slide">
            <img src="img/gallery-img-6.webp" alt="Gallery image 6" />
          </div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </section>
    <!-- gallery section ends -->

    <!-- contact section starts -->
    <section class="contact" id="contact">
      <div class="row">
        <form action="" method="post">
          <h3>send us message</h3>
          <input type="text" name="name" required maxlength="50" placeholder="enter your name" class="box" />
          <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="box" />
          <input type="number" name="number" required maxlength="10" min="0" max="9999999999" placeholder="enter your number" class="box" />
          <textarea name="message" class="box" required maxlength="1000" placeholder="enter your message" cols="30" rows="10"></textarea>
          <input type="submit" value="send message" name="send" class="btn" />
        </form>

        <div class="faq">
          <h3 class="title">frequently asked questions</h3>
          <div class="box active">
            <h3>how to cancel</h3>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Fuga quia
              hic perspiciatis placeat eligendi aliquid non impedit ab, in
              aspernatur quis, maxime deleniti, alias itaque quo reiciendis
              asperiores atque expedita?
            </p>
          </div>
          <div class="box">
            <h3>is there any vacancy?</h3>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis
              quasi placeat tenetur doloremque alias provident rem omnis optio
              dolorum eius sapiente delectus minus ut fugit esse soluta sit, eum
              corrupti?
            </p>
          </div>
          <div class="box">
            <h3>what are payment methods?</h3>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis
              quasi placeat tenetur doloremque alias provident rem omnis optio
              dolorum eius sapiente delectus minus ut fugit esse soluta sit, eum
              corrupti?
            </p>
          </div>
          <div class="box">
            <h3>how to claim coupons codes?</h3>
            <p>
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis
              quasi placeat tenetur doloremque alias provident rem omnis optio
              dolorum eius sapiente delectus minus ut fugit esse soluta sit, eum
              corrupti?
            </p>
          </div>
        </div>
      </div>
    </section>
    <!-- contact section ends -->

    <!-- review section starts -->
    <section class="reviews" id="reviews">
      <div class="swiper3 reviews-slider">
        <div class="swiper-wrapper">
          <div class="swiper-slide box">
            <img src="img/pic-1.webp" alt="" />
            <h3>Dante</h3>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Recusandae ab, dolor veritatis, ea provident beatae sit quo
              nostrum aspernatur fugiat rem tempore quibusdam iure animi
              repellendus delectus repellat nemo culpa.
            </p>
          </div>
          <div class="swiper-slide box">
            <img src="img/pic-2.webp" alt="" />
            <h3>Vergil</h3>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Recusandae ab, dolor veritatis, ea provident beatae sit quo
              nostrum aspernatur fugiat rem tempore quibusdam iure animi
              repellendus delectus repellat nemo culpa.
            </p>
          </div>
          <div class="swiper-slide box">
            <img src="img/pic-3.webp" alt="" />
            <h3>Lady</h3>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Recusandae ab, dolor veritatis, ea provident beatae sit quo
              nostrum aspernatur fugiat rem tempore quibusdam iure animi
              repellendus delectus repellat nemo culpa.
            </p>
          </div>
          <div class="swiper-slide box">
            <img src="img/pic-4.webp" alt="" />
            <h3>Nero</h3>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Recusandae ab, dolor veritatis, ea provident beatae sit quo
              nostrum aspernatur fugiat rem tempore quibusdam iure animi
              repellendus delectus repellat nemo culpa.
            </p>
          </div>
          <div class="swiper-slide box">
            <img src="img/pic-5.webp" alt="" />
            <h3>Alpha</h3>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Recusandae ab, dolor veritatis, ea provident beatae sit quo
              nostrum aspernatur fugiat rem tempore quibusdam iure animi
              repellendus delectus repellat nemo culpa.
            </p>
          </div>
          <div class="swiper-slide box">
            <img src="img/pic-6.webp" alt="" />
            <h3>Lucia</h3>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Recusandae ab, dolor veritatis, ea provident beatae sit quo
              nostrum aspernatur fugiat rem tempore quibusdam iure animi
              repellendus delectus repellat nemo culpa.
            </p>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </section>
    <!-- review section ends -->

    <?php include("components/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/script.js" type="text/javascript"></script>

    <?php include("components/message.php"); ?>

  </body>
</html>