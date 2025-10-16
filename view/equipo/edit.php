<div class="container mt-4">
  <h2><?= htmlspecialchars($title ?? 'Editant equip') ?></h2>

  <div class="">
    <?php
      $action = $baseUri . '/equipo/' . $equipo->getId() . '/update';
      $button = 'Desa canvis';
      include __DIR__ . '/form.php';
    ?>
  </div>
</div>
