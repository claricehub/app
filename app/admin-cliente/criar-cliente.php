<?php
session_start();
require_once '../db/db.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $morada = $_POST['morada'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $contribuinte = $_POST['contribuinte'] ?? '';

    if (strlen($senha) < 6) {
        $erro = "A palavra-passe deve ter no mínimo 6 caracteres.";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO clientes (nome, email, senha, morada, telefone, contribuinte) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome, $email, $senha_hash, $morada, $telefone, $contribuinte);

        if ($stmt->execute()) {
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
    <meta charset="utf-8">
    <title>Criar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white text-center">
            <h2><i class="bi bi-person-fill"></i> Criar Cliente</h2>
        </div>
        <div class="card-body">
            <?php if ($erro): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>
            <form method="POST" autocomplete="off">
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" name="nome" id="nome" placeholder="Nome completo" required>
                    <label for="nome">Nome completo</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="email" name="email" id="email" placeholder="Email" required>
                    <label for="email">Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" name="morada" id="morada" placeholder="Morada">
                    <label for="morada">Morada</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" name="telefone" id="telefone" placeholder="Telemóvel">
                    <label for="telefone">Telemóvel</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" name="contribuinte" id="contribuinte" placeholder="NIF">
                    <label for="contribuinte">NIF (Contribuinte)</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="password" name="senha" id="senha" placeholder="Palavra-passe" required>
                    <label for="senha">Palavra-passe</label>
                </div>
                <button type="submit" class="btn btn-success w-100">Guardar Cliente</button>
            </form>
            <div class="mt-3 text-center">
                <a href="../admin-cliente/admin-cliente-pag.php" class="btn btn-outline-secondary">Voltar para o painel</a>
            </div>
        </div>
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Exemplo: se quiser autocomplete para nome do cliente, adapte aqui
    // (não obrigatório para criar-cliente, pois é cadastro novo)
});
</script>
</body>
</html>
