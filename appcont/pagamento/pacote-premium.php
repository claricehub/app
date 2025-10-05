



































<?php 
session_start();
require_once '../db/db.php';
include '../include/header-trabalhador.php'; ?>
            </nav>
            <!-- Seção de Planos -->
            <section class="bg-light py-5">
                <div class="container px-5 my-5">
                    <div class="text-center mb-5">
                        <h1 class="fw-bolder">Escolha seu Pacote</h1>
                        <p class="lead fw-normal text-muted mb-0">Selecione o plano ideal para destacar seu perfil e conquistar mais oportunidades!</p>
                    </div>
                    <div class="row gx-5 justify-content-center">
                        <!-- Pacote Grátis -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-secondary">
                                <div class="card-body p-5 d-flex flex-column">
                                    <div class="small text-uppercase fw-bold text-muted text-center mb-3">Pacote Grátis</div>
                                    <div class="mb-3 text-center">
                                        <span class="display-5 fw-bold">€0</span>
                                        <span class="text-muted">/ mês</span>
                                    </div>
                                    <ul class="list-unstyled mb-4 flex-grow-1">
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Perfil básico no sistema
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Acesso a oportunidades públicas
                                        </li>
                                        <li class="mb-2 text-muted">
                                            <i class="bi bi-x"></i>
                                            Não fica em destaque
                                        </li>
                                        <li class="mb-2 text-muted">
                                            <i class="bi bi-x"></i>
                                            Não pode adicionar foto de perfil
                                        </li>
                                        <li class="mb-2 text-muted">
                                            <i class="bi bi-x"></i>
                                            Não pode adicionar fotos do trabalho
                                        </li>
                                        <li class="mb-2 text-muted">
                                            <i class="bi bi-x"></i>
                                            Não pode controlar comentários
                                        </li>
                                        <li class="mb-2 text-muted">
                                            <i class="bi bi-x"></i>
                                            Suporte prioritário
                                        </li>
                                    </ul>
                                    <div class="d-grid"><a class="btn btn-outline-primary" href="../pagamento/pagamento.php?plano_id=1">Selecionar</a></div>
                                </div>
                            </div>
                        </div>
                        <!-- Pacote Pro -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-primary shadow-lg" style="border-width:2px;">
                                <div class="card-body p-5 d-flex flex-column">
                                    <div class="small text-uppercase fw-bold text-primary text-center mb-3">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        Pro
                                    </div>
                                    <div class="mb-3 text-center">
                                        <span class="display-5 fw-bold">€1</span>
                                        <span class="text-muted">/ mês</span>
                                    </div>
                                    <ul class="list-unstyled mb-4 flex-grow-1">
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Perfil em <strong>destaque por 1 mês</strong>
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Pode adicionar <strong>foto de perfil</strong>
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Pode adicionar <strong>fotos do seu trabalho</strong> na página principal
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Mais visibilidade para clientes
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Aumenta as chances de ser contratado
                                        </li>
                                        <li class="mb-2 text-muted">
                                            <i class="bi bi-x"></i>
                                            Não pode controlar comentários
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Suporte prioritário
                                        </li>
                                    </ul>
                                    <div class="alert alert-info text-center py-2 mb-3">
                                        <strong>Vantagens do destaque:</strong><br>
                                        Seu perfil aparece no topo das buscas, pode mostrar seu rosto e seu trabalho, recebe mais visualizações e aumenta as chances de ser escolhido por clientes!
                                    </div>
                                    <div class="d-grid"><a class="btn btn-primary btn-lg" href="../pagamento/pagamento.php?plano_id=2">Selecionar Pro</a></div>
                                </div>
                            </div>
                        </div>
                        <!-- Pacote Premium -->
                        <div class="col-lg-4 col-md-12 mb-4">
                            <div class="card h-100 border-dark">
                                <div class="card-body p-5 d-flex flex-column">
                                    <div class="small text-uppercase fw-bold text-dark text-center mb-3">Premium</div>
                                    <div class="mb-3 text-center">
                                        <span class="display-5 fw-bold">€3</span>
                                        <span class="text-muted">/ 1 mês</span>
                                    </div>
                                    <ul class="list-unstyled mb-4 flex-grow-1">
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Perfil em <strong>destaque por 6 meses</strong> (acima de todos nos serviços)
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Pode adicionar <strong>foto de perfil</strong>
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Pode adicionar <strong>várias fotos do seu trabalho</strong> no perfil e na página principal
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Controle total dos comentários: pode apagar comentários de clientes no seu perfil
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Máxima visibilidade para clientes
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Muito mais chances de ser contratado
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-check text-primary"></i>
                                            Suporte premium e prioritário
                                        </li>
                                    </ul>
                                    <div class="alert alert-success text-center py-2 mb-3">
                                        <strong>Vantagens de estar em destaque:</strong><br>
                                        Seu perfil ficará no topo das buscas por 6 meses, com foto e portfólio completo, sendo visto por mais clientes e aumentando muito suas chances de fechar novos trabalhos e parcerias! Você ainda pode controlar os comentários recebidos.
                                    </div>
                                    <div class="d-grid"><a class="btn btn-outline-dark btn-lg" href="../pagamento/pagamento.php?plano_id=3">Selecionar Premium</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <!-- Footer-->
        <?php include '../include/footer.php'; ?>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
                      

