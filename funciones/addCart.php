


<?php


include '../funciones/auth.php';


include '../includes/db.php';





if (!isset($_SESSION['id_usuario'])) {


  header("Location: login.php");


  exit;


}





$id_usuario = $_SESSION['id_usuario'];


$id_plush = $_POST['id_plush'];


$cantidad = $_POST['cantidad'];





// ver si ya existe en el carrito


$stmt = mysqli_prepare($conn, "SELECT cantidad FROM carrito WHERE id_usuario = ? AND id_plush = ?");


mysqli_stmt_bind_param($stmt, "ii", $id_usuario, $id_plush);


mysqli_stmt_execute($stmt);


mysqli_stmt_store_result($stmt);





if (mysqli_stmt_num_rows($stmt) > 0) {


  // si a existe, actualizar cantidad


  mysqli_stmt_close($stmt);


  $stmt = mysqli_prepare($conn, "UPDATE carrito SET cantidad = cantidad + ? WHERE id_usuario = ? AND id_plush = ?");


  mysqli_stmt_bind_param($stmt, "iii", $cantidad, $id_usuario, $id_plush);


  mysqli_stmt_execute($stmt);


} else {


  mysqli_stmt_close($stmt);


  $stmt = mysqli_prepare($conn, "INSERT INTO carrito (id_usuario, id_plush, cantidad) VALUES (?, ?, ?)");


  mysqli_stmt_bind_param($stmt, "iii", $id_usuario, $id_plush, $cantidad);


  mysqli_stmt_execute($stmt);


}





mysqli_stmt_close($stmt);


mysqli_close($conn);


// regresar catalogo


header("Location: catalogo.php?success=1");


exit;