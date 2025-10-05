<?php
session_start();
require_once '../db/db.php';

$erro = '';

// Buscar dados para os selects e armazenar em arrays
$clientes_arr = [];
$res = $conn->query("SELECT id, nome FROM clientes ORDER BY nome");
while ($row = $res->fetch_assoc()) $clientes_arr[] = $row;

$trabalhadores_arr = [];
$res = $conn->query("SELECT id, nome FROM trabalhadores ORDER BY nome");
while ($row = $res->fetch_assoc()) $trabalhadores_arr[] = $row;

$pedidos_arr = [];
$res = $conn->query("SELECT id FROM pedidos ORDER BY id DESC");
while ($row = $res->fetch_assoc()) $pedidos_arr[] = $row;

// Lógica de criação
$cliente_id     = $_POST['cliente_id'] ?? '';
$trabalhador_id = $_POST['trabalhador_id'] ?? '';
$pedido_id      = $_POST['pedido_id'] ?? '';
$estrelas       = $_POST['estrelas'] ?? '';
$comentario     = trim($_POST['comentario'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$cliente_id || !$trabalhador_id || !$pedido_id || !$estrelas) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif ((int)$estrelas < 1 || (int)$estrelas > 5) {
        $erro = "A nota deve estar entre 1 e 5 estrelas.";
    } else {
        // Verificar se já existe avaliação para o pedido selecionado
        $check = $conn->prepare("SELECT id FROM avaliacao WHERE pedido_id = ?");
        $check->bind_param("i", $pedido_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $erro = "Já existe uma avaliação para esse pedido.";
        } else {
            $stmt = $conn->prepare("INSERT INTO avaliacao (cliente_id, trabalhador_id, pedido_id, estrelas, comentario) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiis", $cliente_id, $trabalhador_id, $pedido_id, $estrelas, $comentario);

            if ($stmt->execute()) {
                header("Location: avaliacoes-lista.php");
                exit;
            } else {
                $erro = "Erro ao salvar: " . $stmt->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <title>Criar Avaliação</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .autocomplete-suggestions {
      border: 1px solid #ced4da;
      background: #fff;
      max-height: 200px;
      overflow-y: auto;
      position: absolute;
      z-index: 1000;
      width: 100%;
    }
    .autocomplete-suggestion {
      padding: 8px 12px;
      cursor: pointer;
    }
    .autocomplete-suggestion:hover {
      background: #f1f1f1;
    }
    .position-relative { position: relative; }
  </style>
</head>
<body class="bg-light">
<main class="container py-5">
  <div class="card shadow">
    <div class="card-header bg-warning text-white text-center">
      <h2><i class="bi bi-star-fill"></i> Criar Avaliação</h2>
    </div>
    <div class="card-body">
      <?php if ($erro): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>
      <form method="POST" autocomplete="off">
        <div class="mb-3 position-relative">
          <label>Cliente:</label>
          <input type="text" id="cliente_nome" class="form-control" placeholder="Digite o nome do cliente" autocomplete="off" required>
          <input type="hidden" name="cliente_id" id="cliente_id" required>
          <div id="cliente_suggestions" class="autocomplete-suggestions"></div>
        </div>
        <div class="mb-3 position-relative">
          <label>Trabalhador:</label>
          <input type="text" id="trabalhador_nome" class="form-control" placeholder="Digite o nome do trabalhador" autocomplete="off" required>
          <input type="hidden" name="trabalhador_id" id="trabalhador_id" required>
          <div id="trabalhador_suggestions" class="autocomplete-suggestions"></div>
        </div>
        <div class="form-floating mb-3">
          <select class="form-select" name="pedido_id" id="pedido_id" required>
            <option value="">Escolha o pedido</option>
            <?php foreach ($pedidos_arr as $p): ?>
              <option value="<?= $p['id'] ?>" <?= $pedido_id == $p['id'] ? 'selected' : '' ?>>Pedido #<?= $p['id'] ?></option>
            <?php endforeach; ?>
          </select>
          <label for="pedido_id">Pedido</label>
        </div>
        <div class="form-floating mb-3">
          <select class="form-select" name="estrelas" id="estrelas" required>
            <option value="">Escolha uma nota</option>
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <option value="<?= $i ?>" <?= $estrelas == $i ? 'selected' : '' ?>><?= $i ?> estrela<?= $i > 1 ? 's' : '' ?></option>
            <?php endfor; ?>
          </select>
          <label for="estrelas">Nota</label>
        </div>
        <div class="form-floating mb-3">
          <textarea class="form-control" name="comentario" id="comentario" style="height:100px" placeholder="Comentário..."><?= htmlspecialchars($comentario) ?></textarea>
          <label for="comentario">Comentário (opcional)</label>
        </div>
        <button type="submit" class="btn btn-warning w-100 text-white">
          <i class="bi bi-check-circle"></i> Salvar Avaliação
        </button>
      </form>
      <div class="mt-3 text-center">
        <a href="avaliacoes-lista.php" class="btn btn-outline-secondary">Voltar para o painel</a>
      </div>
    </div>
  </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cliente autocomplete
    const inputCliente = document.getElementById('cliente_nome');
    const hiddenCliente = document.getElementById('cliente_id');
    const suggestionsCliente = document.getElementById('cliente_suggestions');
    inputCliente.addEventListener('input', function() {
        const val = this.value.trim();
        hiddenCliente.value = '';
        suggestionsCliente.innerHTML = '';
        if (val.length < 2) return;
        fetch('../admin-cliente/pesquisar-cliente.php?q=' + encodeURIComponent(val))
            .then(resp => resp.json())
            .then(data => {
                suggestionsCliente.innerHTML = '';
                data.forEach(function(item) {
                    const div = document.createElement('div');
                    div.className = 'autocomplete-suggestion';
                    div.textContent = item.nome;
                    div.dataset.id = item.id;
                    div.addEventListener('click', function() {
                        inputCliente.value = item.nome;
                        hiddenCliente.value = item.id;
                        suggestionsCliente.innerHTML = '';
                    });
                    suggestionsCliente.appendChild(div);
                });
            });
    });
    document.addEventListener('click', function(e) {
        if (!inputCliente.contains(e.target) && !suggestionsCliente.contains(e.target)) {
            suggestionsCliente.innerHTML = '';
        }
    });

    // Trabalhador autocomplete
    const input = document.getElementById('trabalhador_nome');
    const hidden = document.getElementById('trabalhador_id');
    const suggestions = document.getElementById('trabalhador_suggestions');
    input.addEventListener('input', function() {
        const val = this.value.trim();
        hidden.value = '';
        suggestions.innerHTML = '';
        if (val.length < 2) return;
        fetch('../admin-cliente/pesquisar-trabalhador.php?q=' + encodeURIComponent(val))
            .then(resp => resp.json())
            .then(data => {
                suggestions.innerHTML = '';
                data.forEach(function(item) {
                    const div = document.createElement('div');
                    div.className = 'autocomplete-suggestion';
                    div.textContent = item.nome;
                    div.dataset.id = item.id;
                    div.addEventListener('click', function() {
                        input.value = item.nome;
                        hidden.value = item.id;
                        suggestions.innerHTML = '';
                    });
                    suggestions.appendChild(div);
                });
            });
    });
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.innerHTML = '';
        }
    });
});
</script>
</body>
</html>
