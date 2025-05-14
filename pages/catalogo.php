<?php
include '../funciones/auth.php';
include '../includes/db.php';
include '../includes/header.php';

// Obtener todos los plushies
$query = "SELECT * FROM Productos";
$resultado = mysqli_query($conn, $query);
?>

<div class="container">
  <h1 class="mb-4 text-center">Catálogo de Plushies</h1>

  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php while ($plush = mysqli_fetch_assoc($resultado)): 
      $imagen = explode(',', $plush['fotos'])[0]; // Si hay varias fotos, usamos la primera
    ?>
      <div class="col">
        <div class="card h-100 product-card">
          <img src="<?= htmlspecialchars($imagen) ?>" class="card-img-top" alt="<?= htmlspecialchars($plush['nombre']) ?>">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($plush['nombre']) ?></h5>
            <p class="card-text"><strong>$<?= number_format($plush['precio'], 2) ?> USD</strong></p>
            <p class="card-text"><?= htmlspecialchars($plush['descripcion']) ?></p>
          </div>
          <div class="card-footer">
            <?php if (isset($_SESSION['id_usuario'])): ?>
              <form method="POST" action="add_to_cart.php" class="d-flex justify-content-between">
                <input type="hidden" name="id_plush" value="<?= $plush['id_producto'] ?>">
                <input type="number" name="cantidad" min="1" max="<?= $plush['cantidad_en_almacen'] ?>" value="1" class="form-control w-25 me-2">
                <button type="submit" class="btn btn-dark">Agregar al carrito</button>
              </form>
            <?php else: ?>
              <p class="text-muted text-center">Inicia sesión para comprar</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
