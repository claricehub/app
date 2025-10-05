<?php include '../include/header.php'; ?>

<div class="container px-5 my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bolder">Registar como</h2>
        <p class="lead mb-0">Escolha se deseja registar-se como Cliente ou como Trabalhador</p>
    </div>
    <div class="row gx-5 row-cols-1 row-cols-md-2 justify-content-center">
        <style>
            .card-hover {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .card-hover:hover {
                transform: translateY(-8px);
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
            }
        </style>

        <!-- Cadastro como Cliente -->
        <div class="col mb-5">
            <a href="../cadastro-cliente/cadastro.php" style="text-decoration: none; color: inherit;">
                <div class="card card-hover h-100 shadow border-0 text-center">
                    <div class="card-body p-4">
                        <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <h5 class="card-title mb-3">Sou Cliente</h5>
                        <p class="card-text mb-0">Quero registar-me para contratar profissionais.</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Cadastro como Trabalhador -->
        <div class="col mb-5">
            <a href="../cadastro-trabalhador/cadastro.php" style="text-decoration: none; color: inherit;">
                <div class="card card-hover h-100 shadow border-0 text-center">
                    <div class="card-body p-4">
                        <div class="feature bg-success bg-gradient text-white rounded-3 mb-3">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <h5 class="card-title mb-3">Sou Trabalhador</h5>
                        <p class="card-text mb-0">Quero registar-me para oferecer os meus serviços.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Link centralizado -->
    <div class="text-center">
        <a href="../root/escolha-o-seu-login.php">Já tem conta? Faça login</a>
    </div>
</div>

<?php include '../include/footer.php'; ?>
