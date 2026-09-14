<?php
require_once 'includes/config.php';
$page_title = "Checkout";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout.php");
    exit;
}
$uid = $_SESSION['user_id'];

$sql = "SELECT c.id AS cart_id, c.qty, c.color, c.product_id, p.name, p.price
        FROM cart c JOIN products p ON c.product_id = p.id
        WHERE c.user_id = $uid";
$items = mysqli_query($conn, $sql);
$rows = mysqli_fetch_all($items, MYSQLI_ASSOC);

$subtotal = 0;
foreach ($rows as $r) { $subtotal += $r['price'] * $r['qty']; }

$order_placed = false;
$order_code = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order']) && !empty($rows)) {
    $name = trim($_POST['fname']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['addr']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pin']);
    $state = trim($_POST['state']);

    $order_code = "SPH" . rand(100000, 999999);

    // one order row per cart line (simple, single-product store)
    foreach ($rows as $r) {
        $line_total = $r['price'] * $r['qty'];
        $stmt = mysqli_prepare($conn, "INSERT INTO orders
            (order_code, user_id, product_id, qty, color, customer_name, phone, address, city, pincode, state, payment_mode, total_amount)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,'COD',?)");
        mysqli_stmt_bind_param($stmt, "siiisssssssi",
            $order_code, $uid, $r['product_id'], $r['qty'], $r['color'],
            $name, $phone, $address, $city, $pincode, $state, $line_total
        );
        mysqli_stmt_execute($stmt);
    }

    // clear the cart
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = $uid");

    $order_placed = true;
}

require_once 'includes/header.php';
?>

<div class="wrap crumb"><a href="index.php">Home</a> / <a href="cart.php">Cart</a> / Checkout</div>

<section style="padding-top:20px;">
  <div class="wrap">
    <span class="eyebrow">Step 2 of 2</span>
    <h2 style="font-size:32px;margin:10px 0 24px;">Checkout</h2>
    <div class="step-line"><div class="active"></div><div class="active"></div></div>

    <?php if ($order_placed): ?>
      <div class="empty-state">
        <div class="orbit-empty" style="border-color:var(--accent);">✅</div>
        <h3 style="margin-bottom:10px;">Order placed — <?php echo htmlspecialchars($order_code); ?></h3>
        <p style="max-width:420px;margin:0 auto 26px;">Thanks! Your order is saved in the database and being packed. Pay in cash when it's delivered to your address.</p>
        <a href="index.php" class="btn btn-accent">Back to home</a>
      </div>

    <?php elseif (empty($rows)): ?>
      <div class="empty-state">
        <div class="orbit-empty">🛒</div>
        <h3 style="margin-bottom:10px;">Nothing to check out yet</h3>
        <p style="max-width:340px;margin:0 auto 26px;">Add Aura One to your cart first.</p>
        <a href="product.php" class="btn btn-accent">Shop Aura One</a>
      </div>

    <?php else: ?>
      <div class="checkout-layout">
        <form method="POST" action="checkout.php">
          <h3 style="font-size:17px;margin-bottom:16px;">Delivery details</h3>
          <div class="form-row">
            <div class="form-group"><label>Full name</label><input name="fname" required placeholder="Your name"></div>
            <div class="form-group"><label>Phone number</label><input name="phone" required pattern="[0-9]{10}" placeholder="10-digit mobile"></div>
          </div>
          <div class="form-group"><label>Address</label><textarea name="addr" required rows="3" placeholder="House no, street, area"></textarea></div>
          <div class="form-row">
            <div class="form-group"><label>City</label><input name="city" required placeholder="City"></div>
            <div class="form-group"><label>Pincode</label><input name="pin" required pattern="[0-9]{6}" placeholder="6-digit pincode"></div>
          </div>
          <div class="form-group"><label>State</label>
            <select name="state" required>
              <option value="">Select state</option>
              <option>Gujarat</option><option>Maharashtra</option><option>Delhi</option>
              <option>Karnataka</option><option>Rajasthan</option><option>Uttar Pradesh</option>
              <option>Other</option>
            </select>
          </div>

          <h3 style="font-size:17px;margin:28px 0 12px;">Payment method</h3>
          <div class="pay-option">
            <div class="tick">✓</div>
            <div><strong>Cash on Delivery (COD)</strong><span>Pay in cash to the delivery partner when your order arrives.</span></div>
          </div>
          <div class="pay-option pay-disabled">
            <div class="tick" style="background:var(--ink-faint);">✕</div>
            <div><strong>Card / UPI</strong><span>Currently unavailable — COD only for now.</span></div>
          </div>

          <button type="submit" name="place_order" class="btn btn-accent btn-block" style="margin-top:28px;">Place order — Pay &#8377;<?php echo number_format($subtotal); ?> on delivery</button>
        </form>

        <div class="summary-card">
          <h3 style="font-size:18px;margin-bottom:18px;">Order summary</h3>
          <?php foreach ($rows as $r): ?>
            <div style="display:flex;justify-content:space-between;font-size:14px;padding:8px 0;">
              <span><?php echo htmlspecialchars($r['name']); ?> · <?php echo htmlspecialchars($r['color']); ?> × <?php echo $r['qty']; ?></span>
              <span class="mono">&#8377;<?php echo number_format($r['price'] * $r['qty']); ?></span>
            </div>
          <?php endforeach; ?>
          <div class="summary-row"><span>Subtotal</span><span class="mono">&#8377;<?php echo number_format($subtotal); ?></span></div>
          <div class="summary-row"><span>Shipping</span><span class="mono" style="color:var(--accent-dark);">FREE</span></div>
          <div class="summary-row total"><span>Total (COD)</span><span class="mono">&#8377;<?php echo number_format($subtotal); ?></span></div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
