<?php
session_start();
require_once '../db/db.php';
include '../admin/header-admin.php';

$erro = '';
$sucesso = '';

// Verifica se o ID do trabalhador foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID do trabalhador não fornecido.");
}

$id = $_GET['id'];

// Busca os dados atuais do trabalhador, agora incluindo telefone
$stmt = $conn->prepare("SELECT nome, email, telefone, zona FROM trabalhadores WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$trabalhador = $result->fetch_assoc();
$stmt->close();

// Busca profissões atuais
$profissoes_atuais = [];
$res = $conn->query("SELECT profissao_id FROM trabalhador_profissao WHERE trabalhador_id = $id");
while ($row = $res->fetch_assoc()) {
    $profissoes_atuais[] = $row['profissao_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $profissoes = $_POST['profissao'] ?? [];
    $zona = $_POST['zona'] ?? '';

    if (!empty($senha) && strlen($senha) < 6) {
        $erro = "A senha deve ter no mínimo 6 caracteres.";
    } else {
        if (!empty($senha)) {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE trabalhadores SET nome = ?, email = ?, telefone = ?, zona = ?, password = ? WHERE id = ?");
            if ($stmt === false) {
                $erro = "Erro na preparação da query: " . $conn->error;
            } else {
                $stmt->bind_param("sssssi", $nome, $email, $telefone, $zona, $senha_hash, $id);
            }
        } else {
            $stmt = $conn->prepare("UPDATE trabalhadores SET nome = ?, email = ?, telefone = ?, zona = ? WHERE id = ?");
            if ($stmt === false) {
                $erro = "Erro na preparação da query: " . $conn->error;
            } else {
                $stmt->bind_param("ssssi", $nome, $email, $telefone, $zona, $id);
            }
        }

        if (empty($erro) && $stmt && $stmt->execute()) {
            // Atualiza profissões
            $conn->query("DELETE FROM trabalhador_profissao WHERE trabalhador_id = $id");
            $insertProf = $conn->prepare("INSERT INTO trabalhador_profissao (trabalhador_id, profissao_id) VALUES (?, ?)");
            if ($insertProf) {
                foreach ($profissoes as $prof_id) {
                    $insertProf->bind_param("ii", $id, $prof_id);
                    $insertProf->execute();
                }
                $insertProf->close();
            }
            $stmt->close();
            header("Location: ../admin/admin-pag.php");
            exit;
        } else {
            if (empty($erro)) {
                $erro = "Erro ao atualizar: " . ($stmt ? $stmt->error : $conn->error);
            }
            if ($stmt) $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Editar Trabalhador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
</head>
<body class="bg-light d-flex flex-column min-vh-100">
<main class="flex-grow-1">
    <section class="py-5">
        <div class="container px-5">
            <div class="bg-white rounded-3 py-5 px-4 px-md-5 shadow">
                <div class="text-center mb-5">
                    <div class="feature bg-warning bg-gradient text-white rounded-3 mb-3">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <h1 class="fw-bolder">Editar Trabalhador</h1>
                    <p class="lead text-muted">Atualize os dados do trabalhador</p>
                </div>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger text-center"><?php echo htmlspecialchars($erro); ?></div>
                <?php endif; ?>

                <div class="row justify-content-center">
                    <div class="col-lg-8 col-xl-6">
                        <form method="POST">
                            <!-- Nome -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="nome" name="nome" type="text" value="<?php echo htmlspecialchars($trabalhador['nome']); ?>" required />
                                <label for="nome">Nome completo</label>
                            </div>

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" name="email" type="email" value="<?php echo htmlspecialchars($trabalhador['email']); ?>" required />
                                <label for="email">Email</label>
                            </div>

                            <!-- Telefone -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="telefone" name="telefone" type="text" value="<?php echo htmlspecialchars($trabalhador['telefone'] ?? ''); ?>" />
                                <label for="telefone">Telefone</label>
                            </div>

                            <!-- Senha -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="senha" name="senha" type="password" placeholder="Nova senha" />
                                <label for="senha">Nova Palavra-passe(deixe em branco para manter)</label>
                            </div>

                            <!-- Profissões -->
                            <div class="mb-3">
                                <label class="form-label">Profissões</label>
                                <div id="profissoes-container">
                                    <?php
                                    $res = $conn->query("SELECT id, categoria FROM categorias");
                                    while ($cat = $res->fetch_assoc()) {
                                        $checked = in_array($cat['id'], $profissoes_atuais) ? 'checked' : '';
                                        echo "<div class='form-check'>
                                                <input class='form-check-input' type='checkbox' name='profissao[]' value='{$cat['id']}' id='prof{$cat['id']}' $checked>
                                                <label class='form-check-label' for='prof{$cat['id']}'>" . htmlspecialchars($cat['categoria']) . "</label>
                                              </div>";
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Zona -->
                            <div class="form-floating mb-3">
                                <select class="form-select" id="zona" name="zona" required>
                                    <option value="" disabled <?php echo empty($trabalhador['zona']) ? 'selected' : ''; ?>>Selecione uma zona</option>
                                    <?php
                                    $res = $conn->query("SELECT id, zona FROM zona");
                                    while ($z = $res->fetch_assoc()) {
                                        $selected = ($trabalhador['zona'] == $z['id']) ? 'selected' : '';
                                        echo "<option value='{$z['id']}' $selected>" . htmlspecialchars($z['zona']) . "</option>";
                                    }
                                    ?>
                                </select>
                                <label for="zona">Zona</label>
                            </div>

                            <!-- Botão -->
                            <div class="d-grid">
                                <button class="btn btn-warning btn-lg" type="submit">Salvar Alterações</button>
                            </div>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="../admin/admin-pag.php">Voltar para o painel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include '../include/footer.php'; ?>
</body>
</html>
