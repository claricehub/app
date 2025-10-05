<?php
session_start();
require_once '../db/db.php'; // Caminho correto para o banco de dados

// Verifica se o cliente está logado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: ../login-cliente/login-cliente.php");
    exit();
}

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedido_id = intval($_POST['pedido_id']);

    // Verifica se o pedido pertence ao cliente logado
    $stmt = $conn->prepare("SELECT id FROM pedidos WHERE id = ? AND id_cliente = ?");
    $stmt->bind_param("ii", $pedido_id, $_SESSION['cliente_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $pedido = $result->fetch_assoc();
    $stmt->close();

    // Se o pedido for válido, atualiza o status para 'cancelado'
    if ($pedido) {
        $stmt = $conn->prepare("UPDATE pedidos SET status = 'cancelado' WHERE id = ?");
        $stmt->bind_param("i", $pedido_id);
        $stmt->execute();
        $stmt->close();
    }

    // Redireciona corretamente para o perfil do cliente
    header("Location: ../user/perfil-cliente.php?cancelado=1");
    exit();
}
?>
