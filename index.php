<?php
require_once 'php/db.php'; // carga $pdo

// Consultar bodegas
$stmt = $pdo->query("SELECT id, nombre FROM bodegas ORDER BY nombre");
$bodegas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consultar sucursales
$stmt = $pdo->query("SELECT id, nombre, bodega_id FROM sucursales ORDER BY nombre");
$sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consultar monedas
$stmt = $pdo->query("SELECT id, nombre FROM monedas ORDER BY nombre");
$monedas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consultar materiales
$stmt = $pdo->query("SELECT id, nombre FROM materiales ORDER BY nombre");
$materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Formulario de Producto</title>
  <link rel="stylesheet" href="styles/style.css" />
</head>
<body>

  <div class="form-container">
    <h1>Formulario de Producto</h1>

    <form id="formProducto">

      <div class="row">
        <div class="form-group">
          <label for="codigo">Código</label>
          <input type="text" id="codigo" name="codigo" />
        </div>

        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" id="nombre" name="nombre" />
        </div>
      </div>

      <div class="row">
        <div class="form-group">
          <label for="bodega">Bodega</label>
          <select id="bodega" name="bodega">
            <option value=""></option>
            <?php foreach ($bodegas as $bodega): ?>
              <option value="<?= htmlspecialchars($bodega['id']) ?>">
                <?= htmlspecialchars($bodega['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="sucursal">Sucursal</label>
          <select id="sucursal" name="sucursal" disabled>
            <option value=""></option>
            <!-- Opciones de sucursal dinamicas l seleccionar bodega -->
          </select>
        </div>
      </div>

      <div class="row">
        <div class="form-group">
          <label for="moneda">Moneda</label>
          <select id="moneda" name="moneda">
            <option value=""></option>
            <?php foreach ($monedas as $moneda): ?>
              <option value="<?= htmlspecialchars($moneda['id']) ?>">
                <?= htmlspecialchars($moneda['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="precio">Precio</label>
          <input type="text" id="precio" name="precio" />
        </div>
      </div>

      <div class="form-group">
        <label>Material del Producto</label>
        <div class="checkbox-group">
          <?php foreach ($materiales as $material): ?>
            <label>
              <input type="checkbox" name="materiales[]" value="<?= htmlspecialchars($material['id']) ?>" />
              <?= htmlspecialchars($material['nombre']) ?>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="form-group descripcion-group">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="4"></textarea>
      </div>

      <div class="center">
        <button type="submit" id="guardarBtn">Guardar Producto</button>
      </div>

    </form>
  </div>

<script>
// JS para cargar sucursales segun bodega seleccionada

const sucursales = <?= json_encode($sucursales) ?>;
const bodegaSelect = document.getElementById('bodega');
const sucursalSelect = document.getElementById('sucursal');

bodegaSelect.addEventListener('change', () => {
  const selectedBodega = bodegaSelect.value;

  // Limpiar opciones
  sucursalSelect.innerHTML = '<option value=""></option>';

  if (!selectedBodega) {
    sucursalSelect.disabled = true;
    return;
  }

  // Filtrar sucursales para la bodega seleccionada
  const filtered = sucursales.filter(s => s.bodega_id == selectedBodega);

  filtered.forEach(s => {
    const option = document.createElement('option');
    option.value = s.id;
    option.textContent = s.nombre;
    sucursalSelect.appendChild(option);
  });

  sucursalSelect.disabled = false;
});
</script>

</body>
</html>
