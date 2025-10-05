<?php
// Inicia a sessão para controlar login do usuário
session_start();
// Inclui o arquivo de conexão com o banco de dados
require_once '../db/db.php';
// Inclui o cabeçalho do site
include '../include/header.php';

// Inicializa variáveis de erro e sucesso
$erro = '';
$sucesso = '';

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $morada = $_POST['morada'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $contribuinte = $_POST['contribuinte'] ?? '';

    // Validação da senha
    if (strlen($senha) < 6) {
        $erro = "A palavra-passe deve ter no mínimo 6 caracteres.";
    // Validação do telefone (mínimo 9 dígitos)
    } elseif (!empty($telefone) && !preg_match('/^\d{9,}$/', $telefone)) {
        $erro = "Telemóvel inválido. Deve conter pelo menos 9 dígitos.";
    // Validação do contribuinte (exatamente 9 dígitos)
    } elseif (!empty($contribuinte) && !preg_match('/^\d{9}$/', $contribuinte)) {
        $erro = "Número de contribuinte inválido. Deve conter exatamente 9 dígitos.";
    } else {
        // Criptografa a senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Prepara a query para inserir o cliente
        $stmt = $conn->prepare("INSERT INTO clientes (nome, email, senha, morada, telefone, contribuinte) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nome, $email, $senha_hash, $morada, $telefone, $contribuinte);

        // Executa a query e verifica se foi bem-sucedida
        if ($stmt->execute()) {
            // Login automático após cadastro
            $_SESSION['cliente_id'] = $stmt->insert_id;
            $_SESSION['nome'] = $nome;
            $_SESSION['email'] = $email;
            $_SESSION['logado'] = true;

            // Redireciona para a página original, se houver parâmetro redirect
            if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
                $redirect = urldecode($_GET['redirect']);

                // Segurança: só permite caminhos internos
                if (str_starts_with($redirect, '../user/') && str_contains($redirect, '.php')) {
                    header("Location: $redirect");
                } else {
                    header("Location: ../user/perfil-cliente.php");
                }
            } else {
                // Redireciona para o perfil do cliente
                header("Location: ../user/perfil-cliente.php");
            }
            exit();
        } else {
            // Exibe erro caso não consiga cadastrar
            $erro = "Erro ao criar conta: " . $stmt->error;
        }

        // Fecha o statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <!-- Metadados e links de CSS -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Registo de Cliente</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column h-100">
<main class="flex-shrink-0">

    <!-- Seção de cadastro -->
    <section class="py-5">
        <div class="container px-5">
            <div class="bg-light rounded-3 py-5 px-4 px-md-5 mb-5">
                <div class="text-center mb-5">
                    <!-- Ícone de usuário -->
                    <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-person-plus"></i></div>
                    <h1 class="fw-bolder">Registo de Cliente</h1>
                    <p class="lead fw-normal text-muted mb-0">Crie a sua conta para contratar profissionais</p>
                </div>

                <!-- Exibe mensagens de erro ou sucesso -->
                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger text-center"><?php echo htmlspecialchars($erro); ?></div>
                <?php elseif (!empty($sucesso)): ?>
                    <div class="alert alert-success text-center"><?php echo $sucesso; ?></div>
                <?php endif; ?>

                <div class="row gx-5 justify-content-center">
                    <div class="col-lg-8 col-xl-6">
                        <!-- Formulário de cadastro -->
                        <form method="POST" action="">
                            <!-- Campo nome -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="nome" name="nome" type="text" placeholder="O seu nome" required />
                                <label for="nome">Nome completo</label>
                            </div>

                            <!-- Campo email -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" name="email" type="email" placeholder="nome@exemplo.com" required />
                                <label for="email">Email</label>
                            </div>

                            <!-- Campo morada -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="morada" name="morada" type="text" placeholder="Morada" required />
                                <label for="morada">Morada</label>
                            </div>

                            <!-- Campo telefone -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="telefone" name="telefone" type="tel" placeholder="Telemóvel" pattern="\d{9,}" required />
                                <label for="telefone">Telemóvel</label>
                            </div>

                            <!-- Campo contribuinte -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="contribuinte" name="contribuinte" type="text" placeholder="NIF" pattern="\d{9}" maxlength="9" required />
                                <label for="contribuinte">NIF (Contribuinte)</label>
                            </div>

                            <!-- Campo senha -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="senha" name="senha" type="password" placeholder="Palavra-passe" required />
                                <label for="senha">Palavra-passe (mínimo 6 caracteres)</label>
                            </div>

                            <!-- Botão de envio -->
                            <div class="d-grid">
                                <button class="btn btn-primary btn-lg" type="submit">Registar</button>
                            </div>
                        </form>
                        <!-- Link para login caso já tenha conta -->
                        <div class="mt-3 text-center">
                            <a href="../login-cliente/login-cliente.php">Já tem conta? Inicie sessão</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<!-- Inclui o rodapé -->
<?php include '../include/footer.php'; ?>
<!-- Scripts do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
