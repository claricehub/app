<?php
// Inclui a conexão com o banco de dados
require_once '../db/db.php';
// Inclui o cabeçalho do admin
include '../admin/header-admin.php';

// Inicializa variáveis de erro e sucesso
$erro = '';
$sucesso = '';

// Busca todos os trabalhadores para autocomplete (não usado diretamente no select)
$trabalhadores = [];
$res = $conn->query("SELECT id, nome FROM trabalhadores ORDER BY nome");
while ($row = $res->fetch_assoc()) $trabalhadores[] = $row;

// Busca todos os planos disponíveis
$planos = [];
$res = $conn->query("SELECT id, nome, destaque_duracao FROM planos ORDER BY id");
while ($row = $res->fetch_assoc()) $planos[] = $row;

// Se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $trabalhador_id = $_POST['trabalhador_id'] ?? '';
    $plano_id = $_POST['plano_id'] ?? '';
    $metodo = $_POST['metodo_pagamento'] ?? 'manual';
    $status = $_POST['status_pagamento'] ?? 'confirmado';

    // Busca o valor e duração do plano selecionado
    $valor = 0;
    $duracao = 30;
    foreach ($planos as $p) {
        if ($p['id'] == $plano_id) {
            $duracao = (int)($p['destaque_duracao'] ?? 30);
            if ($duracao <= 0) $duracao = 365;
        }
    }
    // Busca o valor do plano no banco
    $stmt = $conn->prepare("SELECT valor FROM planos WHERE id = ?");
    $stmt->bind_param("i", $plano_id);
    $stmt->execute();
    $stmt->bind_result($valor);
    $stmt->fetch();
    $stmt->close();

    // Insere o pagamento no banco
    $stmt = $conn->prepare("INSERT INTO pagamentos (trabalhador_id, plano_id, valor_pago, metodo_pagamento, status_pagamento, data_pagamento) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iisss", $trabalhador_id, $plano_id, $valor, $metodo, $status);
    $stmt->execute();
    $stmt->close();

    // Atualiza ou cria assinatura do trabalhador
    $data_inicio = date('Y-m-d');
    $data_fim = date('Y-m-d', strtotime("+{$duracao} days"));
    $stmt = $conn->prepare("REPLACE INTO assinaturas (trabalhador_id, plano_id, inicio, fim, ativo) VALUES (?, ?, ?, ?, 1)");
    $stmt->bind_param("iiss", $trabalhador_id, $plano_id, $data_inicio, $data_fim);
    $stmt->execute();
    $stmt->close();

    // Redireciona para a listagem de pagamentos
    header("Location: admin-pagamentos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Pagamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Criar Novo Pagamento</h2>
    <form method="POST">
        <!-- Campo de autocomplete do trabalhador -->
        <div class="mb-3 position-relative">
            <label class="form-label">Trabalhador</label>
            <input type="text" id="trabalhador_nome" class="form-control" placeholder="Digite o nome do trabalhador" autocomplete="off" required>
            <input type="hidden" name="trabalhador_id" id="trabalhador_id" required>
            <div id="trabalhador_suggestions" class="autocomplete-suggestions"></div>
        </div>
        <!-- Campo de seleção do plano -->
        <div class="mb-3">
            <label class="form-label">Plano</label>
            <select name="plano_id" class="form-select" required>
                <option value="">Selecione...</option>
                <?php foreach ($planos as $plano): ?>
                    <option value="<?= $plano['id'] ?>"><?= htmlspecialchars($plano['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Campo de método de pagamento -->
        <div class="mb-3">
            <label class="form-label">Método de Pagamento</label>
            <input type="text" name="metodo_pagamento" class="form-control" value="manual" required>
        </div>
        <!-- Campo de status do pagamento -->
        <div class="mb-3">
            <label class="form-label">Status do Pagamento</label>
            <select name="status_pagamento" class="form-select" required>
                <option value="confirmado">Confirmado</option>
                <option value="pendente">Pendente</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>
        <!-- Botões de ação -->
        <button type="submit" class="btn btn-success">Criar Pagamento</button>
        <a href="admin-pagamentos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Autocomplete do trabalhador
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