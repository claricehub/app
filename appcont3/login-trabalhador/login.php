<?php
session_start();
require_once '../db/db.php';
include '../include/header.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM trabalhadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $trabalhador = $resultado->fetch_assoc();

        if (password_verify($senha, $trabalhador['password'])) {
            $_SESSION['trabalhador_id'] = $trabalhador['id'];
            $_SESSION['nome'] = $trabalhador['nome'];
            $_SESSION['email'] = $trabalhador['email'];
            $_SESSION['admin'] = $trabalhador['admin'];
            header("Location: ../user/perfil-trabalhador.php");
            exit();
        } else {
            $erro = "Senha incorreta.";
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
                    <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3"><i class="bi bi-person-circle"></i></div>
                    <h1 class="fw-bolder">Login do Trabalhador</h1>
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
                                <input class="form-control" id="senha" name="senha" type="password" placeholder="Senha" required />
                                <label for="senha">Senha</label>
                            </div>

                            <!-- Botão -->
                            <div class="d-grid">
                                <button class="btn btn-primary btn-lg" type="submit">Entrar</button>
                            </div>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="../cadastro-trabalhador/cadastro.php">Ainda não tem conta? Cadastre-se</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php include '../include/footer.php'; ?>