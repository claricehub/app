<?php
session_start();
if (!isset($_SESSION['trabalhador_id'])) {
  header("Location: ../login-trabalhador/login.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Pagamento Confirmado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5 text-center">
    <h2 class="text-success">✅ Pagamento confirmado com sucesso!</h2>
    <p class="lead">Seu plano premium foi ativado. Você ficará em destaque por até <strong>6 meses</strong>.</p>
    <div class="alert alert-primary mt-4">ID do Trabalhador em destaque: <strong style="font-size:1.5rem;">#<?= isset($_SESSION['trabalhador_id']) ? $_SESSION['trabalhador_id'] : 'EXEMPLO' ?></strong></div>
    <a href="../user/perfil-trabalhador.php" class="btn btn-primary mt-4">Ir para o painel</a>
  </div>
</body>
</html>
