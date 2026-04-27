<?php
/**
 * php/login.php
 * Login doğrulama sayfası
 * Ali Ayyıldız - b241210069
 */

// Tanımlı kullanıcı bilgileri
$valid_email    = "ali.ayyildiz2@ogr.sakarya.edu.tr";
$valid_password = "b241210069";

// Sadece POST isteğini kabul et
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.html");
    exit;
}

$email    = trim($_POST["email"]    ?? "");
$password = trim($_POST["password"] ?? "");

// Boşluk kontrolü
if (empty($email) || empty($password)) {
    header("Location: ../index.html?error=empty");
    exit;
}

// Mail format kontrolü (basit PHP regex)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../index.html?error=empty");
    exit;
}

// Kimlik doğrulama
if ($email === $valid_email && $password === $valid_password) {
    // Başarılı giriş sayfası
    ?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Hoşgeldiniz – Ali Ayyıldız</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="../css/style.css"/>
  <style>
    .success-page { min-height:100vh; display:flex; align-items:center; justify-content:center; text-align:center; flex-direction:column; gap:1.5rem; }
    .checkmark { font-size:5rem; animation:pop 0.5s ease; }
    @keyframes pop { from{transform:scale(0)} to{transform:scale(1)} }
  </style>
</head>
<body>
  <div class="success-page">
    <div class="checkmark">✅</div>
    <h1 style="font-size:2rem;">Hoşgeldiniz <span style="color:var(--accent)">b241210069</span></h1>
    <p style="color:var(--muted)">Giriş başarılı! Ana sayfaya yönlendiriliyorsunuz...</p>
    <a href="../about.html" class="btn-gold" style="display:inline-block; padding:0.7rem 2rem; border-radius:50px; background:var(--accent); color:var(--primary); font-weight:600; text-decoration:none;">
      Siteye Git →
    </a>
  </div>
  <script>
    // 3 saniye sonra otomatik yönlendir
    setTimeout(() => { window.location.href = '../about.html'; }, 3000);
  </script>
</body>
</html>
    <?php
} else {
    // Hatalı giriş
    header("Location: ../index.html?error=invalid");
    exit;
}
?>
