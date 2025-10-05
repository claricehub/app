<?php
require_once '../db/db.php';
include '../admin/header-admin.php';

session_start();



?>



<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoff</title>
    <link rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #tabela-trabalhadores, #tabela-trabalhadores * {
                visibility: visible;
            }
            #tabela-trabalhadores {
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
    <a href="../admin/criar-trabalhador.php" class="btn btn-success btn-lg">
        <i class="bi bi-person-plus-fill"></i> Criar Novo Trabalhador
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
                        <input class="form-check-input coluna-toggle" type="checkbox" value="profissao" checked> Profissão
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="zona" checked> Zona
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
        $sql = "SELECT t.email, t.id, t.nome, t.telefone, GROUP_CONCAT(c.categoria SEPARATOR ', ') AS profissoes, z.zona AS zona,
    COALESCE(pl.nome, 'Free') AS pacote
    FROM trabalhadores t
    LEFT JOIN trabalhador_profissao tp ON t.id = tp.trabalhador_id
    LEFT JOIN categorias c ON tp.profissao_id = c.id
    LEFT JOIN zona z ON t.zona = z.id
    LEFT JOIN (
        SELECT a.trabalhador_id, p.nome
        FROM assinaturas a
        INNER JOIN planos p ON a.plano_id = p.id
        WHERE a.ativo = 1 AND a.fim >= CURDATE()
    ) pl ON t.id = pl.trabalhador_id
    GROUP BY t.id
    ORDER BY t.nome ASC";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tabela-trabalhadores">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th class="col-nome">Nome</th>
                            <th class="col-email">Email</th>
                            <th class="col-telefone">Telefone</th>
                            <th class="col-profissao">Profissão</th>
                            <th class="col-zona">Zona</th>
                            <th class="col-pacote">Pacote</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()):
                            $id = htmlspecialchars($row["id"]);
                            $nome = htmlspecialchars($row["nome"]);
                            $email = htmlspecialchars($row["email"]);
                            $telefone = htmlspecialchars($row["telefone"] ?? 'Não definido');
                            $profissoes = htmlspecialchars($row["profissoes"] ?? 'Não definida');
                            $zona = htmlspecialchars($row["zona"] ?? 'Não definida');
                            $pacote = htmlspecialchars($row['pacote'] ?? 'Free');
                        ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td class="col-nome"><?= $nome ?></td>
                            <td class="col-email"><?= $email ?></td>
                            <td class="col-telefone"><?= $telefone ?></td>
                            <td class="col-profissao"><?= $profissoes ?></td>
                            <td class="col-zona"><?= $zona ?></td>
                            <td class="col-pacote"><?= $pacote ?></td>
                            <td>
                                <a href='../admin/eliminar.php?id=<?= $id ?>' class='btn btn-sm btn-danger'>Eliminar</a>
                                <a href='editar.php?id=<?= $id ?>' class='btn btn-sm btn-primary'>Editar</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Nenhum trabalhador encontrado.</p>
        <?php endif;

        $stmt->close();
        $conn->close();
        ?>
    </div>
</main>

<?php include 'footer-admin.php'; ?>

<script>
document.getElementById('filtro').addEventListener('input', function () {
    const termo = this.value.toLowerCase();
    const linhas = document.querySelectorAll('#tabela-trabalhadores tbody tr');

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
