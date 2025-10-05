<?php
require_once '../db/db.php';

// Verifica se o ID foi passado via GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Segurança básica contra SQL Injection

    // Executa o DELETE
    $sql = "DELETE FROM clientes WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Sucesso — redireciona com mensagem
        header("Location: ../admin-cliente/admin-cliente-pag.php?msg=deleted");
        exit();
    } else {
        // Erro — redireciona com erro
        header("Location: ../admin-cliente/admin-cliente-pag.php?erro=1");
        exit();
    }
} else {
    // Sem ID — redireciona com erro
    header("Location: ../admin-cliente/admin-cliente-pag.php?erro=sem_id");
    exit();
}

$conn->close();
?>

