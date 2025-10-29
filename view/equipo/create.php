<div class="container mt-4">
  <h2><?= htmlspecialchars($title ?? 'Nou equip') ?></h2>

  <div class="">
    <?php
      $action = $baseUri . '/equipo';
      $button = 'Crea equip';
      include __DIR__ . '/form.php';
    ?>
  </div>
</div>
