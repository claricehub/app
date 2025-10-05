<?php
require_once '../db/db.php';

// CRIAÇÃO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nova_categoria'])) {
        $cat = $_POST['categoria'];
        $desc = $_POST['descricao'];
        $stmt = $conn->prepare("INSERT INTO categorias (categoria, descricao) VALUES (?, ?)");
        $stmt->bind_param("ss", $cat, $desc);
        $stmt->execute();
    }

    if (isset($_POST['nova_zona'])) {
        $zona = $_POST['zona_nome'];
        $stmt = $conn->prepare("INSERT INTO zona (zona) VALUES (?)");
        $stmt->bind_param("s", $zona);
        $stmt->execute();
    }

    if (isset($_POST['atualizar_categoria'])) {
        $id = $_POST['atualizar_categoria'];
        $cat = $_POST['categoria'];
        $desc = $_POST['descricao'];
        $stmt = $conn->prepare("UPDATE categorias SET categoria = ?, descricao = ? WHERE id = ?");
        $stmt->bind_param("ssi", $cat, $desc, $id);
        $stmt->execute();
    }

    if (isset($_POST['atualizar_zona'])) {
        $id = $_POST['atualizar_zona'];
        $zona = $_POST['zona_nome'];
        $stmt = $conn->prepare("UPDATE zona SET zona = ? WHERE id = ?");
        $stmt->bind_param("si", $zona, $id);
        $stmt->execute();
    }
}

// EXCLUSÃO
if (isset($_GET['excluir_categoria'])) {
    $id = $_GET['excluir_categoria'];
    $conn->query("DELETE FROM trabalhador_profissao WHERE profissao_id = $id");
    $conn->query("DELETE FROM categorias WHERE id = $id");
}

if (isset($_GET['excluir_zona'])) {
    $id = $_GET['excluir_zona'];
    $conn->query("DELETE FROM zona WHERE id = $id");
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Profissões e Zonas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="text-center mb-4">
        <h1 class="fw-bold text-primary"><i class="bi bi-tools"></i> Gestão de Profissões e Zonas</h1>
        <p class="text-muted">Adicione, edite ou remova categorias e zonas conforme necessidade.</p>
    </div>

    <?php
    // EDITAR CATEGORIA
    if (isset($_GET['editar_categoria'])) {
        $id = $_GET['editar_categoria'];
        $res = $conn->query("SELECT * FROM categorias WHERE id = $id");
        if ($res->num_rows === 1) {
            $cat = $res->fetch_assoc();
            echo "
            <div class='alert alert-warning'>
                <form method='POST'>
                    <input type='hidden' name='atualizar_categoria' value='$id'>
                    <h5><i class='bi bi-pencil'></i> Editar Categoria</h5>
                    <div class='mb-2'>
                        <input type='text' name='categoria' class='form-control' value='" . htmlspecialchars($cat['categoria']) . "' required>
                    </div>
                    <div class='mb-2'>
                        <textarea name='descricao' class='form-control' required>" . htmlspecialchars($cat['descricao']) . "</textarea>
                    </div>
                    <button type='submit' class='btn btn-warning'>Salvar Alterações</button>
                </form>
            </div>";
        }
    }

    // EDITAR ZONA
    if (isset($_GET['editar_zona'])) {
        $id = $_GET['editar_zona'];
        $res = $conn->query("SELECT * FROM zona WHERE id = $id");
        if ($res->num_rows === 1) {
            $zona = $res->fetch_assoc();
            echo "
            <div class='alert alert-info'>
                <form method='POST'>
                    <input type='hidden' name='atualizar_zona' value='$id'>
                    <h5><i class='bi bi-pencil'></i> Editar Zona</h5>
                    <div class='mb-2'>
                        <input type='text' name='zona_nome' class='form-control' value='" . htmlspecialchars($zona['zona']) . "' required>
                    </div>
                    <button type='submit' class='btn btn-info'>Salvar Alterações</button>
                </form>
            </div>";
        }
    }
    ?>

    <div class="row">
        <!-- CATEGORIA -->
        <div class="col-md-6 mb-5">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <i class="bi bi-briefcase-fill"></i> Nova Categoria
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="nova_categoria" value="1">
                        <div class="mb-3">
                            <label class="form-label">Nome:</label>
                            <input type="text" name="categoria" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição:</label>
                            <textarea name="descricao" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Salvar Categoria</button>
                    </form>
                </div>
            </div>

            <h5 class="mt-4">📋 Categorias Existentes</h5>
            <ul class="list-group">
                <?php
                $res = $conn->query("SELECT id, categoria FROM categorias");
                while ($row = $res->fetch_assoc()) {
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>" .
                         htmlspecialchars($row['categoria']) .
                         "<div class='btn-group btn-group-sm'>
                            <a href='?editar_categoria={$row['id']}' class='btn btn-outline-primary'><i class='bi bi-pencil'></i></a>
                            <a href='?excluir_categoria={$row['id']}' class='btn btn-outline-danger'><i class='bi bi-trash'></i></a>
                          </div></li>";
                }
                ?>
            </ul>
        </div>

        <!-- ZONA -->
        <div class="col-md-6 mb-5">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-geo-alt-fill"></i> Nova Zona
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="nova_zona" value="1">
                        <div class="mb-3">
                            <label class="form-label">Nome da Zona:</label>
                            <input type="text" name="zona_nome" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-info w-100">Salvar Zona</button>
                    </form>
                </div>
            </div>

            <h5 class="mt-4">🧭 Zonas Existentes</h5>
            <ul class="list-group">
                <?php
                $res = $conn->query("SELECT id, zona FROM zona");
                while ($row = $res->fetch_assoc()) {
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>" .
                         htmlspecialchars($row['zona']) .
                         "<div class='btn-group btn-group-sm'>
                            <a href='?editar_zona={$row['id']}' class='btn btn-outline-primary'><i class='bi bi-pencil'></i></a>
                            <a href='?excluir_zona={$row['id']}' class='btn btn-outline-danger'><i class='bi bi-trash'></i></a>
                          </div></li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="../admin/admin-pag.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar para Painel
        </a>
    </div>
</div>
</body>
</html>
