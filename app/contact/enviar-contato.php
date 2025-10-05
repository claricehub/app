<?php
session_start();
require_once '../db/db.php';

// Verifica se o cliente está logado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: ../login-cliente/login.php");
    exit();
}

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_SESSION['cliente_id'];
    $trabalhador_id = intval($_POST['trabalhador_id']);
    $mensagem = trim($_POST['mensagem']);

    // Verificação básica
    if ($trabalhador_id && $mensagem !== '') {
        $stmt = $conn->prepare("INSERT INTO pedidos (id_cliente, id_trabalhador, mensagem, status, data_pedido) VALUES (?, ?, ?, 'pendente', NOW())");
        $stmt->bind_param("iis", $cliente_id, $trabalhador_id, $mensagem);
        $stmt->execute();
        $stmt->close();

        // Redireciona de volta ao perfil do trabalhador com mensagem de sucesso
        header("Location: ../user/perfil-trabalhador-para-o-cliente.php?id=$trabalhador_id&sucesso=1");
        exit();
    } else {
        echo "Dados inválidos.";
    }
}
?>
