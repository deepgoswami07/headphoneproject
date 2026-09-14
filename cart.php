<?php
require_once 'includes/config.php';
$page_title = "Your Cart";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=cart.php");
    exit;
}
$uid = $_SESSION['user_id'];

// handle qty update / remove
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_qty'])) {
        $cart_id = (int) $_POST['cart_id'];
        $qty = (int) $_POST['qty'];
        if ($qty < 1) {
            $stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE id=? AND user_id=?");
            mysqli_stmt_bind_param($stmt, "ii", $cart_id, $uid);
        } else {
            $stmt = mysqli_prepare($conn, "UPDATE cart SET qty=? WHERE id=? AND user_id=?");
            mysqli_stmt_bind_param($stmt, "iii", $qty, $cart_id, $uid);
        }
        mysqli_stmt_execute($stmt);
    }
    if (isset($_POST['remove_item'])) {
        $cart_id = (int) $_POST['cart_id'];
        $stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE id=? AND user_id=?");
        mysqli_stmt_bind_param($stmt, "ii", $cart_id, $uid);
        mysqli_stmt_execute($stmt);
    }
    header("Location: cart.php");
    exit;
}

$sql = "SELECT c.id AS cart_id, c.qty, c.color, p.name, p.price, p.image1
        FROM cart c JOIN products p ON c.product_id = p.id
        WHERE c.user_id = $uid";
$items = mysqli_query($conn, $sql);
$rows = mysqli_fetch_all($items, MYSQLI_ASSOC);

$subtotal = 0;
foreach ($rows as $r) { $subtotal += $r['price'] * $r['qty']; }

require_once 'includes/header.php';
?>

<div class="wrap crumb"><a href="index.php">Home</a> / Cart</div>

<section style="padding-top:20px;">
  <div class="wrap">
    <div class="section-head" style="margin-bottom:24px;">
      <div><span class="eyebrow">Step 1 of 2</span><h2 style="font-size:32px;">Your cart</h2></div>
    </div>

    <?php if (empty($rows)): ?>
      <div class="empty-state">
        <div class="orbit-empty">🎧</div>
        <h3 style="margin-bottom:10px;">Your cart is orbiting empty</h3>
        <p style="max-width:340px;margin:0 auto 26px;">Add Aura One to your cart to see it here.</p>
        <a href="product.php" class="btn btn-accent">Shop Aura One</a>
      </div>
    <?php else: ?>
      <div class="cart-layout">
        <div>
          <?php foreach ($rows as $r): ?>
            <div class="cart-item">
              <img src="<?php echo htmlspecialchars($r['image1']); ?>" alt="<?php echo htmlspecialchars($r['name']); ?>">
              <div>
                <h4><?php echo htmlspecialchars($r['name']); ?></h4>
                <div class="meta">COLOUR: <?php echo htmlspecialchars(strtoupper($r['color'])); ?></div>
                <form method="POST" style="display:inline-flex;align-items:center;gap:10px;margin-top:10px;">
                  <input type="hidden" name="cart_id" value="<?php echo $r['cart_id']; ?>">
                  <div class="qty-box">
                    <button type="submit" name="update_qty" value="1" onclick="this.form.qty.value=<?php echo $r['qty'] - 1; ?>">−</button>
                    <input type="hidden" name="qty" value="<?php echo $r['qty']; ?>">
                    <span><?php echo $r['qty']; ?></span>
                    <button type="submit" name="update_qty" value="1" onclick="this.form.qty.value=<?php echo $r['qty'] + 1; ?>">+</button>
                  </div>
                </form>
              </div>
              <strong class="mono">&#8377;<?php echo number_format($r['price'] * $r['qty']); ?></strong>
              <form method="POST">
                <input type="hidden" name="cart_id" value="<?php echo $r['cart_id']; ?>">
                <button type="submit" name="remove_item" value="1" class="remove-btn">Remove</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="summary-card">
          <h3 style="font-size:18px;margin-bottom:18px;">Order summary</h3>
          <div class="summary-row"><span>Subtotal</span><span class="mono">&#8377;<?php echo number_format($subtotal); ?></span></div>
          <div class="summary-row"><span>Shipping</span><span class="mono" style="color:var(--accent-dark);">FREE</span></div>
          <div class="summary-row total"><span>Total</span><span class="mono">&#8377;<?php echo number_format($subtotal); ?></span></div>
          <a href="checkout.php" class="btn btn-accent btn-block" style="margin-top:20px;">Proceed to checkout</a>
          <p style="margin-top:14px;font-size:12.5px;">💵 Cash on Delivery available at checkout.</p>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
