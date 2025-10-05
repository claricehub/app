<?php
session_start();
require_once '../db/db.php';

if (!isset($_SESSION['trabalhador_id'])) {
  header("Location: ../login-trabalhador/login.php");
  exit();
}

$id = $_SESSION['trabalhador_id'];

// Buscar histórico de pedidos concluídos (status = 'feito' OU marcado_por_trabalhador = 1)
$stmt = $conn->prepare("
  SELECT p.*, c.nome AS cliente_nome, c.telefone AS cliente_telefone
  FROM pedidos p
  JOIN clientes c ON p.id_cliente = c.id
  WHERE p.id_trabalhador = ? AND (p.status = 'feito' OR p.marcado_por_trabalhador = 1)
  ORDER BY 
    CASE 
      WHEN p.data_marcado_trabalhador IS NOT NULL THEN p.data_marcado_trabalhador
      ELSE p.data_pedido
    END DESC
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$historico = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Histórico de Trabalhos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card-header {
      background-color: #0d6efd;
      color: #fff;
    }
    .card {
      border: none;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="mb-0">📜 Histórico de Trabalhos Concluídos</h2>
      <a href="../user/perfil-trabalhador.php" class="btn btn-outline-primary">
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
                  <th>Cliente</th>
                  <th>Telefone</th>
                  <th>Mensagem</th>
                  <th>Data do Pedido</th>
                  <th>Data Concluído</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($historico as $pedido): ?>
                  <tr>
                    <td><strong><?= htmlspecialchars($pedido['cliente_nome']) ?></strong></td>
                    <td><?= htmlspecialchars($pedido['cliente_telefone']) ?></td>
                    <td><?= nl2br(htmlspecialchars($pedido['mensagem'])) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                    <td>
                      <?php
                        $dataConcluido = $pedido['data_marcado_trabalhador'] ?? null;
                        if (!$dataConcluido && $pedido['status'] === 'feito') {
                          $dataConcluido = $pedido['data_pedido'];
                        }
                      ?>
                      <span class="badge bg-success">
                        <?= $dataConcluido ? date('d/m/Y H:i', strtotime($dataConcluido)) : '-' ?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Nenhum trabalho concluído até o momento.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
