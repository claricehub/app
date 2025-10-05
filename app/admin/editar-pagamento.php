<?php
require_once '../db/db.php';
include '../admin/header-admin.php';

$id = $_GET['id'] ?? null;
$erro = '';

if (!$id || !is_numeric($id)) {
    echo "<div class='container py-4'><div class='alert alert-danger'>Pagamento não encontrado.</div></div>";
    exit;
}

// Buscar dados do pagamento
$stmt = $conn->prepare("SELECT * FROM pagamentos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$pagamento = $result->fetch_assoc();
$stmt->close();

if (!$pagamento) {
    echo "<div class='container py-4'><div class='alert alert-danger'>Pagamento não encontrado.</div></div>";
    exit;
}

// Buscar todos os planos disponíveis
$planos = [];
$res = $conn->query("SELECT id, nome, destaque_duracao FROM planos ORDER BY id");
while ($row = $res->fetch_assoc()) $planos[] = $row;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status_pagamento'] ?? '';
    $metodo = $_POST['metodo_pagamento'] ?? '';
    $plano_id = $_POST['plano_id'] ?? $pagamento['plano_id'];

    // Buscar valor do plano selecionado
    $valor = 0;
    foreach ($planos as $p) {
        if ($p['id'] == $plano_id) {
            $valor = isset($p['valor']) ? (float)$p['valor'] : 0;
        }
    }
    // Se não houver campo valor, ou for plano free, força 0
    if (!$valor || $plano_id == 1) {
        $valor = 0;
    }

    // Atualiza pagamento com valor correto
    $stmt = $conn->prepare("UPDATE pagamentos SET status_pagamento = ?, metodo_pagamento = ?, plano_id = ?, valor_pago = ? WHERE id = ?");
    $stmt->bind_param("sssdi", $status, $metodo, $plano_id, $valor, $id);
    $stmt->execute();
    $stmt->close();

    // Atualizar assinatura do trabalhador
    $trabalhador_id = $pagamento['trabalhador_id'];
    $data_inicio = date('Y-m-d');
    // Buscar duração do plano
    $duracao = 30; // padrão 30 dias
    foreach ($planos as $p) {
        if ($p['id'] == $plano_id) {
            $duracao = (int)($p['destaque_duracao'] ?? 30);
            if ($duracao <= 0) $duracao = 365; // Se for plano free ou sem duração, 1 ano
        }
    }
    $data_fim = date('Y-m-d', strtotime("+{$duracao} days"));
    // Atualiza ou cria assinatura
    $stmt = $conn->prepare("REPLACE INTO assinaturas (trabalhador_id, plano_id, inicio, fim, ativo) VALUES (?, ?, ?, ?, 1)");
    $stmt->bind_param("iiss", $trabalhador_id, $plano_id, $data_inicio, $data_fim);
    $stmt->execute();
    $stmt->close();

    header("Location: admin-pagamentos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pagamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Editar Pagamento #<?= htmlspecialchars($pagamento['id']) ?></h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Plano</label>
            <select name="plano_id" class="form-select" required>
                <?php foreach ($planos as $plano): ?>
                    <option value="<?= $plano['id'] ?>" <?= $pagamento['plano_id'] == $plano['id'] ? 'selected' : '' ?>><?= htmlspecialchars($plano['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Status do Pagamento</label>
            <select name="status_pagamento" class="form-select" required>
                <option value="confirmado" <?= $pagamento['status_pagamento'] === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                <option value="pendente" <?= $pagamento['status_pagamento'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="cancelado" <?= $pagamento['status_pagamento'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Método de Pagamento</label>
            <input type="text" name="metodo_pagamento" class="form-control" value="<?= htmlspecialchars($pagamento['metodo_pagamento']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="admin-pagamentos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>