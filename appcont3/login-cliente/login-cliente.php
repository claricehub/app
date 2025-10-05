<?php
session_start();
require_once '../db/db.php';
include '../include/header.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $cliente = $resultado->fetch_assoc();

        if (password_verify($senha, $cliente['senha'])) {
            $_SESSION['cliente_id'] = $cliente['id'];
            $_SESSION['nome'] = $cliente['nome'];
            $_SESSION['email'] = $cliente['email'];
            $_SESSION['logado'] = true;

            // Redirecionamento seguro com URL completa
            if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
                $redirect = urldecode($_GET['redirect']);

                // Segurança: só permite caminhos internos
                if (str_starts_with($redirect, '../user/') && str_contains($redirect, '.php')) {
                    header("Location: $redirect");
                } else {
                    header("Location: ../root/index.php");
                }
            } else {
                header("Location: ../root/index.php");
            }
            exit();
        } else {
            $erro = "Palavra-passe incorreta.";
        }
    } else {
        $erro = "Email não encontrado.";
    }

    $stmt->close();
}
?>

<!-- Login Section -->
<section class="py-5">
    <div class="container px-5">
        <div class="bg-light rounded-3 py-5 px-4 px-md-5 mb-5">
            <div class="text-center mb-5">
                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3">
                    <i class="bi bi-person-circle"></i>
                </div>
                <h1 class="fw-bolder">Login do Cliente</h1>
                <p class="lead fw-normal text-muted mb-0">Acesse sua conta para continuar</p>
            </div>

            <?php if ($erro): ?>
                <div class="alert alert-danger text-center"><?php echo $erro; ?></div>
            <?php endif; ?>

            <div class="row gx-5 justify-content-center">
                <div class="col-lg-8 col-xl-6">
                    <form method="POST" action="">
                        <!-- Email -->
                        <div class="form-floating mb-3">
                            <input class="form-control" id="email" name="email" type="email" placeholder="nome@exemplo.com" required />
                            <label for="email">Email</label>
                        </div>

                        <!-- Senha -->
                        <div class="form-floating mb-3">
                            <input class="form-control" id="senha" name="senha" type="password" placeholder="Palavra-passe" required />
                            <label for="senha">Palavra-passe</label>
                        </div>

                        <!-- Botão -->
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg" type="submit">Entrar</button>
                        </div>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="../cadastro-cliente/cadastro.php">Ainda não tem conta? Cadastre-se</a>
                        <div class="mt-2 text-center">
    <a href="../service/esqueci-senha.php">Esqueceu a sua palavra-passe?</a>
</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</main>

<?php include '../include/footer.php'; ?>
