<?php
require_once '../db/db.php';

$nome = 'Admin Principal';
$email = 'clariceveiga04@gmail.com';
$senha = password_hash('clarice1', PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admins (nome, email, senha) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nome, $email, $senha);
$stmt->execute();
echo "Admin criado com sucesso!";
?>
