<?php
session_start();
require_once '../db/db.php';

if (!isset($_SESSION['cliente_id'])) {
    http_response_code(403);
    exit('Acesso negado');
}

if (!isset($_POST['pedido_id'])) {
    http_response_code(400);
    exit('Pedido inválido');
}

$pedido_id = (int)$_POST['pedido_id'];
$cliente_id = (int)$_SESSION['cliente_id'];

// Só permite excluir pedidos do próprio cliente
$stmt = $conn->prepare("DELETE FROM pedidos WHERE id = ? AND id_cliente = ?");
$stmt->bind_param("ii", $pedido_id, $cliente_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "ok";
} else {
    http_response_code(400);
    echo "Erro ao eliminar pedido";
}
$stmt->close(); 