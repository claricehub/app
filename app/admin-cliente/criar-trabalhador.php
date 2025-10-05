<?php
session_start();
require_once '../db/db.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $zona = $_POST['zona'] ?? '';
    $profissoes = $_POST['profissao'] ?? [];

    if (strlen($senha) < 6) {
        $erro = "A palavra-passe deve ter no mínimo 6 caracteres.";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO trabalhadores (nome, email, password, telefone, zona, titulo, texto1) VALUES (?, ?, ?, ?, ?, '', '')");
        $stmt->bind_param("ssssi", $nome, $email, $senha_hash, $telefone, $zona);

        if ($stmt->execute()) {
            $novo_id = $stmt->insert_id;

            $insertProf = $conn->prepare("INSERT INTO trabalhador_profissao (trabalhador_id, profissao_id) VALUES (?, ?)");
            foreach ($profissoes as $prof_id) {
                $insertProf->bind_param("ii", $novo_id, $prof_id);
                $insertProf->execute();
            }

            header("Location: ../admin/admin-pag.php");
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Trabalhador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Criar Trabalhador</h2>
    
    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Palavra-passe</label>
            <input type="password" name="senha" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Zona</label>
            <select name="zona" class="form-select" required>
                <option value="" disabled selected>Selecione uma zona</option>
                <?php
                $res = $conn->query("SELECT id, zona FROM zona ORDER BY zona");
                while ($z = $res->fetch_assoc()) {
                    echo "<option value='{$z['id']}'>" . htmlspecialchars($z['zona']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Profissões</label>
            <div class="row">
                <?php
                $res = $conn->query("SELECT id, categoria FROM categorias ORDER BY categoria");
                while ($cat = $res->fetch_assoc()) {
                    echo "<div class='col-md-6'>
                            <div class='form-check'>
                              <input class='form-check-input' type='checkbox' name='profissao[]' value='{$cat['id']}' id='prof{$cat['id']}'>
                              <label class='form-check-label' for='prof{$cat['id']}'>" . htmlspecialchars($cat['categoria']) . "</label>
                            </div>
                          </div>";
                }
                ?>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Criar</button>
        <a href="../admin-cliente/admin-trabalhadores.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html> 