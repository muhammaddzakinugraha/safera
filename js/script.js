// Ambil elemen navbar
const navbar = document.querySelector(".navbar");

// Tambahkan event listener untuk mendeteksi scroll
window.addEventListener("scroll", function () {
  // Cek apakah halaman sudah di-scroll lebih dari 50px
  if (window.scrollY > 50) {
    navbar.classList.add("scrolled"); // Menambahkan kelas 'scrolled' saat scroll
    navbar.classList.add("navbar-small"); // Menambahkan kelas 'navbar-small'
  } else {
    navbar.classList.remove("scrolled"); // Menghapus kelas 'scrolled' saat scroll ke atas
    navbar.classList.remove("navbar-small"); // Menghapus kelas 'navbar-small'
  }
});

// sweat alert login
document.addEventListener("DOMContentLoaded", () => {
  var btn_beli_tiket = document.querySelector(".btn-beli-tiket");

  btn_beli_tiket
    ? btn_beli_tiket.addEventListener("click", () => {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Anda harus login terlebih dahulu!",
          footer: '<a href="login.php">Login</a>',
        });
      })
    : null;
});

// logout pada navbar anjay
document.addEventListener("DOMContentLoaded", () => {
  // Pilih elemen tombol dengan class .btn dan .btn-logout
  const btn = document.querySelector(".btn.btn-logout");

  // Pastikan tombol tidak null sebelum menambahkan event listener
  if (btn) {
    btn.addEventListener("click", (event) => {
      // Mencegah reload langsung saat tombol diklik
      event.preventDefault();

      // Menampilkan notifikasi menggunakan SweetAlert2
      Swal.fire({
        title: "Logout Berhasil",
        text: "Anda telah logout.",
        icon: "success",
      }).then(() => {
        // Arahkan ke halaman index.html setelah notifikasi
        window.location.href = "logout.php";
      });
    });
  }
});

// logout pada navbar anjay
// media partner
document.addEventListener("DOMContentLoaded", () => {
  const marqueeContainer = document.getElementById("marqueeContainer");
  const sponsorTrack = document.getElementById("sponsorTrack");

  // Duplikasi konten
  const clonedTrack = sponsorTrack.cloneNode(true);
  marqueeContainer.appendChild(clonedTrack);

  let position = 0;
  let speed = 1; // Kecepatan geser (piksel per frame)
  const trackWidth = sponsorTrack.offsetWidth;

  function animate() {
    // Geser posisi
    position -= speed;

    // Reset posisi jika sudah melewati lebar track
    if (Math.abs(position) >= trackWidth) {
      position = 0;
    }

    // Terapkan transformasi
    marqueeContainer.style.transform = `translateX(${position}px)`;

    // Lanjutkan animasi
    requestAnimationFrame(animate);
  }

  // Mulai animasi
  animate();

  // Kontrol kecepatan saat hover
  marqueeContainer.addEventListener("mouseenter", () => {
    speed = 0;
  });

  marqueeContainer.addEventListener("mouseleave", () => {
    speed = 1;
  });
});
// media partner
