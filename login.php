<?php
require_once 'includes/config.php';
$page_title = "Login";

$error = "";
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_login'])) {
    $email = trim(strtolower($_POST['email']));
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT id, name, password FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $redirect = $_GET['redirect'] ?? 'index.php';
        header("Location: " . $redirect);
        exit;
    } else {
        $error = "Email or password is incorrect.";
    }
}

require_once 'includes/header.php';
?>

<div class="wrap auth-wrap">
  <div style="text-align:center;margin-bottom:26px;">
    <span class="eyebrow">Account required to buy</span>
    <h1 style="font-size:28px;margin-top:12px;">Log in to Soundphere</h1>
    <p style="margin-top:8px;">Browsing is always free — checkout needs a login.</p>
  </div>

  <div class="auth-card">
    <?php if ($error): ?>
      <div class="auth-error show"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>">
      <div class="form-group"><label>Email</label><input name="email" type="email" required placeholder="you@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"></div>
      <div class="form-group"><label>Password</label><input name="password" type="password" required placeholder="Your password"></div>
      <button type="submit" name="do_login" class="btn btn-accent btn-block">Log in</button>
    </form>
    <div class="auth-foot">New here? <a href="register.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>">Create an account</a></div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
