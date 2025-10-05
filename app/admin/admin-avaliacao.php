<?php
require_once '../db/db.php';
include '../admin/header-admin.php';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Backoff - Avaliações</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    @media print {
      body * {
        visibility: hidden;
      }
      #tabela-avaliacoes, #tabela-avaliacoes * {
        visibility: visible;
      }
      #tabela-avaliacoes {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
      }
    }
  </style>
</head>
<body style="min-height:100vh; display:flex; flex-direction:column;">
<main style="flex:1">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <a href="../admin/criar-avaliacao.php" class="btn btn-success btn-lg">
        <i class="bi bi-star-fill"></i> Criar Nova Avaliação
      </a>
      <button class="btn btn-outline-primary" onclick="window.print()">
        <i class="bi bi-printer"></i> Imprimir
      </button>
    </div>

    <!-- Filtros e opções -->
    <div class="mb-4">
      <div class="row g-3 align-items-center">
        <div class="col-md-4">
          <input type="text" id="filtro" class="form-control" placeholder="Pesquisar...">
        </div>
        <div class="col-md-6">
          <label class="form-check form-check-inline">
            <input class="form-check-input coluna-toggle" type="checkbox" value="cliente" checked> Cliente
          </label>
          <label class="form-check form-check-inline">
            <input class="form-check-input coluna-toggle" type="checkbox" value="trabalhador" checked> Trabalhador
          </label>
          <label class="form-check form-check-inline">
            <input class="form-check-input coluna-toggle" type="checkbox" value="estrelas" checked> Estrelas
          </label>
          <label class="form-check form-check-inline">
            <input class="form-check-input coluna-toggle" type="checkbox" value="comentario" checked> Comentário
          </label>
          <label class="form-check form-check-inline">
            <input class="form-check-input coluna-toggle" type="checkbox" value="data" checked> Data
          </label>
        </div>
      </div>
    </div>

    <?php
    $sql = "SELECT a.id, a.estrelas, a.comentario, a.criado_em,
                   c.nome AS cliente_nome,
                   t.nome AS trabalhador_nome
            FROM avaliacao a
            JOIN clientes c ON a.cliente_id = c.id
            JOIN trabalhadores t ON a.trabalhador_id = t.id
            ORDER BY a.criado_em DESC";

    $result = $conn->query($sql);
    ?>

    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="tabela-avaliacoes">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th class="col-cliente">Cliente</th>
            <th class="col-trabalhador">Trabalhador</th>
            <th class="col-estrelas">Estrelas</th>
            <th class="col-comentario">Comentário</th>
            <th class="col-data">Data</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()):
              $id         = htmlspecialchars($row["id"]);
              $cliente    = htmlspecialchars($row["cliente_nome"]);
              $trabalhador= htmlspecialchars($row["trabalhador_nome"]);
              $comentario = nl2br(htmlspecialchars($row["comentario"] ?? ''));
              $data       = date('d/m/Y H:i', strtotime($row["criado_em"]));
              $estrelas   = (int) $row["estrelas"];
            ?>
            <tr>
              <td><?= $id ?></td>
              <td class="col-cliente"><?= $cliente ?></td>
              <td class="col-trabalhador"><?= $trabalhador ?></td>
              <td class="col-estrelas text-warning">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <i class="bi bi-star<?= $i <= $estrelas ? '-fill' : '' ?>"></i>
                <?php endfor; ?>
                <span class="text-muted small">(<?= $estrelas ?>)</span>
              </td>
              <td class="col-comentario"><?= $comentario ?></td>
              <td class="col-data"><?= $data ?></td>
              <td>
<a href="../admin/editar-avaliacao.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                <form method="POST" action="eliminar-avaliacao.php" class="d-inline" onsubmit="return confirm('Eliminar avaliação?')">
                  <input type="hidden" name="avaliacao_id" value="<?= $id ?>">
                  <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted">Nenhuma avaliação registrada.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<?php include 'footer-admin.php'; ?>

<script>
document.getElementById('filtro').addEventListener('input', function () {
  const termo = this.value.toLowerCase();
  const linhas = document.querySelectorAll('#tabela-avaliacoes tbody tr');
  linhas.forEach(linha => {
    const texto = linha.textContent.toLowerCase();
    linha.style.display = texto.includes(termo) ? '' : 'none';
  });
});

document.querySelectorAll('.coluna-toggle').forEach(checkbox => {
  checkbox.addEventListener('change', function () {
    const col = this.value;
    const visivel = this.checked;
    document.querySelectorAll('.col-' + col).forEach(el => {
      el.style.display = visivel ? '' : 'none';
    });
    const th = document.querySelector('th.col-' + col);
    if (th) th.style.display = visivel ? '' : 'none';
  });
});
</script>
</body>
</html>
