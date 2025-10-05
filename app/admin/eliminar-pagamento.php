<?php
require_once '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pagamento_id'])) {
    $id = (int) $_POST['pagamento_id'];
    $conn->query("DELETE FROM pagamentos WHERE id = $id");
}

header("Location: admin-pagamentos.php");
exit; 