<?php
require_once 'includes/config.php';
$page_title = "About";
$active = "about";
require_once 'includes/header.php';
?>

<div class="wrap crumb"><a href="index.php">Home</a> / About</div>

<header class="page-hero">
  <div class="wrap">
    <span class="eyebrow">Our story</span>
    <h1>One product, made properly — instead of a hundred, made fast.</h1>
  </div>
</header>

<section>
  <div class="wrap simple-grid">
    <div>
      <p style="font-size:17px;color:var(--ink);">Soundphere started with a simple frustration: most headphone brands chase ten launches a year and get none of them quite right. We picked one — Aura One — and spent our time tuning it instead.</p>
      <br>
      <p>We're a small India-based team of two audio engineers and a product designer. No warehouses of SKUs, no seasonal drops — just one headphone we're proud to keep improving, backed by a straightforward Cash-on-Delivery checkout so trying us out is a low-risk first step.</p>
      <ul class="value-list">
        <li><strong>Focused</strong><br><span style="color:var(--ink-soft);font-size:14px;">One product, continuously refined.</span></li>
        <li><strong>Transparent</strong><br><span style="color:var(--ink-soft);font-size:14px;">Real specs, no inflated claims.</span></li>
        <li><strong>Accessible</strong><br><span style="color:var(--ink-soft);font-size:14px;">COD, free shipping, easy returns.</span></li>
        <li><strong>India-first</strong><br><span style="color:var(--ink-soft);font-size:14px;">Designed and supported locally.</span></li>
      </ul>
    </div>
    <div class="orbit-stage">
      <div class="orbit-ring r1"></div>
      <div class="orbit-ring r2"></div>
      <?php
        $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image3 FROM products LIMIT 1"));
      ?>
      <img class="orbit-img" src="<?php echo htmlspecialchars($r['image3']); ?>" alt="Aura One detail">
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="cta-band">
      <div>
        <span class="eyebrow" style="color:#FF8A3D;">Say hello</span>
        <h2>Questions before you order?</h2>
        <p>We reply to every message within a working day.</p>
      </div>
      <a href="contact.php" class="btn btn-accent">Contact us</a>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
