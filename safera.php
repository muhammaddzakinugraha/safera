<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'user') {
    header('Location: login.php'); // Redirect jika bukan user biasa
    exit();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login.php');
    exit;
}

// Ambil data user dari session
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'guest@example.com';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Safera</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/mainweb.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Parkinsans:wght@300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/logo.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">SAFERA</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="#">HOME</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#about">TENTANG SAFERA</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#jadwal">JADWAL</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#gallery">GALLERY</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#tiket">BELI TIKET</a>
            </li>
            <li class="user-menu">
            <a href="#" class="user-name"><?php echo htmlspecialchars($user_name); ?> <i class="fa fa-chevron-down"></i></a>
            <ul class="dropdown">
                <li><a href="users/profile.php">Profile</a></li>
                <li> <a
                class="nav-link"
                href="https://wa.me/6283890344214?text=Hallo saya ingin menanyakan tentang informasi safera"
                >Kontak Kami</a></li>
                <li><a class="nav-link" href="invoice/history_order.php">Riwayat Pembelian</a></li>
                <li><a class="btn btn-logout" href="logout.php">LogOut</a></li>
            </ul>
        </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Navbar End -->

    <!-- Hero Section with Video Background -->
    <header
      class="hero-section d-flex align-items-center justify-content-center text-center"
    >
      <video autoplay muted loop class="video-background">
        <source src="./assets/try.mp4" type="video/mp4" />
        Your browser does not support the video tag.
      </video>
      <div class="overlay"></div>
      <div class="container">
        <h1></h1>
        <p class="lead"></p>
      </div>
    </header>
    <!-- Hero Section End -->

    <!-- ucapan selamat datang -->
    <!-- <div class="container">
    <h1>Selamat Datang di Safera</h1>
    <p>Halo,<?php echo htmlspecialchars($user_name); ?>! Anda login sebagai <?php echo htmlspecialchars($user_email); ?>.</p>
    </div> -->
    <!-- ucapan selamat datang -->

    <!-- About Section -->
    <section class="content-section py-5" id="about">
      <div class="container">
        <h2 class="h-about">TENTANG SAFERA</h2>
        <p class="p-about">
          Safera "SMANJAS FESTIVAL RAYA" merupakan event besar atau program
          kerja tahunan MOSIS SMANJAS sebagai bentuk puncak apresiasi
          siswa/siswi dalam menampilkan bakat minat yang mereka miliki,
          membangun kepercayaan diri, dan mengembangkan ide-ide kreatif yang
          menghasilkan keuntungan.
          <br /><br />
          Festival kali ini akan jauh lebih seru dan istimewa dari event-event
          sebelumnya yang pernah diselenggarakan oleh SMA Negeri 1 Jatisari.
          <br /><br />
          Nikmati Keseruan di Event Spesial Kami!

          Bergabunglah bersama kami di event yang penuh keseruan dan kenangan tak terlupakan! 🌟
          Kami telah menyiapkan berbagai aktivitas menarik untuk Anda, termasuk:
          <br /><br />
          📸 Photo Booth Instagramable
          Abadikan momen spesial Anda dengan backdrop keren dan dekorasi yang memukau. Cocok untuk menghiasi feed media sosial Anda!
          <br /><br />
          🍔 17 Tenant Makanan & Minuman
          Nikmati berbagai pilihan makanan dan minuman dari tenant terbaik. Mulai dari makanan tradisional hingga hidangan kekinian, semuanya ada di sini!
          <br /><br />
          🎪 Beragam Booth Seru
          Temukan berbagai booth unik yang menawarkan pengalaman berbeda, mulai dari kuliner, hiburan, hingga produk kreatif lokal.
          <br /><br />
          ✨ Jangan lewatkan kesempatan untuk menikmati suasana event yang hangat dan penuh keseruan ini bersama teman dan keluarga. Catat tanggalnya, dan pastikan Anda hadir!
        </p>
      </div>
      <br />
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <p>
              <br />Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Nemo, distinctio! <br /><br />Lorem ipsum dolor sit amet
              consectetur adipisicing elit. Deleniti quasi rerum porro ratione
              reiciendis iure adipisci autem, voluptate impedit veritatis
              accusantium aliquid laboriosam dolor unde nam eum nulla eaque sunt
              tempore, expedita cupiditate! Ratione tempore repellendus nemo
              enim incidunt? Ipsam perferendis voluptas fugiat possimus rerum!
              Dignissimos voluptas accusantium vero doloremque ut? Dicta
              cupiditate quisquam iure reiciendis eum mollitia! Id pariatur esse
              vel beatae quas facilis deleniti eius incidunt aperiam numquam
              inventore voluptas ad voluptatum suscipit consequatur, ex nisi
              perspiciatis officia. Vitae reiciendis voluptatibus sed dolore
              officiis consectetur! Excepturi saepe laborum debitis non quod
              mollitia doloribus nemo totam ipsum, aliquid dolorum.
            </p>
          </div>
          <div class="col-md-6">
            <img
              src="./assets/g11.jpeg"
              alt="About Us"
              class="img-fluid about-image"
            />
          </div>
          <p>
            <br />
            Lorem ipsum dolor sit amet consectetur adipisicing elit.
            Exercitationem recusandae impedit cupiditate explicabo repudiandae
            excepturi quasi deleniti sapiente officiis suscipit velit officia,
            quas sequi, expedita neque. Ad ducimus autem nemo!
          </p>
        </div>
      </div>
    </section>

    <!-- Schedule Section -->
    <section class="schedule py-5" id="jadwal">
      <div class="container">
        <h2 class="jadwal-event">JADWAL EVENT</h2>
        <div class="row">
          <div class="col-md-4">
            <div class="card">
              <img
                src="./assets/d1.jpg"
                class="card-img-top"
                alt="Rock Legends"
              />
              <div class="card-body">
                <h5 class="card-title">Hari 1 - Pembukaan Safera</h5>
                <p class="card-text">
                  Acara pembukaan Safera dan menampilkan seni tari & dance
                  modern.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <img
                src="./assets/d2.jpg"
                class="card-img-top"
                alt="Pop Paradise"
              />
              <div class="card-body">
                <h5 class="card-title">
                  Hari 2 - Fashion Show & Drama Musikal
                </h5>
                <p class="card-text">
                  Menampilkan fashion show & drama musikal dari siswa/siswi SMA
                  SMANJAS.
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <img
                src="./assets/d3.jpeg"
                class="card-img-top"
                alt="Electronic Vibes"
              />
              <div class="card-body">
                <h5 class="card-title">Hari 3 - Penampilan Guest Star</h5>
                <p class="card-text">
                  Saksikan penampilan dari Pia Felline EX. Penyanyi Utopia.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="gallery-section py-5">
      <div class="container">
        <h2 class="h-gallery">GALLERY</h2>
        <div class="row">
          <!-- Gallery Images -->
          <!-- Use Bootstrap grid system -->
          <div class="col-6 col-md-4 col-lg-3 mb-4">
            <img
              src="assets/g1.JPG"
              class="img-fluid about-image"
              alt="Foto 1"
            />
          </div>
          <div class="col-6 col-md-4 col-lg-3 mb-4">
            <img
              src="assets/g2.JPG"
              class="img-fluid about-image"
              alt="Foto 1"
            />
          </div>
          <div class="col-6 col-md-4 col-lg-3 mb-4">
            <img
              src="assets/g3.JPG"
              class="img-fluid about-image"
              alt="Foto 1"
            />
          </div>
          <div class="col-6 col-md-4 col-lg-3 mb-4">
            <img
              src="assets/g4.JPG"
              class="img-fluid about-image"
              alt="Foto 1"
            />
          </div>
          <div class="col-6 col-md-4 col-lg-3 mb-4">
            <img
              src="assets/g5.JPG"
              class="img-fluid about-image"
              alt="Foto 1"
            />
          </div>
          <div class="col-6 col-md-4 col-lg-3 mb-4">
            <img
              src="assets/g6.JPG"
              class="img-fluid about-image"
              alt="Foto 1"
            />
          </div>
          <div class="col-6 col-md-4 col-lg-3 mb-4">
            <img
              src="assets/g7.JPG"
              class="img-fluid about-image"
              alt="Foto 1"
            />
          </div>
          <div class="col-6 col-md-4 col-lg-3 mb-4">
            <img
              src="assets/g8.JPG"
              class="img-fluid about-image"
              alt="Foto 1"
            />
          </div>
          <div class="col-6 col-md-4 col-lg-3 mb-4">
            <img
              src="assets/g9.jpeg"
              class="img-fluid about-image"
              alt="Foto 1"
            />
          </div>
          <div class="col-6 col-md-4 col-lg-3 mb-4">
            <img
              src="assets/g10.jpeg"
              class="img-fluid about-image"
              alt="Foto 1"
            />
          </div>
          <div class="col-6 col-md-4 col-lg-3 mb-4">
            <img
              src="assets/g11.jpeg"
              class="img-fluid about-image"
              alt="Foto 1"
            />
          </div>
          <div class="col-6 col-md-4 col-lg-3 mb-4">
            <img
              src="assets/g12.jpeg"
              class="img-fluid about-image"
              alt="Foto 1"
            />
          </div>
          <!-- Repeat for other images... -->
        </div>
      </div>
    </section>

    <!-- Tickets Section -->
    <section class="tickets py-5" id="tiket">
      <div class="container text-center">
        <h2 class="h-tiket">Dapatkan Tiket Anda</h2>
        <p class="lead">Pilih tiket Anda hari ini!</p>
        <a href="tiket.php" class="btn btn-primary btn-lg">Beli Tiket</a>
      </div>
    </section>

    <section class="our-sponsor py-5 text-center" id="sponsor">
      <h5 class="mb-3 sponsor-heading">MEDIA PARTNER</h5>
      <div class="sponsor-marquee">
        <div class="marquee-container" id="marqueeContainer">
          <div class="sponsor-track" id="sponsorTrack">
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor1" target="_blank">
                <img src="sponsor/1.png" alt="Sponsor 1" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor2" target="_blank">
                <img src="sponsor/2.png" alt="Sponsor 2" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor3" target="_blank">
                <img src="sponsor/3.png" alt="Sponsor 3" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor4" target="_blank">
                <img src="sponsor/4.png" alt="Sponsor 4" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor5" target="_blank">
                <img src="sponsor/5.png" alt="Sponsor 5" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor5" target="_blank">
                <img src="sponsor/6.png" alt="Sponsor 6" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor5" target="_blank">
                <img src="sponsor/7.png" alt="Sponsor 7" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor5" target="_blank">
                <img src="sponsor/8.png" alt="Sponsor 8" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor5" target="_blank">
                <img src="sponsor/9.png" alt="Sponsor 9" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor5" target="_blank">
                <img src="sponsor/10.png" alt="Sponsor 10" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor5" target="_blank">
                <img src="sponsor/11.png" alt="Sponsor 11" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor5" target="_blank">
                <img src="sponsor/12.png" alt="Sponsor 12" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor5" target="_blank">
                <img src="sponsor/13.png" alt="Sponsor 13" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor5" target="_blank">
                <img src="sponsor/14.png" alt="Sponsor 14" />
              </a>
            </div>
            <div class="sponsor-item">
              <a href="https://www.instagram.com/sponsor5" target="_blank">
                <img src="sponsor/15.png" alt="Sponsor 15" />
              </a>
            </div>
          </div>
        </div>
      </div>
      <p class="text-center mt-3">
        Support media partner by following them on Instagram!
      </p>
    </section>

    <!-- Footer -->
    <footer class="footer bg-dark text-light text-center py-3">
    <div class="container">
        <h3>Hubungi Kami</h3>
        <p>
            <i class="fa fa-map-marker" aria-hidden="true"></i>
            Jl. Raya Jatisari, Jatisari, Kec. Jatisari, Karawang, Jawa Barat 41374
        </p>
        <p>
            <i class="fa fa-phone" aria-hidden="true"></i>
            (0267) 403000
        </p>
        <p>
            <i class="fa fa-envelope" aria-hidden="true"></i>
            saferaevent@gmail.com
        </p>
        <div class="social-media">
            <a href="#"><i class="fab fa-facebook" aria-hidden="true"></i></a>
            <a href="#"><i class="fab fa-twitter" aria-hidden="true"></i></a>
            <a href="#"><i class="fab fa-instagram" aria-hidden="true"></i></a>
            <a href="#"><i class="fab fa-linkedin" aria-hidden="true"></i></a>
        </div>
        <p>&copy; 2024 safera. All Rights Reserved.</p>
    </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </body>
</html>
