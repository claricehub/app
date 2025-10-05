<?php
session_start();
require_once '../db/db.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

// Verifica campos
if (empty($email) || empty($senha)) {
    die("⚠️ Preencha todos os campos.");
}

$stmt = $conn->prepare("SELECT id, nome, email, password, admin FROM admin WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    if (password_verify($senha, $usuario['password'])) {
        if ($usuario['admin'] == 1) {
            $_SESSION['admin'] = true;
            $_SESSION['admin_nome'] = $usuario['nome'];
            header("Location: ../admin/admin-pag.php");
            exit;
        } else {
            echo "⛔ Você não tem permissão para acessar esta área.";
        }
    } else {
        echo "⚠️ Senha incorreta.";
    }
} else {
    echo "❌ Admin não encontrado.";
}
?>
