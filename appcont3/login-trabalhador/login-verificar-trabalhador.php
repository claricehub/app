<?php
//session_destroy();
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../db/db.php';

//if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (isset($_POST['email']) && isset($_POST['password']) ) {

    $email = $_POST['email'];
    $senha = $_POST['password'];

    // Evita SQL injection
    $utilizador = mysqli_real_escape_string($conn, $email);
    $senha = mysqli_real_escape_string($conn, $senha);
 
    // Consulta para verificar o login
    $query = "SELECT * FROM clientes WHERE email='$email' AND senha='$senha'";
    $resultadoDB = $conn->query($query);

    if ($resultadoDB->num_rows == 1) {
        // Login bem-sucedido
        $linha = $resultadoDB->fetch_assoc();
        //$linha = array_map('utf8_encode', $linha); // forçar os caracteres em Português 
	
        // Armazena as informações do utilizador na sessão
        $_SESSION['idUtilizador'] = $linha['id'];
        $_SESSION['nome'] = $linha['nome'];
        $_SESSION['telefone'] = $linha['telefone'];
        $_SESSION['nivelPermissao'] = 1;
        $_SESSION['logado'] = true;

        // Redireciona para a página de boas-vindas
        header("Location: ../root/index.php");
        exit();
    } else {
        // Login falhou
        $_SESSION['logado'] = false;
        header("Location: ../root/index.php");
        die();
        echo "Login falhou. Introduziu o telefone e password erradas! Tente Novamente.";
        echo "<p><a href='../login-trabalhador/login.php'>Voltar ao Login</a></p>";
    }
    
} else {
	echo ("Ups! Utilizador e Password errados");
}

// Fechar a conexão da BD
$conn->close();
?>