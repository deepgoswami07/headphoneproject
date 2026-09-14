<?php
// Reusable header. Include AFTER includes/config.php on every page.
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';

// cart count badge (only meaningful when logged in)
$cart_count = 0;
if ($is_logged_in) {
    $uid = $_SESSION['user_id'];
    $res = mysqli_query($conn, "SELECT COALESCE(SUM(qty),0) AS total FROM cart WHERE user_id = $uid");
    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $cart_count = (int) $row['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? htmlspecialchars($page_title) . " — Soundphere" : "Soundphere"; ?></title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="nav">
  <div class="nav-inner">
    <a href="index.php" class="logo"><span class="dot-orbit"><span></span></span> Soundphere</a>
    <div class="nav-links">
      <a href="index.php" class="<?php echo ($active ?? '') === 'home' ? 'active' : ''; ?>">Home</a>
      <a href="product.php" class="<?php echo ($active ?? '') === 'product' ? 'active' : ''; ?>">Aura One</a>
      <a href="about.php" class="<?php echo ($active ?? '') === 'about' ? 'active' : ''; ?>">About</a>
      <a href="contact.php" class="<?php echo ($active ?? '') === 'contact' ? 'active' : ''; ?>">Contact</a>
    </div>
    <div class="nav-cta">
      <?php if ($is_logged_in): ?>
        <div class="account-chip">
          <span class="account-avatar"><?php echo strtoupper(substr($user_name, 0, 1)); ?></span>
          <span class="account-name"><?php echo htmlspecialchars(explode(' ', $user_name)[0]); ?></span>
          <a href="logout.php" class="account-logout">Logout</a>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn-login-nav">Login</a>
      <?php endif; ?>
      <a href="cart.php" class="cart-pill">Cart <span class="count"><?php echo $cart_count; ?></span></a>
    </div>
  </div>
</nav>
