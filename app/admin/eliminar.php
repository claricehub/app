<?php
require_once '../db/db.php';

// Verifica se o ID foi passado via GET ou POST
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Converte para inteiro para evitar SQL Injection

    // Exclui registros dependentes em pagamentos e assinaturas antes de excluir o trabalhador
    $conn->query("DELETE FROM pagamentos WHERE trabalhador_id = $id");
    $conn->query("DELETE FROM assinaturas WHERE trabalhador_id = $id");

    // Agora pode excluir o trabalhador
    $sql = "DELETE FROM trabalhadores WHERE id= $id";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "No ID provided";
}

$conn->close();

// Redireciona para a página inicial
header("Location: ../admin/admin-pag.php");
exit();
?>
