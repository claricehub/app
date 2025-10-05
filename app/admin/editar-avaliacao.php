<?php
session_start();
require_once '../db/db.php';

$id = $_GET['id'] ?? null;
$erro = '';

// Verifica se ID é válido
if (!$id || !is_numeric($id)) {
  echo "<div class='container py-4'><div class='alert alert-danger'>Avaliação não encontrada.</div></div>";
  exit;
}

// Buscar dados da avaliação
$stmt = $conn->prepare("SELECT * FROM avaliacao WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$avaliacao = $result->fetch_assoc();
$stmt->close();

if (!$avaliacao) {
  echo "<div class='container py-4'><div class='alert alert-danger'>Avaliação não encontrada.</div></div>";
  exit;
}

// Buscar dados para os selects
$clientes = $conn->query("SELECT id, nome FROM clientes ORDER BY nome");
$trabalhadores = $conn->query("SELECT id, nome FROM trabalhadores ORDER BY nome");
$pedidos = $conn->query("SELECT id FROM pedidos ORDER BY id DESC");

// Atualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cliente_id     = $_POST['cliente_id'] ?? '';
  $trabalhador_id = $_POST['trabalhador_id'] ?? '';
  $pedido_id      = $_POST['pedido_id'] ?? '';
  $estrelas       = (int) ($_POST['estrelas'] ?? 0);
  $comentario     = trim($_POST['comentario'] ?? '');

  if ($estrelas < 1 || $estrelas > 5) {
    $erro = "A nota deve estar entre 1 e 5 estrelas.";
  } else {
    $stmt = $conn->prepare("UPDATE avaliacao SET cliente_id = ?, trabalhador_id = ?, pedido_id = ?, estrelas = ?, comentario = ? WHERE id = ?");
    $stmt->bind_param("iiiisi", $cliente_id, $trabalhador_id, $pedido_id, $estrelas, $comentario, $id);
    $stmt->execute();

    header("Location: admin-avaliacao.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <title>Editar Avaliação</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white text-center">
      <h2><i class="bi bi-pencil-fill"></i> Editar Avaliação</h2>
    </div>
    <div class="card-body">
      <?php if ($erro): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>
      <form method="POST">
        <div class="form-floating mb-3">
          <select class="form-select" name="cliente_id" id="cliente_id" required>
            <?php while ($c = $clientes->fetch_assoc()): ?>
              <option value="<?= $c['id'] ?>" <?= $c['id'] == $avaliacao['cliente_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nome']) ?>
              </option>
            <?php endwhile; ?>
          </select>
          <label for="cliente_id">Cliente</label>
        </div>
        <div class="form-floating mb-3">
          <select class="form-select" name="trabalhador_id" id="trabalhador_id" required>
            <?php while ($t = $trabalhadores->fetch_assoc()): ?>
              <option value="<?= $t['id'] ?>" <?= $t['id'] == $avaliacao['trabalhador_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($t['nome']) ?>
              </option>
            <?php endwhile; ?>
          </select>
          <label for="trabalhador_id">Trabalhador</label>
        </div>
        <div class="form-floating mb-3">
          <select class="form-select" name="pedido_id" id="pedido_id" required>
            <?php while ($p = $pedidos->fetch_assoc()): ?>
              <option value="<?= $p['id'] ?>" <?= $p['id'] == $avaliacao['pedido_id'] ? 'selected' : '' ?>>
                Pedido #<?= $p['id'] ?>
              </option>
            <?php endwhile; ?>
          </select>
          <label for="pedido_id">Pedido</label>
        </div>
        <div class="form-floating mb-3">
          <select class="form-select" name="estrelas" id="estrelas" required>
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <option value="<?= $i ?>" <?= $avaliacao['estrelas'] == $i ? 'selected' : '' ?>>
                <?= $i ?> estrela<?= $i > 1 ? 's' : '' ?>
              </option>
            <?php endfor; ?>
          </select>
          <label for="estrelas">Nota</label>
        </div>
        <div class="form-floating mb-3">
          <textarea class="form-control" name="comentario" id="comentario" style="height:100px"><?= htmlspecialchars($avaliacao['comentario']) ?></textarea>
          <label for="comentario">Comentário (opcional)</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-save-fill"></i> Atualizar Avaliação
        </button>
      </form>
      <div class="mt-3 text-center">
        <a href="avaliacoes-lista.php" class="btn btn-outline-secondary">Voltar para o painel</a>
      </div>
    </div>
  </div>
</main>
</body>
</html>
