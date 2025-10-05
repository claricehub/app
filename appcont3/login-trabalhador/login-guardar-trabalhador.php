<?php
//session_destroy();
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../db/db.php';

//if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (isset($_POST['email']) && isset($_POST['password']) ) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['password'];

    // Evita SQL injection
    $nomeU = mysqli_real_escape_string($conn, $nome);
    $utilizador = mysqli_real_escape_string($conn, $telefone);
    $senha = mysqli_real_escape_string($conn, $senha);
 
    // Consulta para verificar o login
    $query = "INSERT INTO trabalhadores (nome, email, senha) VALUES ('$nomeU', '$utilizador', '$senha')";
    $resultadoDB = $conn->query($query);

    header("Location: ../root/index.php");
        
    } else {
        // Login falhou
        header("Location: ../root/index.php");
        die();
        echo "Login falhou. Introduziu o email e password erradas! Tente Novamente.";
        echo "<p><a href='../login-trabalhador/login.php'>Voltar ao Login</a></p>";   
}

// Fechar a conexão da BD
$conn->close();
?>