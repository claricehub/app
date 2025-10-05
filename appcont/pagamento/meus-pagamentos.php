<?php
session_start();
require_once '../db/db.php';

if (!isset($_SESSION['trabalhador_id'])) {
  header("Location: ../login-trabalhador/login.php");
  exit();
}

$trabalhador_id = $_SESSION['trabalhador_id'];

// Buscar pagamentos realizados
$stmt = $conn->prepare("
  SELECT pg.*, pl.nome AS plano_nome, pl.destaque_duracao
  FROM pagamentos pg
  JOIN planos pl ON pg.plano_id = pl.id
  WHERE pg.trabalhador_id = ?
  ORDER BY pg.data_pagamento DESC
");
$stmt->bind_param("i", $trabalhador_id);
$stmt->execute();
$result = $stmt->get_result();
$pagamentos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Meus Pagamentos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .card-header { background-color: #0d6efd; color: #fff; }
    .card { border: none; }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="card shadow">
      <div class="card-header">
        <h3 class="mb-0">💰 Histórico de Pagamentos</h3>
      </div>
      <div class="card-body">
        <?php if (!empty($pagamentos)): ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Plano</th>
                  <th>Valor Pago</th>
                  <th>Duração</th>
                  <th>Método</th>
                  <th>Status</th>
                  <th>Data</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($pagamentos as $pg): ?>
                  <tr>
                    <td><?= ucfirst($pg['plano_nome']) ?></td>
                    <td>R$ <?= number_format($pg['valor_pago'], 2, ',', '.') ?></td>
                    <td><?= $pg['destaque_duracao'] ?> dias</td>
                    <td><?= htmlspecialchars($pg['metodo_pagamento'] ?? 'Manual') ?></td>
                    <td>
                      <?php if ($pg['status_pagamento'] === 'confirmado'): ?>
                        <span class="badge bg-success">Confirmado</span>
                      <?php else: ?>
                        <span class="badge bg-secondary"><?= htmlspecialchars($pg['status_pagamento']) ?></span>
                      <?php endif; ?>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($pg['data_pagamento'])) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Nenhum pagamento registrado ainda.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
