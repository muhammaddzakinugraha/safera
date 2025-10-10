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
              <a class="nav-link" href="login.php">LOGIN</a>
            </li>
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
              <a class="nav-link" href="#tiket">BELI TIKET</a>
            </li>
            <li class="nav-item">
              <a
                class="nav-link"
                href="https://wa.me/6283890344214?text=Hallo saya ingin menanyakan tentang informasi safera"
                >KONTAK</a
              >
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
    <!-- Tickets Section -->
    <section class="tickets py-5" id="tiket">
      <div class="container text-center">
        <h2>Dapatkan Tiket Anda</h2>
        <p class="lead">Pilih tiket Anda hari ini!</p>
        <a class="btn btn-primary btn-lg btn-beli-tiket">Beli Tiket</a>
      </div>
    </section>

    <!-- Footer -->
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
  </body>
</html>
