<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../db/db.php';
include '../include/header.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $profissoes = $_POST['profissao'] ?? [];
    $zona = $_POST['zona'] ?? '';
    $admin = 1;
    $avaliacao = '';
    $disponibilidade = 1; // Disponível ao cadastrar

    if (strlen($senha) < 6) {
        $erro = "A palavra-passe deve ter no mínimo 6 caracteres.";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO trabalhadores (nome, email, telefone, password, zona, admin, disponibilidade) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisisi", $nome, $email, $telefone, $senha_hash, $zona, $admin, $disponibilidade);

        if ($stmt->execute()) {
            $trabalhador_id = $stmt->insert_id;

            $insertProf = $conn->prepare("INSERT INTO trabalhador_profissao (trabalhador_id, profissao_id) VALUES (?, ?)");
            foreach ($profissoes as $prof_id) {
                $insertProf->bind_param("ii", $trabalhador_id, $prof_id);
                $insertProf->execute();
            }

            $_SESSION['trabalhador_id'] = $trabalhador_id;
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;

            header("Location: ../user/perfil-trabalhador.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registo de Trabalhador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
</head>
<body class="bg-light d-flex flex-column min-vh-100">
<main class="flex-grow-1">
    <section class="py-5">
        <div class="container px-5">
            <div class="bg-white rounded-3 py-5 px-4 px-md-5 shadow">
                <div class="text-center mb-5">
                    <div class="feature bg-success bg-gradient text-white rounded-3 mb-3">
                        <i class="bi bi-person-plus"></i>
                    </div>
                    <h1 class="fw-bolder">Registo de Trabalhador</h1>
                    <p class="lead text-muted">Crie a sua conta para começar a oferecer serviços</p>
                </div>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger text-center"><?php echo htmlspecialchars($erro); ?></div>
                <?php elseif (!empty($sucesso)): ?>
                    <div class="alert alert-success text-center"><?php echo $sucesso; ?></div>
                <?php endif; ?>

                <div class="row justify-content-center">
                    <div class="col-lg-8 col-xl-6">
                        <form method="POST" action="">
                            <!-- Nome -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="nome" name="nome" type="text" placeholder="O seu nome" required />
                                <label for="nome">Nome completo</label>
                            </div>

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" name="email" type="email" placeholder="nome@exemplo.com" required />
                                <label for="email">Email</label>
                            </div>

                            <!-- Telefone -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="telefone" name="telefone" type="text" placeholder="912345678" required />
                                <label for="telefone">Telemóvel</label>
                            </div>

                            <!-- Senha -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="senha" name="senha" type="password" placeholder="Palavra-passe" required />
                                <label for="senha">Palavra-passe (mínimo 6 caracteres)</label>
                            </div>

                            <!-- Profissões -->
                            <div class="mb-3">
                                <label class="form-label">Profissões</label>
                                <div id="profissoes-container">
                                    <div class="input-group mb-2">
                                        <select class="form-select" name="profissao[]" required>
                                            <option value="" disabled selected>Selecione uma profissão</option>
                                            <?php
                                            $res = $conn->query("SELECT id, categoria FROM categorias");
                                            while ($cat = $res->fetch_assoc()) {
                                                echo "<option value='{$cat['id']}'>" . htmlspecialchars($cat['categoria']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="adicionarProfissao()">+ Adicionar outra profissão</button>
                            </div>

                            <!-- Zona -->
                            <div class="form-floating mb-3">
                                <select class="form-select" id="zona" name="zona" required>
                                    <option value="" disabled selected>Selecione uma zona</option>
                                    <?php
                                    $res = $conn->query("SELECT id, zona FROM zona");
                                    while ($z = $res->fetch_assoc()) {
                                        echo "<option value='{$z['id']}'>" . htmlspecialchars($z['zona']) . "</option>";
                                    }
                                    ?>
                                </select>
                                <label for="zona">Zona</label>
                            </div>

                            <!-- Botão -->
                            <div class="d-grid">
                                <button class="btn btn-success btn-lg" type="submit">Registar</button>
                            </div>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="../login-trabalhador/login.php">Já tem conta? Inicie sessão</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include '../include/footer.php'; ?>

<!-- Script para adicionar/remover selects -->
<script>
function adicionarProfissao() {
    const container = document.getElementById('profissoes-container');
    const novaLinha = document.createElement('div');
    novaLinha.classList.add('input-group', 'mb-2');

    // O PHP não será executado dentro do JS, então precisamos gerar as opções via JS ou duplicar o select inicial
    // Para evitar erro de parse, vamos clonar o primeiro select
    const selects = container.getElementsByTagName('select');
    let selectHTML = '';
    if (selects.length > 0) {
        selectHTML = selects[0].outerHTML;
    } else {
        selectHTML = `
            <select class="form-select" name="profissao[]" required>
                <option value="" disabled selected>Selecione uma profissão</option>
            </select>
        `;
    }

    novaLinha.innerHTML = selectHTML + 
        '<button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">×</button>';

    container.appendChild(novaLinha);
}
</script>
</body>
</html>
</html>
