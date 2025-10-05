<?php
require_once '../db/db.php';
include '../admin/header-admin.php';

session_start();

// Consulta para listar pagamentos com nome do trabalhador e nome do plano
$sql = "SELECT pg.id, t.nome AS trabalhador_nome, pl.nome AS plano_nome, pg.valor_pago, pg.metodo_pagamento, pg.status_pagamento, pg.data_pagamento
        FROM pagamentos pg
        LEFT JOIN trabalhadores t ON pg.trabalhador_id = t.id
        LEFT JOIN planos pl ON pg.plano_id = pl.id
        ORDER BY pg.data_pagamento DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Pagamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        @media print {
            body * { visibility: hidden; }
            #tabela-pagamentos, #tabela-pagamentos * { visibility: visible; }
            #tabela-pagamentos { position: absolute; left: 0; top: 0; width: 100%; }
        }
    </style>
</head>
<body style="min-height:100vh; display:flex; flex-direction:column;">
<main style="flex:1">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Pagamentos</h2>
            <div>
                <a href="criar-pagamento.php" class="btn btn-success me-2">
                    <i class="bi bi-plus-circle"></i> Criar Novo Pagamento
                </a>
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
            </div>
        </div>
        <div class="mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" id="filtro" class="form-control" placeholder="Pesquisar...">
                </div>
                <div class="col-md-8">
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="trabalhador" checked> Trabalhador
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="plano" checked> Plano
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="valor" checked> Valor
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="metodo" checked> Método
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="status" checked> Status
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="data" checked> Data
                    </label>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tabela-pagamentos">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th class="col-trabalhador">Trabalhador</th>
                        <th class="col-plano">Plano</th>
                        <th class="col-valor">Valor</th>
                        <th class="col-metodo">Método</th>
                        <th class="col-status">Status</th>
                        <th class="col-data">Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td class="col-trabalhador"><?= htmlspecialchars($row['trabalhador_nome'] ?? 'N/A') ?></td>
                            <td class="col-plano"><?= htmlspecialchars($row['plano_nome'] ?? 'N/A') ?></td>
                            <td class="col-valor">
                                <?php
                                $valor = $row['valor_pago'];
                                if ($valor === null || $valor === '' || !is_numeric($valor)) {
                                    echo '€ 0,00';
                                } else {
                                    echo '€ ' . number_format((float)$valor, 2, ',', '.');
                                }
                                ?>
                            </td>
                            <td class="col-metodo"><?= htmlspecialchars($row['metodo_pagamento'] ?? '-') ?></td>
                            <td class="col-status">
                                <?php if (($row['status_pagamento'] ?? '') === 'confirmado'): ?>
                                    <span class="badge bg-success">Confirmado</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($row['status_pagamento'] ?? '-') ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="col-data"><?= !empty($row['data_pagamento']) ? date('d/m/Y H:i', strtotime($row['data_pagamento'])) : '-' ?></td>
                            <td>
                                <a href="editar-pagamento.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                <form method="POST" action="eliminar-pagamento.php" class="d-inline" onsubmit="return confirm('Eliminar pagamento?')">
                                    <input type="hidden" name="pagamento_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center text-muted">Nenhum pagamento encontrado.</td></tr>
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
    const linhas = document.querySelectorAll('#tabela-pagamentos tbody tr');
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