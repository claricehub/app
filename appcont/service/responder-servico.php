<?php

session_start();
require_once '../db/db.php';

if (!isset($_SESSION['trabalhador_id'])) {
  header("Location: ../login-trablhador/login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pedido_id = intval($_POST['pedido_id']);
  $acao = $_POST['acao']; // deve ser 'aceito' ou 'recusado'

  if (in_array($acao, ['aceito', 'recusado'])) {
    $stmt = $conn->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $acao, $pedido_id);
    $stmt->execute();
    $stmt->close();
  }

  header("Location: ../user/perfil-trabalhador.php");
  exit();
}
