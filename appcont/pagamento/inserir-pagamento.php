<?php
session_start();
require_once '../db/db.php';

if (!isset($_SESSION['trabalhador_id']) || !isset($_POST['plano_id'])) {
  header("Location: pacote-premium.php");
  exit();
}

$trabalhador_id = $_SESSION['trabalhador_id'];
$plano_id = (int) $_POST['plano_id'];

// Buscar valor e duração
$stmt = $conn->prepare("SELECT valor, destaque_duracao FROM planos WHERE id = ?");
$stmt->bind_param("i", $plano_id);
$stmt->execute();
$plano = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$plano) {
  echo "Plano inválido.";
  exit();
}

$valor = $plano['valor'];
$dias = (int) $plano['destaque_duracao'];
$data_inicio = date('Y-m-d');
$data_fim = date('Y-m-d', strtotime("+$dias days"));

// Receber dados do cartão (simulação, não salvar no banco)
$nome_cartao = isset($_POST['nome_cartao']) ? trim($_POST['nome_cartao']) : '';
$numero_cartao = isset($_POST['numero_cartao']) ? trim($_POST['numero_cartao']) : '';
$validade = isset($_POST['validade']) ? trim($_POST['validade']) : '';
$cvv = isset($_POST['cvv']) ? trim($_POST['cvv']) : '';

// Validação simples (poderia ser mais robusta)
if (!$nome_cartao || !$numero_cartao || !$validade || !$cvv) {
  echo "Dados do cartão inválidos.";
  exit();
}

// Verificar se já existe assinatura ativa para esse trabalhador e plano
$stmt = $conn->prepare("SELECT fim FROM assinaturas WHERE trabalhador_id = ? AND plano_id = ? AND ativo = 1 LIMIT 1");
$stmt->bind_param("ii", $trabalhador_id, $plano_id);
$stmt->execute();
$stmt->bind_result($fim);
$assinatura_ativa = false;
if ($stmt->fetch()) {
    if (strtotime($fim) > time()) {
        $assinatura_ativa = $fim;
    }
}
$stmt->close();

if ($assinatura_ativa) {
    echo '<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height:60vh;">';
    echo '  <div class="card shadow-lg" style="max-width: 480px; width:100%;">';
    echo '    <div class="card-body text-center">';
    echo '      <div class="mb-3"><i class="bi bi-exclamation-triangle-fill text-warning" style="font-size:3rem;"></i></div>';
    echo '      <h4 class="mb-3">Plano já ativo</h4>';
    echo '      <p class="mb-4">Você já possui esse pacote ativo até <strong>' . date('d/m/Y', strtotime($assinatura_ativa)) . '</strong>.<br>Não é possível adquirir novamente antes do término desse período.</p>';
    echo '      <a href="pacote-premium.php" class="btn btn-primary btn-lg">Voltar para os planos</a>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">';
    exit();
}

// Registrar pagamento
$stmt = $conn->prepare("INSERT INTO pagamentos (trabalhador_id, plano_id, valor_pago, metodo_pagamento) VALUES (?, ?, ?, 'manual')");
$stmt->bind_param("iid", $trabalhador_id, $plano_id, $valor);
$stmt->execute();
$stmt->close();

// Atualizar ou criar assinatura
$stmt = $conn->prepare("REPLACE INTO assinaturas (trabalhador_id, plano_id, inicio, fim, ativo) VALUES (?, ?, ?, ?, 1)");
$stmt->bind_param("iiss", $trabalhador_id, $plano_id, $data_inicio, $data_fim);
$stmt->execute();
$stmt->close();

header("Location: pagamento-confirmado.php");
exit();
?>
