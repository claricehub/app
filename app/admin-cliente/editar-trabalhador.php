<?php
require_once '../db/db.php';

$erro = '';
$sucesso = '';

// Verifica se o ID do trabalhador foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
  die("ID do trabalhador não fornecido.");
}

$trabalhador_id = (int) $_GET['id'];

// Busca o trabalhador
$stmt = $conn->prepare("SELECT * FROM trabalhadores WHERE id = ?");
$stmt->bind_param("i", $trabalhador_id);
$stmt->execute();
$result = $stmt->get_result();
$trabalhador = $result->fetch_assoc();
$stmt->close();

if (!$trabalhador) {
  die("Trabalhador não encontrado.");
}

// Busca profissões atuais do trabalhador
$profissoes_atuais = [];
$stmt = $conn->prepare("SELECT profissao_id FROM trabalhador_profissao WHERE trabalhador_id = ?");
$stmt->bind_param("i", $trabalhador_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $profissoes_atuais[] = $row['profissao_id'];
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = trim($_POST['nome']);
  $email = trim($_POST['email']);
  $telefone = trim($_POST['telefone']);
  $zona = $_POST['zona'];
  $profissoes = $_POST['profissao'] ?? [];
  $senha = $_POST['senha'];

  if (empty($nome) || empty($email)) {
    $erro = "Nome e email são obrigatórios.";
  } elseif (!empty($senha) && strlen($senha) < 6) {
    $erro = "A palavra-passe deve ter no mínimo 6 caracteres.";
  } else {
    // Atualiza dados básicos
    if (!empty($senha)) {
      $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("UPDATE trabalhadores SET nome = ?, email = ?, telefone = ?, zona = ?, password = ? WHERE id = ?");
      $stmt->bind_param("sssssi", $nome, $email, $telefone, $zona, $senha_hash, $trabalhador_id);
    } else {
      $stmt = $conn->prepare("UPDATE trabalhadores SET nome = ?, email = ?, telefone = ?, zona = ? WHERE id = ?");
      $stmt->bind_param("ssssi", $nome, $email, $telefone, $zona, $trabalhador_id);
    }

    if ($stmt->execute()) {
      // Atualiza profissões
      $conn->query("DELETE FROM trabalhador_profissao WHERE trabalhador_id = $trabalhador_id");
      $insertProf = $conn->prepare("INSERT INTO trabalhador_profissao (trabalhador_id, profissao_id) VALUES (?, ?)");
      foreach ($profissoes as $prof_id) {
        $insertProf->bind_param("ii", $trabalhador_id, $prof_id);
        $insertProf->execute();
      }
      $insertProf->close();

      header("Location: ../admin-cliente/admin-pedidos.php");
      exit();
    } else {
      $erro = "Erro ao atualizar trabalhador: " . $stmt->error;
    }

    $stmt->close();
  }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Trabalhador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Editar Trabalhador #<?= htmlspecialchars($trabalhador_id) ?></h2>
    
    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($trabalhador['nome']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($trabalhador['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($trabalhador['telefone'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Zona</label>
            <select name="zona" class="form-select" required>
                <option value="" disabled>Selecione uma zona</option>
                <?php
                $res = $conn->query("SELECT id, zona FROM zona ORDER BY zona");
                while ($z = $res->fetch_assoc()) {
                    $selected = ($trabalhador['zona'] == $z['id']) ? 'selected' : '';
                    echo "<option value='{$z['id']}' $selected>" . htmlspecialchars($z['zona']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Profissões</label>
            <div class="row">
                <?php
                $res = $conn->query("SELECT id, categoria FROM categorias ORDER BY categoria");
                while ($cat = $res->fetch_assoc()) {
                    $checked = in_array($cat['id'], $profissoes_atuais) ? 'checked' : '';
                    echo "<div class='col-md-6'>
                            <div class='form-check'>
                              <input class='form-check-input' type='checkbox' name='profissao[]' value='{$cat['id']}' id='prof{$cat['id']}' $checked>
                              <label class='form-check-label' for='prof{$cat['id']}'>" . htmlspecialchars($cat['categoria']) . "</label>
                            </div>
                          </div>";
                }
                ?>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Nova Palavra-passe</label>
            <input type="password" name="senha" class="form-control" placeholder="Deixe em branco para manter a palavra-passe atual">
            <div class="form-text">Deixe em branco para manter a palavra-passe atual</div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="../admin-cliente/admin-pedidos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html> 