<?php
session_start();
require_once '../db/db.php';

$erro = '';
$sucesso = '';

// Verifica se o ID do cliente foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID do cliente não fornecido.");
}

$id = $_GET['id'];

// Busca os dados atuais do cliente, agora incluindo telefone, morada e contribuinte
$stmt = $conn->prepare("SELECT nome, email, telefone, morada, contribuinte FROM clientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $morada = $_POST['morada'] ?? '';
    $contribuinte = $_POST['contribuinte'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!empty($senha) && strlen($senha) < 6) {
        $erro = "A palavra-passe deve ter no mínimo 6 caracteres.";
    } else {
        if (!empty($senha)) {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE clientes SET nome = ?, email = ?, telefone = ?, morada = ?, contribuinte = ?, senha = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $nome, $email, $telefone, $morada, $contribuinte, $senha_hash, $id);
        } else {
            $stmt = $conn->prepare("UPDATE clientes SET nome = ?, email = ?, telefone = ?, morada = ?, contribuinte = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $nome, $email, $telefone, $morada, $contribuinte, $id);
        }

        if ($stmt->execute()) {
            header("Location: ../admin-cliente/admin-cliente-pag.php");
            exit;
        } else {
            $erro = "Erro ao atualizar: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Editar Cliente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="../css/styles.css" rel="stylesheet" />
</head>
<body class="bg-light d-flex flex-column min-vh-100">
<main class="flex-grow-1">
  <section class="py-5">
    <div class="container px-5">
      <div class="bg-white rounded-3 py-5 px-4 px-md-5 shadow">
        <div class="text-center mb-5">
          <div class="feature bg-primary bg-gradient text-white rounded-3 mb-3">
            <i class="bi bi-person-lines-fill"></i>
          </div>
          <h1 class="fw-bolder">Editar Cliente</h1>
          <p class="lead text-muted">Atualize os dados do cliente</p>
        </div>

        <?php if (!empty($erro)): ?>
          <div class="alert alert-danger text-center"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <div class="row justify-content-center">
          <div class="col-lg-8 col-xl-6">
            <form method="POST">
              <!-- Nome -->
              <div class="form-floating mb-3">
                <input class="form-control" id="nome" name="nome" type="text" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required />
                <label for="nome">Nome completo</label>
              </div>

              <!-- Email -->
              <div class="form-floating mb-3">
                <input class="form-control" id="email" name="email" type="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" required />
                <label for="email">Email</label>
              </div>

              <!-- Telefone -->
              <div class="form-floating mb-3">
                <input class="form-control" id="telefone" name="telefone" type="text" value="<?php echo htmlspecialchars($cliente['telefone'] ?? ''); ?>" />
                <label for="telefone">Telefone</label>
              </div>

              <!-- Morada -->
              <div class="form-floating mb-3">
                <input class="form-control" id="morada" name="morada" type="text" value="<?php echo htmlspecialchars($cliente['morada'] ?? ''); ?>" />
                <label for="morada">Morada</label>
              </div>

              <!-- Contribuinte -->
              <div class="form-floating mb-3">
                <input class="form-control" id="contribuinte" name="contribuinte" type="text" value="<?php echo htmlspecialchars($cliente['contribuinte'] ?? ''); ?>" />
                <label for="contribuinte">Número de Contribuinte</label>
              </div>

              <!-- Senha -->
              <div class="form-floating mb-3">
                <input class="form-control" id="senha" name="senha" type="password" placeholder="Nova senha" />
                <label for="senha">Nova palavra-passe (deixe em branco para manter)</label>
              </div>

              <!-- Botão -->
              <div class="d-grid">
                <button class="btn btn-primary btn-lg" type="submit">Guardar Alterações</button>
              </div>
            </form>
            <div class="mt-3 text-center">
              <a href="../admin-cliente/admin-cliente-pag.php">Voltar para o painel</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include '../include/footer.php'; ?>

<script>
function toggleSenha() {
  const senhaInput = document.getElementById("senha");
  senhaInput.type = senhaInput.type === "password" ? "text" : "password";
}
</script>
</body>
</html>
