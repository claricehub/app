<?php
require_once '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['avaliacao_id'])) {
  $id = (int) $_POST['avaliacao_id'];
  $conn->query("DELETE FROM avaliacao WHERE id = $id");
  // Mensagem de sucesso simples (opcional)
  // echo "<script>alert('Avaliação eliminada com sucesso!');</script>";
}

header("Location: avaliacoes-admin.php");
exit;
