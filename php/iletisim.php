<?php
/**
 * php/iletisim.php
 * İletişim formu - sunucu tarafı işleme
 * Ali Ayyıldız - b241210069
 */

// Sadece POST kabul et
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../iletisim.html");
    exit;
}

// Gelen verileri temizle
function temizle($val) {
    return htmlspecialchars(strip_tags(trim($val ?? '')));
}

$ad          = temizle($_POST['ad']          ?? '');
$soyad       = temizle($_POST['soyad']       ?? '');
$email       = temizle($_POST['email']       ?? '');
$telefon     = temizle($_POST['telefon']     ?? '');
$dogumtarihi = temizle($_POST['dogumtarihi'] ?? '');
$konu        = temizle($_POST['konu']        ?? '');
$oncelik     = temizle($_POST['oncelik']     ?? '');
$mesaj       = temizle($_POST['mesaj']       ?? '');
$bulten      = temizle($_POST['bulten']      ?? 'hayır');
$kvkk        = temizle($_POST['kvkk']        ?? '');
$kanallar    = $_POST['iletisim_kanal'] ?? [];

// Temel zorunlu alan kontrolü
$hatalar = [];
if (empty($ad))      $hatalar[] = "Ad alanı boş.";
if (empty($soyad))   $hatalar[] = "Soyad alanı boş.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $hatalar[] = "Geçersiz e-posta formatı.";
if (!preg_match('/^[0-9\s\+]{10,13}$/', $telefon)) $hatalar[] = "Telefon yalnızca rakamlardan oluşmalı.";
if (empty($konu))    $hatalar[] = "Konu seçilmedi.";
if (empty($oncelik)) $hatalar[] = "Öncelik seçilmedi.";
if (strlen($mesaj) < 10) $hatalar[] = "Mesaj en az 10 karakter olmalı.";
if ($kvkk !== 'kabul') $hatalar[] = "KVKK onayı gerekli.";
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Form Sonucu – Ali Ayyıldız</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="../css/style.css"/>
  <style>
    .result-page { max-width:700px; margin:0 auto; padding:3rem 1rem; }
    .data-table { width:100%; border-collapse:collapse; margin-top:1rem; }
    .data-table tr { border-bottom:1px solid rgba(255,255,255,0.06); }
    .data-table td { padding:0.75rem 0.5rem; font-size:0.92rem; }
    .data-table td:first-child { color:var(--muted); width:38%; font-weight:500; }
    .data-table td:last-child { color:var(--light); }
    .section-head { color:var(--accent); font-size:0.8rem; text-transform:uppercase; letter-spacing:2px; margin:1.5rem 0 0.5rem; }
  </style>
</head>
<body>
<nav class="site-nav">
  <a href="../about.html" class="nav-brand">✦ Ali Ayyıldız</a>
  <ul class="nav-links">
    <li><a href="../about.html">Hakkında</a></li>
    <li><a href="../cv.html">Özgeçmiş</a></li>
    <li><a href="../sehrim.html">Şehrim</a></li>
    <li><a href="../miras.html">Mirasımız</a></li>
    <li><a href="../ilgi-alanlari.html">İlgi Alanlarım</a></li>
    <li><a href="../iletisim.html" class="active">İletişim</a></li>
  </ul>
</nav>

<div class="page-wrapper">
  <div class="result-page">

<?php if (!empty($hatalar)): ?>
    <!-- HATA DURUMU -->
    <div style="text-align:center;padding:2rem 0;">
      <div style="font-size:4rem;margin-bottom:1rem;">⚠️</div>
      <h1 style="font-size:1.8rem;margin-bottom:0.5rem;">Form Gönderilemedi</h1>
      <p style="color:var(--muted);">Aşağıdaki hatalar giderilmeden form işleme alınamaz.</p>
    </div>

    <div class="alert-custom alert-danger" style="margin-bottom:1.5rem;">
      <strong>Bulunan hatalar:</strong>
      <ul style="margin:0.5rem 0 0 1.2rem;">
        <?php foreach ($hatalar as $h): ?>
          <li><?= $h ?></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <a href="../iletisim.html" class="btn-gold" style="display:inline-block;">← Geri Dön</a>

<?php else: ?>
    <!-- BAŞARILI DURUM -->
    <div style="text-align:center;padding:2rem 0 1.5rem;">
      <div style="font-size:4rem;margin-bottom:1rem;animation:pop 0.5s ease;">✅</div>
      <h1 style="font-size:1.8rem;margin-bottom:0.5rem;">Form Başarıyla Alındı!</h1>
      <p style="color:var(--muted);">Gönderilen tüm veriler aşağıda düzenli şekilde listelenmiştir.</p>
    </div>

    <div class="card-custom">

      <p class="section-head">👤 Kişisel Bilgiler</p>
      <table class="data-table">
        <tr><td>Ad</td><td><?= $ad ?></td></tr>
        <tr><td>Soyad</td><td><?= $soyad ?></td></tr>
        <tr><td>E-posta</td><td><?= $email ?></td></tr>
        <tr><td>Telefon</td><td><?= $telefon ?></td></tr>
        <?php if ($dogumtarihi): ?>
        <tr><td>Doğum Tarihi</td><td><?= $dogumtarihi ?></td></tr>
        <?php endif; ?>
      </table>

      <p class="section-head">📋 Mesaj Detayları</p>
      <table class="data-table">
        <tr><td>Konu</td><td><?= ucfirst($konu) ?></td></tr>
        <tr><td>Öncelik</td><td>
          <?php
            $oncelikLabel = ['dusuk'=>'🟢 Düşük','orta'=>'🟡 Orta','yuksek'=>'🔴 Yüksek'];
            echo $oncelikLabel[$oncelik] ?? $oncelik;
          ?>
        </td></tr>
        <tr>
          <td>Mesaj</td>
          <td style="white-space:pre-wrap;line-height:1.7;"><?= $mesaj ?></td>
        </tr>
      </table>

      <p class="section-head">⚙️ Tercihler</p>
      <table class="data-table">
        <tr>
          <td>İletişim Kanalları</td>
          <td>
            <?php if (!empty($kanallar)): ?>
              <?php foreach ($kanallar as $k): ?>
                <span style="background:rgba(212,175,55,0.15);border:1px solid var(--border);padding:0.2rem 0.75rem;border-radius:50px;font-size:0.82rem;margin-right:0.4rem;">
                  <?= htmlspecialchars($k) ?>
                </span>
              <?php endforeach; ?>
            <?php else: ?>
              <span style="color:var(--muted);">Seçilmedi</span>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td>Bülten Kaydı</td>
          <td><?= ($bulten === 'evet') ? '✅ Evet' : '❌ Hayır' ?></td>
        </tr>
        <tr>
          <td>KVKK Onayı</td>
          <td><?= ($kvkk === 'kabul') ? '✅ Onaylandı' : '❌ Onaylanmadı' ?></td>
        </tr>
      </table>

      <p class="section-head">🕐 Gönderim Bilgisi</p>
      <table class="data-table">
        <tr><td>Gönderim Zamanı</td><td><?= date('d.m.Y H:i:s') ?></td></tr>
        <tr><td>IP Adresi</td><td><?= htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'Bilinmiyor') ?></td></tr>
        <tr><td>Tarayıcı</td><td style="font-size:0.8rem;"><?= htmlspecialchars(substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 80)) ?>...</td></tr>
      </table>

    </div>

    <div style="display:flex;gap:1rem;margin-top:1.5rem;flex-wrap:wrap;">
      <a href="../iletisim.html" class="btn-outline-gold" style="display:inline-block;">← Yeni Mesaj</a>
      <a href="../about.html" class="btn-gold" style="display:inline-block;">Ana Sayfa →</a>
    </div>

<?php endif; ?>
  </div>
</div>

<footer class="site-footer">
  <p>© 2026 <a href="../about.html">Ali Ayyıldız</a> – Sakarya Üniversitesi Web Teknolojileri Projesi</p>
</footer>
<style>@keyframes pop { from{transform:scale(0)} to{transform:scale(1)} }</style>
</body>
</html>
