<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

$ok    = get_flash('ok');
$error = get_flash('error');

if ($ok): ?>
  <div class="alert alert-success"><?= htmlspecialchars($ok) ?></div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
