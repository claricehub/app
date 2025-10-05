<?php
require_once '../db/db.php';
include '../admin/header-admin.php';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #tabela-clientes, #tabela-clientes * {
                visibility: visible;
            }
            #tabela-clientes {
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
            <a href="../admin-cliente/criar-cliente.php" class="btn btn-success btn-lg">
                <i class="bi bi-person-plus-fill"></i> Criar Novo Cliente
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
                        <input class="form-check-input coluna-toggle" type="checkbox" value="nome" checked> Nome
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="email" checked> Email
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="telefone" checked> Telefone
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="morada" checked> Morada
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="contribuinte" checked> Número de Contribuinte
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
        $sql = "SELECT * FROM clientes ORDER BY nome ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tabela-clientes">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th class="col-nome">Nome</th>
                            <th class="col-email">Email</th>
                            <th class="col-telefone">Telefone</th>
                            <th class="col-morada">Morada</th>
                            <th class="col-contribuinte">Número de Contribuinte</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()):
                            $id = htmlspecialchars($row["id"]);
                            $nome = htmlspecialchars($row["nome"]);
                            $email = htmlspecialchars($row["email"]);
                            $telefone = htmlspecialchars($row["telefone"]);
                            $morada = htmlspecialchars($row["morada"]);
                            $contribuinte = htmlspecialchars($row["contribuinte"]);
                        ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td class="col-nome"><?= $nome ?></td>
                            <td class="col-email"><?= $email ?></td>
                            <td class="col-telefone"><?= $telefone ?></td>
                            <td class="col-morada"><?= $morada ?></td>
                            <td class="col-contribuinte"><?= $contribuinte ?></td>
                            <td>
                                <a href="../admin-cliente/eliminar-cliente.php?id=<?= $id ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                <a href="../admin-cliente/editar-cliente.php?id=<?= $id ?>" class="btn btn-sm btn-primary">Editar</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Nenhum cliente encontrado.</p>
        <?php endif;

        $stmt->close();
        $conn->close();
        ?>
    </div>
</main>

<?php include '../admin/footer-admin.php'; ?>

<script>
// Filtro de pesquisa
document.getElementById('filtro').addEventListener('input', function () {
    const termo = this.value.toLowerCase();
    const linhas = document.querySelectorAll('#tabela-clientes tbody tr');

    linhas.forEach(linha => {
        const texto = linha.textContent.toLowerCase();
        linha.style.display = texto.includes(termo) ? '' : 'none';
    });
});

// Mostrar/ocultar colunas
document.querySelectorAll('.coluna-toggle').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const colClass = 'col-' + this.value;
        const visivel = this.checked;

        document.querySelectorAll('.' + colClass).forEach(el => {
            el.style.display = visivel ? '' : 'none';
        });
    });
});
</script>

</body>
</html>
