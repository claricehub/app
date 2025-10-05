<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['logado'] !== true) {
    header("Location: login-admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel do Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['admin_nome']) ?>!</h2>
    <p>Você está logado como administrador.</p>
    <a href="logout.php" class="btn btn-danger">Sair</a>
</div>
</body>
</html>
