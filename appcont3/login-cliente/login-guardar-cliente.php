<?php
session_start();
require_once '../db/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $senha = $_POST['password'];
    $redirectId = isset($_POST['redirect_id']) ? intval($_POST['redirect_id']) : null;

    // DEBUG opcional
    // echo "Email recebido: [$email]";

    $stmt = $conn->prepare("SELECT * FROM clientes WHERE email = ?");
    if (!$stmt) {
        die("Erro na preparação da query: " . $conn->error);
    }

    $stmt->bind_param("s", $emai);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows === 1) {
        $cliente = $resultado->fetch_assoc();

        // Se a senha estiver em texto puro (sem hash), use: if ($senha === $cliente['senha'])
        if (password_verify($senha, $cliente['senha'])) {
            $_SESSION['cliente_id'] = $cliente['id'];
            $_SESSION['nome'] = $cliente['nome'];
            $_SESSION['email'] = $cliente['email'];
            $_SESSION['logado'] = true;

            if ($redirectId) {
                header("Location: ../user/perfil-trabalhador-para-o-cliente.php?id=$redirectId");
            } else {
                header("Location: ../user/perfil-cliente.php");
            }
            exit();
        } else {
            echo "<script>alert('Senha incorreta.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email não encontrado.'); window.history.back();</script>";
    }

    $stmt->close();
}
?>
