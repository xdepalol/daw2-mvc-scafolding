<?php
/**
 * Partial: Formulari d'equip
 * Variables:
 *   - $action  (string) → URL d'enviament del formulari
 *   - $button  (string) → Text del botó
 *   - $equipo  (App\Model\Equipo|null)
 */
?>
<form action="<?= $action ?>" method="POST">

  <div class="mb-3 row">
    <label for="nombre" class="col-sm-2 col-form-label">Nom de l’equip</label>
    <div class="col-sm-10">
        <input type="text" name="nombre" id="nombre" class="form-control"
            value="<?= htmlspecialchars($equipo?->getNombre() ?? '') ?>"
            required maxlength="100">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="ciudad" class="col-sm-2 col-form-label">Ciutat</label>
    <div class="col-sm-10">
        <input type="text" name="ciudad" id="ciudad" class="form-control"
            value="<?= htmlspecialchars($equipo?->getCiudad() ?? '') ?>"
            required maxlength="100">
    </div>
  </div>

  <div class="mb-3 row">
    <label for="pais" class="col-sm-2 col-form-label">País</label>
    <div class="col-sm-10">
        <input type="text" name="pais" id="pais" class="form-control"
            value="<?= htmlspecialchars($equipo?->getPais() ?? '') ?>"
            required maxlength="100">
    </div>
  </div>

  <div class="d-flex justify-content-end mt-4">
    <button type="submit" class="btn btn-primary">
      <?= htmlspecialchars($button) ?>
    </button>
  </div>
</form>