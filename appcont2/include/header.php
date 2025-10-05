
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Portal de Serviços</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column h-100">
<main class="flex-shrink-0">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container px-5">
        <a href="../root/index.php" class="navbar-brand">Portal de Serviços</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav align-items-center gap-2">
                <li class="nav-item"><a class="nav-link" href="../root/index.php">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="../root/escolha-cadastro.php">Registar</a></li>

                <?php if (isset($_SESSION['cliente_id']) && $_SESSION['logado'] === true): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../user/perfil-cliente.php">
                            <i class="bi bi-person-circle"></i> Meu Perfil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../login-cliente/logout.php">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </a>
                    </li>

                <?php elseif (isset($_SESSION['trabalhador_id']) && $_SESSION['logado'] === true): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../user/perfil-trabalhador.php">
                            <i class="bi bi-person-circle"></i> Meu Perfil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../login-trabalhador/logout.php">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </a>
                    </li>

                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../root/escolha-o-seu-login.php">
                            <i class="bi bi-box-arrow-in-right"></i> Entrar
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
