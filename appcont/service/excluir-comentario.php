<?php
session_start();
require_once '../db/db.php';

if (!isset($_SESSION['trabalhador_id'])) {
    header('Location: ../login-trabalhador/login.php');
    exit();
}

$trabalhador_id = $_SESSION['trabalhador_id'];
$criado_em = isset($_POST['criado_em']) ? $_POST['criado_em'] : null;

if (!$criado_em) {
    header('Location: ../user/perfil-trabalhador.php?erro=1');
    exit();
}

// Verificar se o trabalhador tem plano Premium ativo
$hoje = date('Y-m-d');
$stmt = $conn->prepare('SELECT plano_id FROM assinaturas WHERE trabalhador_id = ? AND plano_id = 3 AND ativo = 1 AND fim >= ? LIMIT 1');
$stmt->bind_param('is', $trabalhador_id, $hoje);
$stmt->execute();
$stmt->store_result();
$isPremium = $stmt->num_rows > 0;
$stmt->close();

if (!$isPremium) {
    header('Location: ../user/perfil-trabalhador.php?erro=2');
    exit();
}

// Excluir o comentário
$stmt = $conn->prepare('DELETE FROM avaliacao WHERE trabalhador_id = ? AND criado_em = ?');
$stmt->bind_param('is', $trabalhador_id, $criado_em);
$stmt->execute();
$stmt->close();

header('Location: ../user/perfil-trabalhador.php?sucesso=1');
exit(); 