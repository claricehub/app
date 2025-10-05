<?php
session_start();
require_once '../db/db.php';

if (!isset($_SESSION['trabalhador_id'])) {
  header("Location: ../login-trabalhador/login.php");
  exit();
}

$id = $_SESSION['trabalhador_id'];

$stmt = $conn->prepare("
  SELECT p.*, c.nome AS cliente_nome, c.telefone AS cliente_telefone
  FROM pedidos p
  JOIN clientes c ON p.id_cliente = c.id
  WHERE p.id_trabalhador = ? AND p.status = 'feito'
  ORDER BY p.data_pedido DESC
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$feitos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Trabalhos Feitos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h3 class="mb-4">✅ Trabalhos Finalizados</h3>
    <?php if (!empty($feitos)): ?>
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr>
            <th>Cliente</th>
            <th>Telefone</th>
            <th>Mensagem</th>
            <th>Data</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($feitos as $pedido): ?>
            <tr>
              <td><?= htmlspecialchars($pedido['cliente_nome']) ?></td>
              <td><?= htmlspecialchars($pedido['cliente_telefone']) ?></td>
              <td><?= nl2br(htmlspecialchars($pedido['mensagem'])) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-info text-center">Nenhum trabalho marcado como “feito” ainda.</div>
    <?php endif; ?>
  </div>
</body>
</html>
