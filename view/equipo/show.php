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
  <h2 class="mb-0"><?= htmlspecialchars($title) ?></h2>

  <div>
    <div class="mb-3 row">
      <label for="nombre" class="col-sm-2 col-form-label">Email</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="nombre" value="<?= htmlspecialchars($equipo->getNombre()) ?>">
      </div>
    </div>
 
    <div class="mb-3 row">
      <label for="ciudad" class="col-sm-2 col-form-label">Ciutat</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="ciudad" value="<?= htmlspecialchars($equipo->getCiudad()) ?>">
      </div>
    </div>
 
    <div class="mb-3 row">
      <label for="pais" class="col-sm-2 col-form-label">Email</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="pais" value="<?= htmlspecialchars($equipo->getPais()) ?>">
      </div>
    </div>

    <div class="mb-3 row">
      <label for="created_at" class="col-sm-2 col-form-label">Creat</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="created_at" value="<?= $equipo->getCreatedAt()?->format('Y-m-d H:i:s') ?? '—' ?>">
      </div>
    </div>

    <div class="mb-3 row">
      <label for="updated_at" class="col-sm-2 col-form-label">Actualitzat</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="updated_at" value="<?= $equipo->getUpdatedAt()?->format('Y-m-d H:i:s') ?? '—' ?>">
      </div>
    </div>
  </div>

  <div class="bottom-action d-flex flex-wrap gap-2 mt-4">
    <a href="<?= $baseUri ?>/equipo" class="btn btn-secondary btn-back">Tornar al llistat</a>
    <a href="<?= $baseUri ?>/equipo/<?= $equipo->getId() ?>/edit" class="btn btn-primary">Edita</a>
    <form action="<?= $baseUri ?>/equipo/<?= $equipo->getId() ?>/delete"
          method="POST"
          class="d-inline-block"
          onsubmit="return confirm('Segur que vols eliminar aquest equip?');">
      <button type="submit" class="btn btn-danger">Elimina equip</button>
    </form>
  </div>
</div>