<?php
session_start();
require_once '../db/db.php';
include '../include/header.php';

// Verifica se o trabalhador está logado
if (!isset($_SESSION['trabalhador_id'])) {
  header("Location: ../login-trabalhador/login.php");
  exit();
}

// Busca os dados do trabalhador
$id = $_SESSION['trabalhador_id'];
$stmt = $conn->prepare("SELECT * FROM trabalhadores WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$trabalhador = $result->fetch_assoc();
$stmt->close();

// Verifica se o trabalhador tem plano ativo diferente do grátis (id=1)
$temPlanoPago = false;
$stmtPlano = $conn->prepare("SELECT plano_id FROM assinaturas WHERE trabalhador_id = ? AND ativo = 1 AND fim >= CURDATE() ORDER BY plano_id DESC LIMIT 1");
$stmtPlano->bind_param("i", $id);
$stmtPlano->execute();
$resultPlano = $stmtPlano->get_result();
if ($rowPlano = $resultPlano->fetch_assoc()) {
    if ($rowPlano['plano_id'] != 1) {
        $temPlanoPago = true;
    }
}
$stmtPlano->close();
?>


  <style>
    .form-container {
      max-width: 700px;
      margin: auto;
      background: #fff;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">
<main class="flex-shrink-0">


  <!-- Page content -->
  <section class="py-5">
    <div class="container px-5">
      <a href="../user/perfil-trabalhador.php" class="btn btn-secondary mb-4"><i class="bi bi-arrow-left"></i> Voltar ao Perfil</a>
      <div class="form-container">
        <h3 class="mb-4 text-center">Editar Perfil</h3>
<form action="../service/salvar-edicao.php" method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($trabalhador['titulo'] ?? '') ?>" required>
          </div>

          <div class="mb-3">
            <label for="texto1" class="form-label">Texto 1</label>
            <textarea name="texto1" class="form-control" rows="4" required><?= htmlspecialchars($trabalhador['texto1'] ?? '') ?></textarea>
          </div>

<?php if ($temPlanoPago): ?>
  <div class="mb-3">
    <label for="imagens[]" class="form-label">Imagens do seu trabalho (você pode enviar várias)</label>
    <input type="file" name="imagens[]" class="form-control" accept="image/*" multiple>
  </div>
<?php else: ?>
  <div class="mb-3">
    <label class="form-label text-muted">Imagens do seu trabalho (disponível apenas para assinantes Pro ou Premium)</label>
    <div class="alert alert-warning mb-0">Para adicionar fotos do seu trabalho, faça upgrade para um plano Pro ou Premium. <a href="../pagamento/pacote-premium.php">Ver planos</a></div>
  </div>
<?php endif; ?>

          <div class="d-grid">
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
          </div>
        </form>
      </div>
    </div>
  </section>

</main>
<?php
include '../include/footer.php'; ?>
