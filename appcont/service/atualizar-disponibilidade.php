<?php
// Inicia a sessão para acessar variáveis de sessão do usuário
session_start();
// Inclui o arquivo de conexão com o banco de dados
require_once '../db/db.php';

// Verifica se o trabalhador está logado, senão redireciona para login
if (!isset($_SESSION['trabalhador_id'])) {
    header("Location: ../login-trabalhador/login.php"); // Redireciona se não estiver logado
    exit();
}

// Obtém o ID do trabalhador logado a partir da sessão
$id = $_SESSION['trabalhador_id'];
// Obtém o novo valor de disponibilidade enviado pelo formulário (POST), padrão 1 (disponível)
$nova_disponibilidade = isset($_POST['disponibilidade']) ? (int) $_POST['disponibilidade'] : 1;

// Prepara a query para atualizar a disponibilidade do trabalhador
$stmt = $conn->prepare("UPDATE trabalhadores SET disponibilidade = ? WHERE id = ?");
// Faz o bind dos parâmetros (nova disponibilidade e id do trabalhador)
$stmt->bind_param("ii", $nova_disponibilidade, $id);
// Executa a query de atualização
$stmt->execute();
// Fecha o statement para liberar recursos
$stmt->close();

// Redireciona de volta para o perfil do trabalhador após atualizar
header("Location: ../user/perfil-trabalhador.php");
exit();
?>
