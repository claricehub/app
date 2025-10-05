<?php
require_once '../db/db.php';
session_start();

if (isset($_SESSION['trabalhador_id']) && isset($_POST['pedido_id'])) {
  $pedidoId = $_POST['pedido_id'];

  // Marca que o trabalhador concluiu o serviço e atualiza o status para 'feito'
  $stmt = $conn->prepare("UPDATE pedidos SET marcado_por_trabalhador = 1, data_marcado_trabalhador = NOW(), status = 'feito' WHERE id = ? AND id_trabalhador = ?");
  $stmt->bind_param("ii", $pedidoId, $_SESSION['trabalhador_id']);
  $stmt->execute();
  $stmt->close();
}

header("Location: ../user/historico.php");
exit();
?>
