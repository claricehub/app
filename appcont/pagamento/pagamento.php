<?php
session_start();
require_once '../db/db.php';
include '../include/header-trabalhador.php'; 

$trabalhador_id = isset($_SESSION['trabalhador_id']) ? $_SESSION['trabalhador_id'] : null;
if (!isset($_GET['plano_id'])) {
  echo '<div class="alert alert-danger">Plano não selecionado. <a href="pacote-premium.php">Escolher plano</a></div>';
  exit();
}

$plano_id = (int) $_GET['plano_id'];

// Buscar detalhes do plano
$stmt = $conn->prepare("SELECT nome, valor, destaque_duracao FROM planos WHERE id = ?");
$stmt->bind_param("i", $plano_id);
$stmt->execute();
$plano = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$plano) {
  echo "Plano inválido.";
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Pagamento do Plano</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">Pagamento do plano <strong><?= htmlspecialchars($plano['nome']) ?></strong></h2>

    <p>Valor: <strong>R$<?= number_format($plano['valor'], 2, ',', '.') ?></strong></p>
    <p>Duração de destaque: <strong><?= $plano['destaque_duracao'] ?> dias</strong></p>

    <?php if (!$trabalhador_id): ?>
      <div class="alert alert-warning mb-4">Você precisa estar logado como trabalhador para concluir o pagamento. <a href="../login-trabalhador/login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-primary btn-sm ms-2">Fazer login</a></div>
    <?php endif; ?>

    <form action="inserir-pagamento.php" method="post" autocomplete="off">
      <input type="hidden" name="plano_id" value="<?= $plano_id ?>">
      <div class="mb-3">
        <label for="nome_cartao" class="form-label">Nome no cartão</label>
        <input type="text" class="form-control" id="nome_cartao" name="nome_cartao" required>
      </div>
      <div class="mb-3">
        <label for="numero_cartao" class="form-label">Número do cartão</label>
        <input type="text" class="form-control" id="numero_cartao" name="numero_cartao" maxlength="19" pattern="[0-9 ]{13,19}" placeholder="0000 0000 0000 0000" required>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="validade" class="form-label">Validade</label>
          <input type="text" class="form-control" id="validade" name="validade" maxlength="5" pattern="(0[1-9]|1[0-2])\/([0-9]{2})" placeholder="MM/AA" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="cvv" class="form-label">CVV</label>
          <input type="password" class="form-control" id="cvv" name="cvv" maxlength="4" pattern="[0-9]{3,4}" required>
        </div>
      </div>
      <button type="submit" class="btn btn-success" <?= !$trabalhador_id ? 'disabled' : '' ?>>Confirmar pagamento</button>
    </form>
  </div>
  <?php include '../include/footer.php'; ?>
</body>
</html>
