<?php
$title = "Equips";
?>
<h1 class="mb-4">Equips</h1>

<table class="table table-striped" data-dt>
  <thead class="table-dark">
    <tr>
      <th>#</th>
      <th>Nom</th>
      <th>Ciutat</th>
      <th>País</th>
      <th>Accions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($equipos as $e): ?>
      <tr>
        <td><?= $e->getId() ?></td>
        <td><?= htmlspecialchars($e->getNombre()) ?></td>
        <td><?= htmlspecialchars($e->getCiudad()) ?></td>
        <td><?= htmlspecialchars($e->getPais()) ?></td>
        <td>
          <a href="equipo/<?= $e->getId() ?>" class="btn btn-sm btn-outline-primary">Veure</a>
          <a href="equipo/<?= $e->getId() ?>/edit" class="btn btn-sm btn-outline-secondary">Editar</a>
          <form action="equipo/<?= $e->getId() ?>/delete" method="post" style="display:inline;">
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Eliminar <?= $e->getNombre() ?>?')">Eliminar</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
