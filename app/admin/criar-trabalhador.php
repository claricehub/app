<?php
session_start();
require_once '../db/db.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $zona = $_POST['zona'] ?? '';
    $profissoes = $_POST['profissao'] ?? [];

    if (strlen($senha) < 6) {
        $erro = "A senha deve ter no mínimo 6 caracteres.";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO trabalhadores (nome, email, password, zona, titulo, texto1) VALUES (?, ?, ?, ?, '', '')");
        $stmt->bind_param("sssi", $nome, $email, $senha_hash, $zona);

        if ($stmt->execute()) {
            $novo_id = $stmt->insert_id;

            $insertProf = $conn->prepare("INSERT INTO trabalhador_profissao (trabalhador_id, profissao_id) VALUES (?, ?)");
            foreach ($profissoes as $prof_id) {
                $insertProf->bind_param("ii", $novo_id, $prof_id);
                $insertProf->execute();
            }

            header("Location: admin-pag.php");
            exit;
        } else {
            $erro = "Erro ao salvar: " . $stmt->error;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Criar Trabalhador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light d-flex flex-column min-vh-100">
<main class="flex-grow-1">
    <section class="py-5">
        <div class="container px-5">
            <div class="bg-white rounded-3 py-5 px-4 px-md-5 shadow">
                <div class="text-center mb-5">
                    <div class="feature bg-warning bg-gradient text-white rounded-3 mb-3">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <h1 class="fw-bolder">Criar Trabalhador</h1>
                    <p class="lead text-muted">Insira os dados do novo profissional</p>
                </div>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger text-center"><?php echo htmlspecialchars($erro); ?></div>
                <?php endif; ?>

                <div class="row justify-content-center">
                    <div class="col-lg-8 col-xl-6">
                        <form method="POST">
                            <!-- Nome -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="nome" name="nome" type="text" placeholder="Nome completo" required />
                                <label for="nome">Nome completo</label>
                            </div>

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" name="email" type="email" placeholder="Email" required />
                                <label for="email">Email</label>
                            </div>

                            <!-- Senha -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="senha" name="senha" type="password" placeholder="Senha" required />
                                <label for="senha">Palavra-passe</label>
                            </div>

                            <!-- Profissões -->
                            <div class="mb-3">
                                <label class="form-label">Profissões</label>
                                <div id="profissoes-container">
                                    <?php
                                    $res = $conn->query("SELECT id, categoria FROM categorias");
                                    while ($cat = $res->fetch_assoc()) {
                                        echo "<div class='form-check'>
                                                <input class='form-check-input' type='checkbox' name='profissao[]' value='{$cat['id']}' id='prof{$cat['id']}'>
                                                <label class='form-check-label' for='prof{$cat['id']}'>" . htmlspecialchars($cat['categoria']) . "</label>
                                              </div>";
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Zona -->
                            <div class="mb-3 position-relative">
                                <label for="zona_nome" class="form-label">Zona</label>
                                <input class="form-control" id="zona_nome" type="text" placeholder="Digite a zona" autocomplete="off" required>
                                <input type="hidden" name="zona" id="zona" required>
                                <div id="zona_suggestions" class="autocomplete-suggestions"></div>
                            </div>
                            <!-- Botão -->
                            <div class="d-grid">
                                <button class="btn btn-warning btn-lg" type="submit">Salvar Trabalhador</button>
                            </div>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="admin-pag.php">Voltar para o painel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Zona autocomplete
    const inputZona = document.getElementById('zona_nome');
    const hiddenZona = document.getElementById('zona');
    const suggestionsZona = document.getElementById('zona_suggestions');
    inputZona.addEventListener('input', function() {
        const val = this.value.trim();
        hiddenZona.value = '';
        suggestionsZona.innerHTML = '';
        if (val.length < 2) return;
        fetch('../admin-cliente/pesquisar-zona.php?q=' + encodeURIComponent(val))
            .then(resp => resp.json())
            .then(data => {
                suggestionsZona.innerHTML = '';
                data.forEach(function(item) {
                    const div = document.createElement('div');
                    div.className = 'autocomplete-suggestion';
                    div.textContent = item.zona;
                    div.dataset.id = item.id;
                    div.addEventListener('click', function() {
                        inputZona.value = item.zona;
                        hiddenZona.value = item.id;
                        suggestionsZona.innerHTML = '';
                    });
                    suggestionsZona.appendChild(div);
                });
            });
    });
    document.addEventListener('click', function(e) {
        if (!inputZona.contains(e.target) && !suggestionsZona.contains(e.target)) {
            suggestionsZona.innerHTML = '';
        }
    });
});
</script>
<?php include '../include/footer.php'; ?>
</body>
</html>

