<div class="container mt-4">
  <h2><?= htmlspecialchars($title ?? 'Edita equip') ?></h2>
  <a href="<?= $baseUri ?>/equipo" class="btn btn-secondary btn-sm mb-3">← Torna al llistat</a>

  <div class="">
    <?php
      $action = $baseUri . '/equipo/' . $equipo->getId() . '/update';
      $button = '💾 Desa canvis';
      include __DIR__ . '/form.php';
    ?>
  </div>
</div>
