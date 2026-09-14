<?php
require_once 'includes/config.php';
$page_title = "Aura one";
$active = "product";

$result = mysqli_query($conn, "SELECT * FROM products LIMIT 1");
$product = mysqli_fetch_assoc($result);

$notice = "";

// ---- Add to cart (requires login) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?redirect=product.php");
        exit;
    }
    $uid = $_SESSION['user_id'];
    $pid = (int) $_POST['product_id'];
    $qty = max(1, (int) $_POST['qty']);
    $color = $_POST['color'];

    // if same product+color already in cart, just increase qty
    $check = mysqli_prepare($conn, "SELECT id, qty FROM cart WHERE user_id=? AND product_id=? AND color=?");
    mysqli_stmt_bind_param($check, "iis", $uid, $pid, $color);
    mysqli_stmt_execute($check);
    $res = mysqli_stmt_get_result($check);

    if ($row = mysqli_fetch_assoc($res)) {
        $newQty = $row['qty'] + $qty;
        $upd = mysqli_prepare($conn, "UPDATE cart SET qty=? WHERE id=?");
        mysqli_stmt_bind_param($upd, "ii", $newQty, $row['id']);
        mysqli_stmt_execute($upd);
    } else {
        $ins = mysqli_prepare($conn, "INSERT INTO cart (user_id, product_id, color, qty) VALUES (?,?,?,?)");
        mysqli_stmt_bind_param($ins, "iisi", $uid, $pid, $color, $qty);
        mysqli_stmt_execute($ins);
    }

    if (isset($_POST['buy_now'])) {
        header("Location: checkout.php");
        exit;
    }
    $notice = "Added to cart!";
}

require_once 'includes/header.php';
?>

<div class="wrap crumb"><a href="index.php">Home</a> / <a href="product.php">Aura One</a></div>

<div class="wrap product-page">

  <div class="gallery">
    <div class="gallery-frame" id="galleryFrame">
      <div class="gallery-track" id="galleryTrack">
        <img src="<?php echo htmlspecialchars($product['image1']); ?>" alt="Aura One — front view">
        <img src="<?php echo htmlspecialchars($product['image2']); ?>" alt="Aura One — angle view">
        <img src="<?php echo htmlspecialchars($product['image3']); ?>" alt="Aura One — detail view">
      </div>
      <button class="gallery-arrow prev" id="prevBtn" type="button" aria-label="Previous image">‹</button>
      <button class="gallery-arrow next" id="nextBtn" type="button" aria-label="Next image">›</button>
      <div class="gallery-hint">👆 swipe to explore</div>
    </div>
    <div class="gallery-dots" id="galleryDots">
      <button class="active" data-i="0" type="button" aria-label="Image 1"></button>
      <button data-i="1" type="button" aria-label="Image 2"></button>
      <button data-i="2" type="button" aria-label="Image 3"></button>
    </div>
    <div class="thumb-row" id="thumbRow">
      <button class="active" data-i="0" type="button"><img src="<?php echo htmlspecialchars($product['image1']); ?>" alt=""></button>
      <button data-i="1" type="button"><img src="<?php echo htmlspecialchars($product['image2']); ?>" alt=""></button>
      <button data-i="2" type="button"><img src="<?php echo htmlspecialchars($product['image3']); ?>" alt=""></button>
    </div>
  </div>

  <div class="pd-info">
    <span class="badge">30% OFF LAUNCH PRICE</span>
    <h1 class="pd-title"><?php echo htmlspecialchars($product['name']); ?></h1>
    <p class="pd-sub">Wireless Over-Ear Headphones · Orbit Violet</p>

    <div class="rating-row">
      <span class="stars">★★★★★</span>
      <span class="mono" style="color:var(--ink-faint);">4.8 (312 reviews)</span>
    </div>

    <div class="price-tag">&#8377;<?php echo number_format($product['price']); ?> <span class="old">&#8377;<?php echo number_format($product['old_price']); ?></span></div>

    <?php if ($notice): ?>
      <div class="cod-note" style="background:#E1F5EE;"><span>✅</span><div><?php echo htmlspecialchars($notice); ?></div></div>
    <?php endif; ?>

    <p><?php echo htmlspecialchars($product['description']); ?></p>

    <form method="POST" action="product.php">
      <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

      <div>
        <label class="mono" style="font-size:12.5px;color:var(--ink-faint);">COLOUR</label>
        <div class="color-row">
          <label><input type="radio" name="color" value="Orbit Violet" checked style="display:none;" class="color-radio"><span class="color-dot active" style="background:#5B3CFF;" data-color="Orbit Violet"></span></label>
          <label><input type="radio" name="color" value="Amber Dusk" style="display:none;" class="color-radio"><span class="color-dot" style="background:#FF8A3D;" data-color="Amber Dusk"></span></label>
          <label><input type="radio" name="color" value="Ink Black" style="display:none;" class="color-radio"><span class="color-dot" style="background:#1C1B22;" data-color="Ink Black"></span></label>
        </div>
      </div>

      <div class="qty-row">
        <span class="mono" style="font-size:12.5px;color:var(--ink-faint);">QUANTITY</span>
        <div class="qty-box">
          <button type="button" id="qtyMinus" aria-label="Decrease quantity">−</button>
          <span id="qtyVal">1</span>
          <button type="button" id="qtyPlus" aria-label="Increase quantity">+</button>
        </div>
        <input type="hidden" name="qty" id="qtyInput" value="1">
      </div>

      <div class="pd-actions">
        <button type="submit" name="add_to_cart" class="btn btn-primary btn-block">Add to cart</button>
        <button type="submit" name="add_to_cart" value="1" class="btn btn-accent btn-block" onclick="document.getElementById('buyNowFlag').value='1';">Buy now</button>
        <input type="hidden" name="buy_now" id="buyNowFlag" value="">
      </div>
    </form>

    <div class="cod-note">
      <span>💵</span>
      <div><strong>Cash on Delivery available.</strong><br>Pay in cash when your order reaches your doorstep — no card or UPI needed.</div>
    </div>

    <table class="spec-table">
      <tbody>
        <tr><td>Driver</td><td>40mm dynamic, neodymium</td></tr>
        <tr><td>Battery</td><td>45 hrs (ANC off) / 32 hrs (ANC on)</td></tr>
        <tr><td>Charging</td><td>USB-C, 10 min = 6 hrs playback</td></tr>
        <tr><td>Connectivity</td><td>Bluetooth 5.3, multipoint</td></tr>
        <tr><td>Weight</td><td>254g</td></tr>
        <tr><td>Warranty</td><td>12 months, India-wide service</td></tr>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
  // Gallery swipe/arrows/dots (same as before, pure front-end)
  const track = document.getElementById('galleryTrack');
  const dots = [...document.querySelectorAll('#galleryDots button')];
  const thumbs = [...document.querySelectorAll('#thumbRow button')];
  const frame = document.getElementById('galleryFrame');
  let index = 0;
  const total = 3;
  function goTo(i) {
    index = (i + total) % total;
    track.style.transform = `translateX(-${index * 100}%)`;
    dots.forEach((d, di) => d.classList.toggle('active', di === index));
    thumbs.forEach((t, ti) => t.classList.toggle('active', ti === index));
  }
  document.getElementById('prevBtn').addEventListener('click', () => goTo(index - 1));
  document.getElementById('nextBtn').addEventListener('click', () => goTo(index + 1));
  dots.forEach(d => d.addEventListener('click', () => goTo(+d.dataset.i)));
  thumbs.forEach(t => t.addEventListener('click', () => goTo(+t.dataset.i)));
  let startX = 0, isDown = false;
  frame.addEventListener('touchstart', e => { startX = e.touches[0].clientX; isDown = true; }, {passive:true});
  frame.addEventListener('touchmove', e => {
    if (!isDown) return;
    const dx = e.touches[0].clientX - startX;
    track.style.transform = `translateX(calc(-${index * 100}% + ${dx}px))`;
  }, {passive:true});
  frame.addEventListener('touchend', e => {
    if (!isDown) return;
    isDown = false;
    const dx = e.changedTouches[0].clientX - startX;
    if (dx > 60) goTo(index - 1); else if (dx < -60) goTo(index + 1); else goTo(index);
  });

  // colour swatches
  document.querySelectorAll('.color-dot').forEach(dot => {
    dot.addEventListener('click', () => {
      document.querySelectorAll('.color-dot').forEach(d => d.classList.remove('active'));
      dot.classList.add('active');
      dot.previousElementSibling ?? null;
      dot.closest('label').querySelector('.color-radio').checked = true;
    });
  });

  // quantity
  let qty = 1;
  const qtyVal = document.getElementById('qtyVal');
  const qtyInput = document.getElementById('qtyInput');
  document.getElementById('qtyPlus').addEventListener('click', () => { qty++; qtyVal.textContent = qty; qtyInput.value = qty; });
  document.getElementById('qtyMinus').addEventListener('click', () => { qty = Math.max(1, qty - 1); qtyVal.textContent = qty; qtyInput.value = qty; });
</script>
