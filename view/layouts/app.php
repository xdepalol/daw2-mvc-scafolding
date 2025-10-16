<?php
  $includeDatatable = $includeDatatable ?? false;
?>
<!DOCTYPE html>
<html lang="ca">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'App') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <?php if ($includeDatatable) { ?>
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <?php } ?>
  <link rel="stylesheet" href="<?= asset_url('css/app.css') ?>">
</head>

<body>

  <!-- Navbar -->
  <?php include VIEW_PATH . '/partials/navbar.php'; ?>

  <!-- Contingut principal -->
  <main class="flex-grow-1 py-4">
    <div class="container">
      <?php
        // Contingut dinàmic de cada vista
        if (isset($content)) {
            include $content;
        } else {
            echo "<div class='alert alert-warning'>Cap vista especificada.</div>";
        }
      ?>
    </div>
  </main>

<?php if ($includeDatatable) { ?>
  <script defer src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script defer src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script defer src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<?php } ?>
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php if ($includeDatatable) { ?>
  <script defer>
  document.addEventListener('DOMContentLoaded', function () {
    const tables = document.querySelectorAll('[data-dt]');
    tables.forEach(tbl => window.jQuery(tbl).DataTable());
  });
  </script>
<?php } ?>

  <!-- Footer -->
  <footer class="bg-dark text-light py-3 mt-auto small">
    <div class="container-lg">
      &copy; <?= date('Y') ?> La Lliga. Tots els drets reservats.
    </div>
  </footer>
</body>
</html>
