<?php
require_once 'includes/config.php';
$page_title = "Register";

$error = "";
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_register'])) {
    $name = trim($_POST['name']);
    $email = trim(strtolower($_POST['email']));
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    if ($name === "" || $email === "" || $phone === "" || $password === "") {
        $error = "Please fill in all fields.";
    } else {
        // check if email already used
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "An account with this email already exists. Try logging in instead.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_prepare($conn, "INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert, "ssss", $name, $email, $phone, $hashed);

            if (mysqli_stmt_execute($insert)) {
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['user_name'] = $name;
                $redirect = $_GET['redirect'] ?? 'index.php';
                header("Location: " . $redirect);
                exit;
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

require_once 'includes/header.php';
?>

<div class="wrap auth-wrap">
  <div style="text-align:center;margin-bottom:26px;">
    <span class="eyebrow">Account required to buy</span>
    <h1 style="font-size:28px;margin-top:12px;">Create your Soundphere account</h1>
  </div>

  <div class="auth-card">
    <?php if ($error): ?>
      <div class="auth-error show"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>">
      <div class="form-group"><label>Full name</label><input name="name" required placeholder="Your name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"></div>
      <div class="form-group"><label>Email</label><input name="email" type="email" required placeholder="you@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"></div>
      <div class="form-group"><label>Phone number</label><input name="phone" required pattern="[0-9]{10}" placeholder="10-digit mobile" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"></div>
      <div class="form-group"><label>Password</label><input name="password" type="password" required minlength="4" placeholder="At least 4 characters"></div>
      <button type="submit" name="do_register" class="btn btn-accent btn-block">Create account</button>
    </form>
    <div class="auth-foot">Already have an account? <a href="login.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>">Log in</a></div>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
