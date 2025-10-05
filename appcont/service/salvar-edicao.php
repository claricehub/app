<?php
session_start();
require_once '../db/db.php';

if (!isset($_SESSION['trabalhador_id'])) {
  header("Location: ../login-trabalhador/login.php");
  exit();
}

$id = $_SESSION['trabalhador_id']; // Agora $id está disponível para todo o script

if (!empty($_FILES['imagens']['name'][0])) {
  $pasta = dirname(__DIR__) . '/uploads/servicos';

  if (!is_dir($pasta)) {
    mkdir($pasta, 0777, true);
  }

  foreach ($_FILES['imagens']['tmp_name'] as $index => $tmpName) {
    $nomeOriginal = $_FILES['imagens']['name'][$index];
    $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
    $permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($extensao, $permitidos)) {
      $novoNome = 'servico_' . $id . '_' . uniqid() . '.' . $extensao;
      $destino = $pasta . '/' . $novoNome;

      if (move_uploaded_file($tmpName, $destino)) {
        $stmt = $conn->prepare("INSERT INTO servico_imagens (id_trabalhador, nome_arquivo) VALUES (?, ?)");
        $stmt->bind_param("is", $id, $novoNome);
        $stmt->execute();
        $stmt->close();
      }
    }
  }
}

$titulo = $_POST['titulo'];
$texto1 = $_POST['texto1'];

$stmt = $conn->prepare("UPDATE trabalhadores SET titulo = ?, texto1 = ? WHERE id = ?");
$stmt->bind_param("ssi", $titulo, $texto1, $id);

if ($stmt->execute()) {
  $stmt->close();
  header("Location: ../user/perfil-trabalhador.php?sucesso=1");
  exit();
} else {
  echo "Erro ao salvar: " . $stmt->error;
  $stmt->close();
}
?>
