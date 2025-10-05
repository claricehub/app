<?php
require_once '../db/db.php';

$erro = '';
$sucesso = '';

// Verifica se o ID do pedido foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
  die("ID do pedido não fornecido.");
}

$pedido_id = (int) $_GET['id'];

// Busca o pedido
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE id = ?");
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();
$stmt->close();

if (!$pedido) {
  die("Pedido não encontrado.");
}

// Adiciona o novo status 'feito'
$status_opcoes = ['pendente', 'aceito', 'recusado', 'cancelado', 'feito'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mensagem = trim($_POST['mensagem']);
  $status = $_POST['status'];
  $cliente_id = $_POST['cliente_id'];
  $trabalhador_id = $_POST['trabalhador_id'];

  if (empty($mensagem)) {
    $erro = "A mensagem não pode estar vazia.";
  } elseif (!in_array($status, $status_opcoes)) {
    $erro = "Estado inválido.";
  } elseif (empty($cliente_id) || empty($trabalhador_id)) {
    $erro = "Cliente e trabalhador são obrigatórios.";
  } else {
    $stmt = $conn->prepare("UPDATE pedidos SET mensagem = ?, status = ?, id_cliente = ?, id_trabalhador = ? WHERE id = ?");
    $stmt->bind_param("ssiii", $mensagem, $status, $cliente_id, $trabalhador_id, $pedido_id);

    if ($stmt->execute()) {
      header("Location: ../admin-cliente/admin-pedidos.php");
      exit();
    } else {
      $erro = "Erro ao atualizar encomenda: " . $stmt->error;
    }

    $stmt->close();
  }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Editar Encomenda #<?= htmlspecialchars($pedido_id) ?></h2>
    
    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <select name="cliente_id" class="form-select" required>
                <option value="" disabled>Selecione um cliente</option>
                <?php
                $res = $conn->query("SELECT id, nome FROM clientes ORDER BY nome");
                while ($cli = $res->fetch_assoc()) {
                    $selected = ($pedido['id_cliente'] == $cli['id']) ? 'selected' : '';
                    echo "<option value='{$cli['id']}' $selected>" . htmlspecialchars($cli['nome']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Trabalhador</label>
            <select name="trabalhador_id" class="form-select" required>
                <option value="" disabled>Selecione um trabalhador</option>
                <?php
                $res = $conn->query("SELECT id, nome FROM trabalhadores ORDER BY nome");
                while ($tra = $res->fetch_assoc()) {
                    $selected = ($pedido['id_trabalhador'] == $tra['id']) ? 'selected' : '';
                    echo "<option value='{$tra['id']}' $selected>" . htmlspecialchars($tra['nome']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Mensagem</label>
            <textarea name="mensagem" class="form-control" rows="5" required><?= htmlspecialchars($pedido['mensagem']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="status" class="form-select" required>
                <?php
                foreach ($status_opcoes as $op) {
                    $selected = ($pedido['status'] === $op) ? 'selected' : '';
                    echo "<option value=\"$op\" $selected>" . ucfirst($op) . "</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="../admin-cliente/admin-pedidos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
