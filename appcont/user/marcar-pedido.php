<?php
session_start();
require_once '../db/db.php';

if (isset($_POST['pedido_id']) && isset($_SESSION['trabalhador_id'])) {
  $id = $_POST['pedido_id'];

  $stmt = $conn->prepare("UPDATE pedidos SET status = 'feito' WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
}

header("Location: ../user/perfil-trabalhador.php");
exit();
?>
