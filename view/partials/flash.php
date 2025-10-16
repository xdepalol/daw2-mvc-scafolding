<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$ok    = $_SESSION['ok']    ?? null;
$error = $_SESSION['error'] ?? null;

unset($_SESSION['ok'], $_SESSION['error']);

if ($ok): ?>
  <div class="alert alert-success"><?= htmlspecialchars($ok) ?></div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
