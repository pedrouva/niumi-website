<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
<div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER["PHP_SELF"]) == "dashboard.php" ? "active" : ""; ?>" href="dashboard.php">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading">Gerenciamento</h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER["PHP_SELF"]) == "produtos.php" ? "active" : ""; ?>" href="produtos.php">
                    <i class="fas fa-cube me-2"></i>Produtos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER["PHP_SELF"]) == "categorias.php" ? "active" : ""; ?>" href="categorias.php">
                    <i class="fas fa-tags me-2"></i>Categorias
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER["PHP_SELF"]) == "parceiros.php" ? "active" : ""; ?>" href="parceiros.php">
                    <i class="fas fa-users me-2"></i>Parceiros
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading">Sistema</h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER["PHP_SELF"]) == "usuarios.php" ? "active" : ""; ?>" href="usuarios.php">
                    <i class="fas fa-user-shield me-2"></i>Usuários Admin
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER["PHP_SELF"]) == "configuracoes.php" ? "active" : ""; ?>" href="configuracoes.php">
                    <i class="fas fa-cog me-2"></i>Configurações
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="backup.php">
                    <i class="fas fa-download me-2"></i>Backup
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading">Site</h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="../" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i>Ver Site
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="relatorios.php">
                    <i class="fas fa-chart-bar me-2"></i>Relatórios
                </a>
            </li>
        </ul>

        <div class="mt-auto p-3">
            <div class="text-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    NiUMi Admin v1.0
                </small>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="logout.php" class="btn btn-danger btn-block">
                <i class="fas fa-sign-out-alt me-2"></i>Sair
            </a>
        </div>
    </div>
</nav>

