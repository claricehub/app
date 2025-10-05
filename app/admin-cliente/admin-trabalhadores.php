<?php
session_start();
require_once '../db/db.php';
include '../admin/header-admin.php';
?>

<main class="flex-grow-1">
    <div class="container px-5 py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bolder">Gestão de Trabalhadores</h1>
            <a href="../admin-cliente/criar-trabalhador.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Novo Trabalhador
            </a>
        </div>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="bi bi-people"></i> Lista de Trabalhadores
                        </h5>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="filtro" class="form-control" placeholder="Filtrar trabalhadores...">
                    </div>
                    <div class="col-md-2 text-end">
                        <button class="btn btn-outline-light w-100" onclick="window.print()">
                            <i class="bi bi-printer"></i> Imprimir
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Mostrar/Ocultar colunas:</small><br>
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
                        <input class="form-check-input coluna-toggle" type="checkbox" value="zona" checked> Zona
                    </label>
                    <label class="form-check form-check-inline">
                        <input class="form-check-input coluna-toggle" type="checkbox" value="profissoes" checked> Profissões
                    </label>
                </div>

                <?php
                $sql = "SELECT t.*, z.zona AS nome_zona,
                        GROUP_CONCAT(c.categoria SEPARATOR ', ') AS profissoes
                        FROM trabalhadores t
                        LEFT JOIN zona z ON t.zona = z.id
                        LEFT JOIN trabalhador_profissao tp ON t.id = tp.trabalhador_id
                        LEFT JOIN categorias c ON tp.profissao_id = c.id
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
                                    <th class="col-zona">Zona</th>
                                    <th class="col-profissoes">Profissões</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()):
                                    $id = htmlspecialchars($row["id"]);
                                    $nome = htmlspecialchars($row["nome"]);
                                    $email = htmlspecialchars($row["email"]);
                                    $telefone = htmlspecialchars($row["telefone"]);
                                    $zona = htmlspecialchars($row["nome_zona"]);
                                    $profissoes = htmlspecialchars($row["profissoes"]);
                                ?>
                                <tr>
                                    <td><?= $id ?></td>
                                    <td class="col-nome"><?= $nome ?></td>
                                    <td class="col-email"><?= $email ?></td>
                                    <td class="col-telefone"><?= $telefone ?></td>
                                    <td class="col-zona"><?= $zona ?></td>
                                    <td class="col-profissoes"><?= $profissoes ?></td>
                                    <td>
                                        <a href="../admin-cliente/eliminar-trabalhador.php?id=<?= $id ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                        <a href="../admin-cliente/editar-trabalhador.php?id=<?= $id ?>" class="btn btn-sm btn-primary">Editar</a>
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
        </div>
    </div>
</main>

<?php include '../admin/footer-admin.php'; ?>

<script>
// Filtro de pesquisa
document.getElementById('filtro').addEventListener('input', function () {
    const termo = this.value.toLowerCase();
    const linhas = document.querySelectorAll('#tabela-trabalhadores tbody tr');

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