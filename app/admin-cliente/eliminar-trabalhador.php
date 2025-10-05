<?php
require_once '../db/db.php';

// Verifica se o ID foi passado via GET
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Converte para inteiro para evitar SQL Injection

    // Primeiro remove as profissões associadas
    $sql_profissoes = "DELETE FROM trabalhador_profissao WHERE trabalhador_id = $id";
    $conn->query($sql_profissoes);

    // Remove as assinaturas associadas
    $sql_assinaturas = "DELETE FROM assinaturas WHERE trabalhador_id = $id";
    $conn->query($sql_assinaturas);

    // Remove os pagamentos associados
    $sql_pagamentos = "DELETE FROM pagamentos WHERE trabalhador_id = $id";
    $conn->query($sql_pagamentos);

    // Remove as avaliações associadas
    $sql_avaliacoes = "DELETE FROM avaliacao WHERE trabalhador_id = $id";
    $conn->query($sql_avaliacoes);

    // Remove os pedidos associados
    $sql_pedidos = "DELETE FROM pedidos WHERE id_trabalhador = $id";
    $conn->query($sql_pedidos);

    // Remove as fotos de serviço associadas
    $sql_fotos = "DELETE FROM servico_imagens WHERE id_trabalhador = $id";
    $conn->query($sql_fotos);

    // Finalmente remove o trabalhador
    $sql = "DELETE FROM trabalhadores WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Trabalhador eliminado com sucesso";
    } else {
        echo "Erro ao eliminar trabalhador: " . $conn->error;
    }
} else {
    echo "ID não fornecido";
}

$conn->close();

// Redireciona para a página de listagem
header("Location: ../admin-cliente/admin-trabalhadores.php");
exit();
?> 