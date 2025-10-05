<?php
require_once '../db/db.php';
 include '../include/header.php'; ?>

    
<div class="container px-5 my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bolder">Aceder como</h2>
        <p class="lead mb-0">Escolha se deseja entrar como Cliente ou como Trabalhador</p>
    </div>
    <div class="row gx-5 row-cols-1 row-cols-md-2 justify-content-center">
<style> .card-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
}
</style>
<!-- Login como Cliente -->
<div class="col mb-5">
    <a href="../login-cliente/login-cliente.php" style="text-decoration: none; color: inherit;">
        <div class="card card-hover h-100 shadow border-0 text-center">
            <div class="card-body p-4">
                <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3">
                    <i class="bi bi-person"></i>
                </div>
                <h5 class="card-title mb-3">Sou Cliente</h5>
                <p class="card-text mb-0">Quero contratar um profissional para um serviço.</p>
            </div>
        </div>
    </a>
</div>

<!-- Login como Trabalhador -->
<div class="col mb-5">
    <a href="../login-trabalhador/login.php" style="text-decoration: none; color: inherit;">
        <div class="card card-hover h-100 shadow border-0 text-center">
            <div class="card-body p-4">
                <div class="feature bg-success bg-gradient text-white rounded-3 mb-3">
                    <i class="bi bi-person-badge"></i>
                </div>
                <h5 class="card-title mb-3">Sou Trabalhador</h5>
                <p class="card-text mb-0">Quero oferecer os meus serviços e encontrar oportunidades.</p>
            </div>
        </div>
    </a>
</div>
    </div>
       <div class="text-center mt-3">
    <a href="../root/escolha-cadastro.php">Ainda não tem conta? Registe-se</a>
</div>
</div>  
<?php include '../include/footer.php'; ?>