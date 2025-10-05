<?php
// Inicia a sessão
session_start();
$_SESSION['logado'] = false;
// Destrói todas as variáveis de sessão
session_destroy();
echo "sessão apagada";

// Redireciona para a página de login
header("Location: ../root/index.php");
exit();
?>