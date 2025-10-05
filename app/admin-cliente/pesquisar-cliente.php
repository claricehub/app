<?php
require_once '../db/db.php';
$q = $_GET['q'] ?? '';
$out = [];
if (strlen($q) >= 2) {
    $stmt = $conn->prepare("SELECT id, nome FROM clientes WHERE nome LIKE CONCAT('%', ?, '%') LIMIT 10");
    $stmt->bind_param("s", $q);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $out[] = $row;
    }
    $stmt->close();
}
header('Content-Type: application/json');
echo json_encode($out);
