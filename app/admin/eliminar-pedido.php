<?php
require_once '../db/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Segurança básica

    $sql = "DELETE FROM pedidos WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Sucesso — redireciona
        header("Location: ../admin-cliente/admin-pedidos.php?msg=deleted");
        exit();
    } else {
        // Erro — redireciona com erro
        header("Location: ../admin-cliente/admin-pedidos.php?erro=1");
        exit();
    }
} else {
    // Sem ID — redireciona com erro
    header("Location: ../admin-cliente/admin-pedidos.php?erro=sem_id");
    exit();
}

$conn->close();
?>
