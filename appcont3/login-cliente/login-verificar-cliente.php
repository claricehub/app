<?php
session_start();
require_once '../db/db.php';

if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['nome'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['password'];

    // Busca todos os e-mails cadastrados
    $resultado = $conn->query("SELECT email FROM clientes");

    $emailExiste = false;

    while ($row = $resultado->fetch_assoc()) {
        if (strtolower($row['email']) == strtolower($email)) {
            $emailExiste = true;
            break;
        }
    }

    if ($emailExiste) {
        echo "<h3 style='color: red;'>Este e-mail já está cadastrado. Por favor, use outro.</h3>";
        echo "<p><a href='../login-cliente/login.php'>Voltar ao Login</a></p>";
    } else {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO clientes (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senhaHash);

        if ($stmt->execute()) {
            header("Location: ../root/index.php");
            exit();
        } else {
            echo "<h3 style='color: red;'>Erro ao registrar: " . $stmt->error . "</h3>";
        }

        $stmt->close();
    }
} else {
    echo "<h3 style='color: red;'>Preencha todos os campos!</h3>";
    echo "<p><a href='../login-cliente/login.php'>Voltar ao Login</a></p>";
}

$conn->close();
?>
