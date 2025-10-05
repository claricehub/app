<?php
session_start();
require_once '../db/db.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $_POST['id_cliente'] ?? '';
    $trabalhador = $_POST['id_trabalhador'] ?? '';
    $mensagem = $_POST['mensagem'] ?? '';

    $stmt = $conn->prepare("INSERT INTO pedidos (id_cliente, id_trabalhador, mensagem, status) VALUES (?, ?, ?, 'pendente')");
    $stmt->bind_param("iis", $cliente, $trabalhador, $mensagem);

    if ($stmt->execute()) {
        header("Location: ../admin-cliente/admin-pedidos.php");
        exit;
    } else {
        $erro = "Erro ao salvar: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Criar Pedido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="card-header bg-primary text-white text-center">
            <h2><i class="bi bi-journal-plus"></i> Criar Pedido</h2>
        </div>
        <div class="card-body">
            <?php if ($erro): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>
            <form method="POST" autocomplete="off">
                <div class="mb-3 position-relative">
                    <label>Cliente:</label>
                    <input type="text" id="cliente_nome" class="form-control" placeholder="Digite o nome do cliente" autocomplete="off" required>
                    <input type="hidden" name="id_cliente" id="id_cliente" required>
                    <div id="cliente_suggestions" class="autocomplete-suggestions"></div>
                </div>
                <div class="mb-3 position-relative">
                    <label>Trabalhador:</label>
                    <input type="text" id="trabalhador_nome" class="form-control" placeholder="Digite o nome do trabalhador" autocomplete="off" required>
                    <input type="hidden" name="id_trabalhador" id="id_trabalhador" required>
                    <div id="trabalhador_suggestions" class="autocomplete-suggestions"></div>
                </div>
                <div class="form-floating mb-3">
                    <textarea class="form-control" name="mensagem" id="mensagem" style="height: 100px" required></textarea>
                    <label for="mensagem">Mensagem</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Salvar Pedido</button>
            </form>
            <div class="mt-3 text-center">
                <a href="../admin-cliente/admin-pedidos.php" class="btn btn-outline-secondary">Voltar para o painel</a>
            </div>
        </div>
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cliente autocomplete
    const inputCliente = document.getElementById('cliente_nome');
    const hiddenCliente = document.getElementById('id_cliente');
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
    const hidden = document.getElementById('id_trabalhador');
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
