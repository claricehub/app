<?php
session_start();
require_once '../db/db.php';

if (!isset($_SESSION['cliente_id'])) {
  header("Location: ../login-cliente/login.php");
  exit();
}

$clienteId = $_SESSION['cliente_id'];

// Buscar histórico de pedidos marcados como concluídos (status = 'feito')
$stmt = $conn->prepare("
  SELECT p.*, t.nome AS trabalhador_nome, t.email AS trabalhador_email, t.telefone AS trabalhador_telefone, t.titulo, t.texto1
  FROM pedidos p
  JOIN trabalhadores t ON p.id_trabalhador = t.id
  WHERE p.id_cliente = ? AND p.status = 'feito'
  ORDER BY p.data_marcado_trabalhador DESC
");
$stmt->bind_param("i", $clienteId);
$stmt->execute();
$result = $stmt->get_result();
$historico = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Histórico de Serviços Contratados</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card-header { background-color: #0d6efd; color: #fff; }
    .card { border: none; }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">Histórico de Serviços Concluídos</h2>
      <a href="perfil-cliente.php" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left"></i> Voltar ao Perfil
      </a>
    </div>
    <div class="card shadow">
      <div class="card-body">
        <?php if (!empty($historico)): ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Trabalhador</th>
                  <th>Título</th>
                  <th>Mensagem</th>
                  <th>Telefone</th>
                  <th>Email</th>
                  <th>Data do Pedido</th>
                  
                </tr>
              </thead>
              <tbody>
                <?php foreach ($historico as $pedido): ?>
                  <tr>
                    <td><strong><?= htmlspecialchars($pedido['trabalhador_nome']) ?></strong></td>
                    <td><?= htmlspecialchars($pedido['titulo']) ?></td>
                    <td><?= nl2br(htmlspecialchars($pedido['mensagem'])) ?></td>
                    <td><?= htmlspecialchars($pedido['trabalhador_telefone']) ?></td>
                    <td><?= htmlspecialchars($pedido['trabalhador_email']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                    <td>
                      
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Nenhum serviço concluído até o momento.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
