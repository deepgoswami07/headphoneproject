<?php
require_once 'includes/config.php';
$page_title = "Home";
$active = "home";

// fetch the product for the home page teaser
$result = mysqli_query($conn, "SELECT * FROM products LIMIT 1");
$product = mysqli_fetch_assoc($result);

require_once 'includes/header.php';
?>

<header class="hero">
  <div class="wrap hero-inner">
    <div>
      <span class="eyebrow">New — <?php echo htmlspecialchars($product['name']); ?></span>
      <h1>Sound that <em>orbits</em><br>around you.</h1>
      <p class="lead">One headphone, obsessed over. 45-hour battery, adaptive ANC, and a fit that disappears — so all that's left is the music.</p>
      <div class="hero-actions">
        <a href="product.php" class="btn btn-accent">Shop <?php echo htmlspecialchars($product['name']); ?> — &#8377;<?php echo number_format($product['price']); ?></a>
        <a href="#features" class="btn btn-outline">Why Soundphere</a>
      </div>
      <div class="hero-stats">
        <div><strong>45 hrs</strong><span>BATTERY LIFE</span></div>
        <div><strong>4.8 / 5</strong><span>312 REVIEWS</span></div>
        <div><strong>COD</strong><span>PAY ON DELIVERY</span></div>
      </div>
    </div>
    <div class="orbit-stage">
      <div class="orbit-ring r1"></div>
      <div class="orbit-ring r2"></div>
      <div class="orbit-sat"></div>
      <img class="orbit-img" src="<?php echo htmlspecialchars($product['image1']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>
  </div>
</header>

<section id="features">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="eyebrow">Why it feels different</span>
        <h2>Built around three quiet obsessions.</h2>
      </div>
      <p>Every part of <?php echo htmlspecialchars($product['name']); ?> earns its place — nothing decorative, nothing left out.</p>
    </div>
    <div class="feature-grid">
      <div class="feature-card">
        <div class="feature-icon">🎧</div>
        <span class="num">01 — SOUND</span>
        <h3>40mm drivers, tuned warm</h3>
        <p>Neodymium drivers built for depth first, loudness second — vocals stay forward, bass stays controlled.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🔋</div>
        <span class="num">02 — BATTERY</span>
        <h3>45 hours, 10-minute top-ups</h3>
        <p>A full week of commutes on one charge. Ten minutes on USB-C buys you six more hours.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">🤫</div>
        <span class="num">03 — SILENCE</span>
        <h3>Adaptive hybrid ANC</h3>
        <p>Reads the room every few seconds and adjusts cancellation — flip to Transparency to hear your name called.</p>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="product-strip">
      <img src="<?php echo htmlspecialchars($product['image2']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
      <div>
        <span class="badge">30% OFF LAUNCH PRICE</span>
        <h2 style="font-size:clamp(26px,3vw,36px);"><?php echo htmlspecialchars($product['name']); ?></h2>
        <p style="margin-top:8px;">Wireless over-ear headphones with adaptive ANC, 45-hour battery, and memory-foam comfort.</p>
        <div class="price-tag">&#8377;<?php echo number_format($product['price']); ?> <span class="old">&#8377;<?php echo number_format($product['old_price']); ?></span></div>
        <div class="hero-actions">
          <a href="product.php" class="btn btn-primary">View product</a>
          <a href="cart.php" class="btn btn-outline">Go to cart</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="cta-band">
      <div>
        <span class="eyebrow" style="color:#FF8A3D;">Order today</span>
        <h2>Free shipping, pay when it arrives.</h2>
        <p>Cash on Delivery available across India — check the box, we handle the rest.</p>
      </div>
      <a href="product.php" class="btn btn-accent">Shop <?php echo htmlspecialchars($product['name']); ?></a>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
