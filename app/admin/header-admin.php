<style>
.header-servicos {
    background: #23262a;
    padding: 24px 0;
}
.header-servicos-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
}
.header-servicos-title {
    color: #fff;
    font-size: 2rem;
    margin: 0;
    flex: 2;
    text-align: center;
    font-weight: 400;
}
.header-servicos-nav {
    flex: 1;
    display: flex;
    justify-content: flex-end;
    gap: 16px;
    align-items: center;
}
.header-servicos-link {
    color: #bfc4c9;
    text-decoration: none;
    font-size: 1.1rem;
    transition: color 0.2s;
    display: flex;
    align-items: center;
}
.header-servicos-link:hover {
    color: #fff;
}
.header-servicos-icon {
    margin-right: 4px;
    font-size: 1.2rem;
}
</style>
<?php
// Detecta o nome do arquivo atual
$pagina = basename($_SERVER['PHP_SELF']);

// Define o título com base na página
switch ($pagina) {
    case 'admin-pedidos.php':
        $tituloPagina = 'Pedidos';
        break;
    case 'admin-cliente-pag.php':
        $tituloPagina = 'Clientes';
        break;
    case 'admin-pag.php':
        $tituloPagina = 'Trabalhadores';
        break;
    default:
        $tituloPagina = 'Admin';
}
?>

<header class="header-servicos">
  <div class="header-servicos-container">
    <div style="flex: 1;"></div>
    <h1 class="header-servicos-title"><?= htmlspecialchars($tituloPagina) ?></h1>

    <nav class="header-servicos-nav" style="width: 100%;">
      <a href="../admin-cliente/zonas-admin.php" class="btn btn-outline-light btn-sm" style="position: absolute; left: 24px; top: 24px;">
        Editar
      </a>
      <!-- Botão para trabalhadores -->
      <a href="../admin/admin-pag.php" class="btn btn-outline-light btn-sm">
          Trabalhadores
       </a>
  <a href="../admin/admin-avaliacao.php" class="btn btn-outline-light btn-sm">
          avaliacao
       </a>

      <!-- Botão para clientes -->
      <a href="../admin-cliente/admin-cliente-pag.php" class="btn btn-outline-light btn-sm">
        Clientes
      </a>

      <!-- Botão para pedidos -->
      <a href="../admin-cliente/admin-pedidos.php" class="btn btn-outline-light btn-sm">
       Pedidos
      </a>
      <a href="../admin/admin-pagamentos.php" class="btn btn-outline-light btn-sm">
        Pagamento
      </a>

      <!-- Botão de sair -->
      <a href="../admin/logout.php" class="header-servicos-link">
        Sair
      </a>
    </nav>
  </div>
</header>
