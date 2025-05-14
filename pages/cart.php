<?php
include '../funciones/auth.php';
include '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['id_usuario'])) {
  echo '<div class="container text-center mt-5"><p class="text-danger">Debes iniciar sesión para ver tu carrito.</p></div>';
  include '../includes/footer.php';
  exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener plushies del carrito
$query = "
SELECT c.id_carrito, c.cantidad, p.nombre, p.precio, p.fotos
FROM Carrito c
JOIN Productos p ON c.id_plush = p.id_producto
WHERE c.id_usuario = $id_usuario
";
$resultado = mysqli_query($conn, $query);

$total = 0;
?>

<div class="container">
  <h1 class="mb-4 text-center">Tu carrito de plushies</h1>

  <?php if (mysqli_num_rows($resultado) > 0): ?>
    <table class="table table-bordered align-middle text-center">
      <thead class="table-light">
        <tr>
          <th>Imagen</th>
          <th>Nombre</th>
          <th>Precio</th>
          <th>Cantidad</th>
          <th>Subtotal</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($item = mysqli_fetch_assoc($resultado)): 
          $subtotal = $item['precio'] * $item['cantidad'];
          $total += $subtotal;
          $imagen = explode(',', $item['fotos'])[0]; // Si hay varias imágenes separadas por comas
        ?>
          <tr>
            <td><img src="<?= $imagen ?>" alt="<?= $item['nombre'] ?>" width="60"></td>
            <td><?= $item['nombre'] ?></td>
            <td>$<?= number_format($item['precio'], 2) ?> USD</td>
            <td>
              <form method="POST" action="../funciones/updateCart.php" class="d-flex justify-content-center">
                <input type="hidden" name="id_carrito" value="<?= $item['id_carrito'] ?>">
                <input type="number" name="cantidad" min="1" value="<?= $item['cantidad'] ?>" class="form-control w-50 me-2">
                <button type="submit" class="btn btn-sm btn-outline-primary">Actualizar</button>
              </form>
            </td>
            <td>$<?= number_format($subtotal, 2) ?> USD</td>
            <td>
              <form method="POST" action="../funcions/deleteCart.php">
                <input type="hidden" name="id_carrito" value="<?= $item['id_carrito'] ?>">
                <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <div class="text-end">
      <h4>Total: $<?= number_format($total, 2) ?> USD</h4>
      <form action="../funciones/checkout.php" method="POST">
        <button type="submit" class="btn btn-success">Finalizar compra</button>
      </form>
    </div>
  
  <?php else: ?>
    <p class="text-center text-muted">Tu carrito está vacío.</p>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>