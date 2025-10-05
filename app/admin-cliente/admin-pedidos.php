<?php
require_once '../db/db.php';
include '../admin/header-admin.php';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - Admin</title>
    <link rel="stylesheet" href="../css/corpo.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #tabela-pedidos, #tabela-pedidos * {
                visibility: visible;
            }
            #tabela-pedidos {
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
    
    <a href="../admin-cliente/criar-pedido.php" class="btn btn-success btn-lg">
        <i class="bi bi-person-plus-fill"></i> Criar Nova Encomenda
    </a>
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
                        <input class="form-check-input coluna-toggle" type="checkbox" value="mensagem" checked> Mensagem
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="status" checked> Estado
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="data" checked> Data
                    </label>
                </div>
                <div class="col-md-2 text-end">
                    <button class="btn btn-outline-primary w-100" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div>
            </div>
        </div>

        <?php
        $sql = "SELECT p.id, c.nome AS cliente, t.nome AS trabalhador, p.mensagem, p.status, p.data_pedido
                FROM pedidos p
                LEFT JOIN clientes c ON p.id_cliente = c.id
                LEFT JOIN trabalhadores t ON p.id_trabalhador = t.id
                ORDER BY p.data_pedido DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tabela-pedidos">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th class="col-cliente">Cliente</th>
                            <th class="col-trabalhador">Trabalhador</th>
                            <th class="col-mensagem">Mensagem</th>
                            <th class="col-status">Estado</th>
                            <th class="col-data">Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()):
                            $id = htmlspecialchars($row["id"]);
                            $cliente = htmlspecialchars($row["cliente"] ?? 'Não informado');
                            $trabalhador = htmlspecialchars($row["trabalhador"] ?? 'Não informado');
                            $mensagem = htmlspecialchars($row["mensagem"]);
                            $status = htmlspecialchars($row["status"]);
                            $data = date('d/m/Y H:i', strtotime($row["data_pedido"]));
                        ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td class="col-cliente"><?= $cliente ?></td>
                            <td class="col-trabalhador"><?= $trabalhador ?></td>
                            <td class="col-mensagem"><?= $mensagem ?></td>
                            <td class="col-status"><?= ucfirst($status) ?></td>
                            <td class="col-data"><?= $data ?></td>
                            <td>
    <a href='../admin/eliminar-pedido.php?id=<?= $id ?>' class='btn btn-sm btn-danger'>Eliminar</a>
    <a href='../admin-cliente/editar-pedido.php?id=<?= $id ?>' class='btn btn-sm btn-primary'>Editar</a>
 

</td>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Nenhum pedido encontrado.</p>
        <?php endif;

        $stmt->close();
        $conn->close();
        ?>
    </div>
</main>

<?php include '../admin/footer-admin.php'; ?>

<script>
document.getElementById('filtro').addEventListener('input', function () {
    const termo = this.value.toLowerCase();
    const linhas = document.querySelectorAll('#tabela-pedidos tbody tr');

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
