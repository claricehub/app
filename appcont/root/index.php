<?php

// Ativar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



require_once '../db/db.php';
include '../include/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<STYLE>
.card-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
}
</STYLE>

<header class="bg-dark py-5">
    <div class="container px-5">
        <div class="row gx-5 align-items-center justify-content-center">
            <div class="col-lg-8 col-xl-7 col-xxl-6">
                <div class="my-5 text-center text-xl-start">
                    <h1 class="display-5 fw-bolder text-white mb-2">Encontre o profissional ideal</h1>
                    <p class="lead fw-normal text-white-50 mb-4">Conectamos-lhe aos melhores trabalhadores da sua região.</p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center justify-content-xl-start">
                        <?php if (isset($_SESSION['cliente_id'])): ?>
                            <a class="btn btn-primary btn-lg px-4 me-sm-3" href="../user/perfil-cliente.php">Ir para meu perfil</a>
                        <?php elseif (isset($_SESSION['trabalhador_id'])): ?>
                            <a class="btn btn-primary btn-lg px-4 me-sm-3" href="../user/perfil-trabalhador.php">Ir para meu perfil</a>
                        <?php else: ?>
                            <a class="btn btn-primary btn-lg px-4 me-sm-3" href="../root/escolha-cadastro.php">Registar</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-xxl-6 d-none d-xl-block text-center">
                <img class="img-fluid rounded-3 my-5" src="https://img.freepik.com/fotos-premium/homem-trabalhador-com-capacete-no-fundo-desfocado_488220-9599.jpg" alt="Trabalhador com capacete" />
            </div>
        </div>
    </div>
</header>

<!-- Restante do seu código... -->

    <!-- Categorias (Features) -->
    <section class="py-5" id="categorias">
        <div class="container px-5 my-5">
            <div class="text-center mb-5">
                <h2 class="fw-bolder">Serviços</h2>
                <p class="lead mb-0">Escolha o serviço que melhor atende à sua necessidade</p>
            </div>
            <div class="row gx-5 row-cols-1 row-cols-md-2 row-cols-xl-3">

             <?php
$sql = "SELECT * FROM categorias";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="col mb-5">
            <a href="servicos.php?categoria=<?php echo $row['id']; ?>" style="text-decoration: none; color: inherit;">
                <div class="card card-hover h-100 shadow border-0">
                    <div class="card-body p-4">
                        <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3">
                            <i class="bi bi-tools"></i>
                        </div>
                        <h5 class="card-title mb-3"><?php echo htmlspecialchars($row['categoria']); ?></h5>
                        <p class="card-text mb-0"><?php echo htmlspecialchars($row['descricao']); ?></p>
                    </div>
                </div>
            </a>
        </div>
        <?php
    }
} else {
    echo "<p class='text-muted'>Nenhuma categoria encontrada.</p>";
}
?>


            </div>
        </div>
    </section>

</main>

<?php include '../include/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>