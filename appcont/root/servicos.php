<?php
require_once '../db/db.php';
include '../include/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET["categoria"]) || !is_numeric($_GET["categoria"])) {
    header("Location: ../root/index.php");
    exit();
}

$categoria_id = (int) $_GET["categoria"];
$zonaFiltro = isset($_GET['zona']) && is_numeric($_GET['zona']) ? (int) $_GET['zona'] : null;

// Buscar IDs de trabalhadores com plano premium ativo
$hoje = date('Y-m-d');
// Premium (id=3)
$stmtPremium = $conn->prepare("
    SELECT a.trabalhador_id
    FROM assinaturas a
    WHERE a.plano_id = 3 AND a.ativo = 1 AND a.fim >= ?
");
$stmtPremium->bind_param("s", $hoje);
$stmtPremium->execute();
$resultPremium = $stmtPremium->get_result();
$premiumIds = array_column($resultPremium->fetch_all(MYSQLI_ASSOC), 'trabalhador_id');
$stmtPremium->close();

// Buscar trabalhadores premium (agora respeitando o filtro de zona)
$premiumTrabalhadores = [];
if ($premiumIds) {
    $idsIn = implode(',', array_map('intval', $premiumIds));
    $sqlPremium = "
      SELECT t.*, z.zona AS localidade,
             COALESCE((
               SELECT ROUND(AVG(a.estrelas), 2)
               FROM avaliacao a
               WHERE a.trabalhador_id = t.id
             ), 0) AS media_estrelas
      FROM trabalhadores t
      INNER JOIN zona z ON t.zona = z.id
      INNER JOIN trabalhador_profissao tp ON t.id = tp.trabalhador_id
      WHERE tp.profissao_id = $categoria_id AND t.disponibilidade = 1 AND t.id IN ($idsIn)
    ";
    if ($zonaFiltro) {
        $sqlPremium .= " AND t.zona = $zonaFiltro";
    }
    $sqlPremium .= " GROUP BY t.id ORDER BY media_estrelas DESC";
    $premiumTrabalhadores = $conn->query($sqlPremium)->fetch_all(MYSQLI_ASSOC);
}

// Buscar trabalhadores normais (excluindo os premium)
$sql = "
  SELECT t.*, z.zona AS localidade,
         COALESCE((
           SELECT ROUND(AVG(a.estrelas), 2)
           FROM avaliacao a
           WHERE a.trabalhador_id = t.id
         ), 0) AS media_estrelas
  FROM trabalhadores t
  INNER JOIN zona z ON t.zona = z.id
  INNER JOIN trabalhador_profissao tp ON t.id = tp.trabalhador_id
  WHERE tp.profissao_id = $categoria_id AND t.disponibilidade = 1
";
if ($zonaFiltro) {
    $sql .= " AND t.zona = $zonaFiltro";
}
if ($premiumIds) {
    $idsIn = implode(',', array_map('intval', $premiumIds));
    $sql .= " AND t.id NOT IN ($idsIn)";
}
$sql .= " GROUP BY t.id ORDER BY media_estrelas DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <title>Profissionais por Categoria</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
        }
        .premium-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">
<main class="flex-fill py-5">
    <div class="container px-5">
        <h2 class="fw-bolder fs-4 mb-4">Profissionais disponíveis</h2>

        <!-- Filtro por zona -->
        <form method="GET" class="row g-3 align-items-end mb-4">
            <input type="hidden" name="categoria" value="<?= $categoria_id ?>">
            <div class="col-md-4">
                <label for="zona" class="form-label">Zona</label>
                <select class="form-select" id="zona" name="zona">
                    <option value="">Todas as zonas</option>
                    <?php
                    $zonas = $conn->query("SELECT id, zona FROM zona ORDER BY zona");
                    while ($z = $zonas->fetch_assoc()) {
                        $selected = ($zonaFiltro == $z['id']) ? 'selected' : '';
                        echo "<option value='{$z['id']}' $selected>" . htmlspecialchars($z['zona']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel-fill me-2"></i>Filtrar
                </button>
            </div>
        </form>

        <!-- Destaque Premium -->
        <?php if (!empty($premiumTrabalhadores)): ?>
        <div class="mb-5">
            <h4 class="fw-bold text-warning mb-3"><i class="bi bi-star-fill"></i> Profissionais em Destaque</h4>
            <div class="row gx-5">
                <?php foreach ($premiumTrabalhadores as $row): ?>
                    <?php
                    $profissoes = [];
                    $profQuery = $conn->query("SELECT c.categoria 
                                               FROM trabalhador_profissao tp 
                                               INNER JOIN categorias c ON tp.profissao_id = c.id 
                                               WHERE tp.trabalhador_id = " . $row['id']);
                    while ($p = $profQuery->fetch_assoc()) {
                        $profissoes[] = $p['categoria'];
                    }
                    $caminho_foto = (!empty($row['foto_perfil']) && file_exists(__DIR__ . '/../uploads/trabalhadores/' . $row['foto_perfil']))
                        ? '../uploads/trabalhadores/' . $row['foto_perfil']
                        : '../assets/img/user-default.png';
                    ?>
                    <div class="col-lg-4 mb-4">
                        <div class="card card-hover h-100 shadow border-warning border-3 position-relative">
                            <span class="badge bg-warning text-dark premium-badge"><i class="bi bi-star-fill"></i> Premium</span>
                            <img class="card-img-top" src="<?= htmlspecialchars($caminho_foto) ?>" alt="Foto de <?= htmlspecialchars($row['nome']) ?>" style="height: 250px; object-fit: cover;" />
                            <div class="card-body p-4">
                                <?php foreach ($profissoes as $prof): ?>
                                    <div class="badge bg-primary bg-gradient rounded-pill mb-2 me-1">
                                        <?= htmlspecialchars($prof) ?>
                                    </div>
                                <?php endforeach; ?>
                                <a class="text-decoration-none link-dark stretched-link" href="../user/perfil-trabalhador-para-o-cliente.php?id=<?= $row['id']; ?>">
                                    <div class="h5 card-title mb-3"><?= htmlspecialchars($row['nome']); ?></div>
                                </a>
                                <p class="card-text mb-0">
                                    Zona: <?= htmlspecialchars($row['localidade']); ?><br>
                                    Disponibilidade: 
                                    <?= ($row['disponibilidade'] == 0) 
                                        ? '<span class="text-danger">Indisponível</span>' 
                                        : '<span class="text-success">Disponível</span>'; ?>
                                    <?php if (isset($row['media_estrelas'])): ?>
                                        <p class="text-warning mb-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi bi-star<?= $i <= round($row['media_estrelas']) ? '-fill' : '' ?>"></i>
                                            <?php endfor; ?>
                                            <span class="text-muted small"><?= number_format($row['media_estrelas'], 1) ?>/5</span>
                                        </p>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Lista normal -->
        <div class="row gx-5">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                $profissoes = [];
                $profQuery = $conn->query("SELECT c.categoria 
                                           FROM trabalhador_profissao tp 
                                           INNER JOIN categorias c ON tp.profissao_id = c.id 
                                           WHERE tp.trabalhador_id = " . $row['id']);
                while ($p = $profQuery->fetch_assoc()) {
                    $profissoes[] = $p['categoria'];
                }
                $caminho_foto = (!empty($row['foto_perfil']) && file_exists(__DIR__ . '/../uploads/trabalhadores/' . $row['foto_perfil']))
                    ? '../uploads/trabalhadores/' . $row['foto_perfil']
                    : '../assets/img/user-default.png';
                ?>
                <div class="col-lg-4 mb-5">
                    <div class="card card-hover h-100 shadow border-0">
                        <img class="card-img-top" src="<?= htmlspecialchars($caminho_foto) ?>" alt="Foto de <?= htmlspecialchars($row['nome']) ?>" style="height: 250px; object-fit: cover;" />
                        <div class="card-body p-4">
                            <?php foreach ($profissoes as $prof): ?>
                                <div class="badge bg-primary bg-gradient rounded-pill mb-2 me-1">
                                    <?= htmlspecialchars($prof) ?>
                                </div>
                            <?php endforeach; ?>
                            <a class="text-decoration-none link-dark stretched-link" href="../user/perfil-trabalhador-para-o-cliente.php?id=<?= $row['id']; ?>">
                                <div class="h5 card-title mb-3"><?= htmlspecialchars($row['nome']); ?></div>
                            </a>
                            <p class="card-text mb-0">
                                Zona: <?= htmlspecialchars($row['localidade']); ?><br>
                                Disponibilidade: 
                                <?= ($row['disponibilidade'] == 0) 
                                    ? '<span class="text-danger">Indisponível</span>' 
                                    : '<span class="text-success">Disponível</span>'; ?>
                                <?php if (isset($row['media_estrelas'])): ?>
  <p class="text-warning mb-2">
    <?php for ($i = 1; $i <= 5; $i++): ?>
      <i class="bi bi-star<?= $i <= round($row['media_estrelas']) ? '-fill' : '' ?>"></i>
    <?php endfor; ?>
    <span class="text-muted small"><?= number_format($row['media_estrelas'], 1) ?>/5</span>
  </p>
<?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-lg-12 text-center">
                <p class="text-muted">Não há trabalhadores disponíveis nesta categoria<?= $zonaFiltro ? ' nesta zona' : '' ?>.</p>
            </div>
        <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../include/footer.php'; ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
