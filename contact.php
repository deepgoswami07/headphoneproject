<?php
require_once 'includes/config.php';
$page_title = "Contact";
$active = "contact";

$sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    // Kept simple: no messages table — just acknowledge on screen.
    // (Add a `messages` table + INSERT here if you want to store enquiries.)
    $sent = true;
}

require_once 'includes/header.php';
?>

<div class="wrap crumb"><a href="index.php">Home</a> / Contact</div>

<header class="page-hero">
  <div class="wrap">
    <span class="eyebrow">We're listening</span>
    <h1>Get in touch with the Soundphere team.</h1>
  </div>
</header>

<section style="padding-top:10px;">
  <div class="wrap simple-grid">
    <form method="POST" action="contact.php">
      <?php if ($sent): ?>
        <div class="cod-note" style="background:#E1F5EE;margin-bottom:20px;"><span>✅</span><div>Message sent — we'll reply within a working day.</div></div>
      <?php endif; ?>
      <div class="form-group"><label>Name</label><input name="cname" required placeholder="Your name"></div>
      <div class="form-group"><label>Email</label><input name="cemail" type="email" required placeholder="you@example.com"></div>
      <div class="form-group"><label>Subject</label>
        <select name="csubject">
          <option>Order question</option>
          <option>Product question</option>
          <option>Returns &amp; warranty</option>
          <option>Something else</option>
        </select>
      </div>
      <div class="form-group"><label>Message</label><textarea name="cmsg" rows="5" required placeholder="How can we help?"></textarea></div>
      <button type="submit" name="send_message" class="btn btn-accent btn-block">Send message</button>
    </form>

    <div>
      <div class="contact-card">
        <h4 class="mono" style="font-size:12.5px;letter-spacing:1px;color:var(--ink-faint);margin-bottom:10px;">EMAIL</h4>
        <p style="color:var(--ink);font-weight:600;">support@soundphere.example</p>
      </div>
      <div class="contact-card">
        <h4 class="mono" style="font-size:12.5px;letter-spacing:1px;color:var(--ink-faint);margin-bottom:10px;">PHONE</h4>
        <p style="color:var(--ink);font-weight:600;">+91 98765 43210 (10am–7pm, Mon–Sat)</p>
      </div>
      <div class="contact-card">
        <h4 class="mono" style="font-size:12.5px;letter-spacing:1px;color:var(--ink-faint);margin-bottom:10px;">STUDIO</h4>
        <p style="color:var(--ink);font-weight:600;">Ahmedabad, Gujarat, India</p>
      </div>
      <div class="map-block">📍 Ahmedabad, Gujarat — map preview</div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
