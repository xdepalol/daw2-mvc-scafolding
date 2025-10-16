<?php
/**
 * Vista: Detall d'un equip
 * Variables disponibles:
 *   - $title   (string)  → Títol de la pàgina
 *   - $equipo  (App\Model\Equipo) → Instància de l'equip
 *   - $baseUri (string)  → Prefix dinàmic de rutes (passat des del controlador o global)
 */
  $name = $equipo->GetNombre();
  $title = $title ?? 'Detalls de l\'equip {$name}';
?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0"><?= htmlspecialchars($title) ?></h2>
    <div>
      <a href="<?= $baseUri ?>/equipo" class="btn btn-secondary btn-sm">
        ← Tornar al llistat
      </a>
      <a href="<?= $baseUri ?>/equipo/<?= $equipo->getId() ?>/edit" class="btn btn-primary btn-sm">
        ✏️ Edita
      </a>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <dl class="row">
        <dt class="col-sm-3">Nom</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($equipo->getNombre()) ?></dd>

        <dt class="col-sm-3">Ciutat</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($equipo->getCiudad()) ?></dd>

        <dt class="col-sm-3">País</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($equipo->getPais()) ?></dd>

        <dt class="col-sm-3">Creat</dt>
        <dd class="col-sm-9">
          <?= $equipo->getCreatedAt()?->format('Y-m-d H:i:s') ?? '—' ?>
        </dd>

        <dt class="col-sm-3">Actualitzat</dt>
        <dd class="col-sm-9">
          <?= $equipo->getUpdatedAt()?->format('Y-m-d H:i:s') ?? '—' ?>
        </dd>
      </dl>
    </div>
  </div>

  <form action="<?= $baseUri ?>/equipo/<?= $equipo->getId() ?>/delete" method="POST" class="mt-4"
        onsubmit="return confirm('Segur que vols eliminar aquest equip?');">
    <button type="submit" class="btn btn-danger">
      🗑️ Elimina equip
    </button>
  </form>
</div>