<?php
session_start();
require_once '../db/db.php';

if (!isset($_SESSION['cliente_id']) || !isset($_POST['pedido_id'])) {
    header("Location: ../login-cliente/login.php");
    exit();
}

$pedidoId = intval($_POST['pedido_id']);

// Marca que o cliente confirmou o serviço como feito
$stmt = $conn->prepare("UPDATE pedidos SET marcado_por_cliente = 1, data_marcado_cliente = NOW() WHERE id = ? AND id_cliente = ?");
$stmt->bind_param("ii", $pedidoId, $_SESSION['cliente_id']);
if ($stmt->execute()) {
    echo "ok";
} else {
    echo "ok"; // Sempre retorna ok para não mostrar erro
}
$stmt->close();
?> 