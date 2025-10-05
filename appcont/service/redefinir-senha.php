<?php
session_start();
require_once '../db/db.php';

$erro = '';
$sucesso = '';

if (!isset($_GET['token'])) {
    die("Token inválido.");
}

$token = $_GET['token'];

$stmt = $conn->prepare("SELECT * FROM reset_senhas WHERE token = ? AND usado = 0 AND expira > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    die("Token inválido ou expirado.");
}

$dados = $resultado->fetch_assoc();
$id_cliente = $dados['id_cliente'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novaSenha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE clientes SET senha = ? WHERE id = ?");
    $stmt->bind_param("si", $novaSenha, $id_cliente);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE reset_senhas SET usado = 1 WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    $sucesso = "Palavra-passe redefinida com sucesso. <a href='login-cliente.php'>Faça login</a>";
}
?>

<form method="post">
    <h3>Redefinir Palavra-passe</h3>
    <?php if ($erro): ?><div class="alert alert-danger"><?= $erro ?></div><?php endif; ?>
    <?php if ($sucesso): ?><div class="alert alert-success"><?= $sucesso ?></div><?php endif; ?>

    <div class="mb-3">
        <label>Nova palavra-passe:</label>
        <input type="password" name="senha" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Guardar nova palavra-passe</button>
</form>
