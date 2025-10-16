<div class="container mt-4">
  <h2><?= htmlspecialchars($title ?? 'Nou equip') ?></h2>
  <a href="<?= $baseUri ?>/equipo" class="btn btn-secondary btn-sm mb-3 btn-back">Torna al llistat</a>

  <div class="">
    <?php
      $action = $baseUri . '/equipo';
      $button = 'Crea equip';
      include __DIR__ . '/form.php';
    ?>
  </div>
</div>
