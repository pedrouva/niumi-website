<?php
require_once '../includes/config.php';

// Verificar se está logado
if (!isLoggedIn()) {
    redirect('/admin/login.php');
}

// Buscar estatísticas
try {
    // Total de produtos
    $stmt = $pdo->query("SELECT COUNT(*) FROM produtos WHERE ativo = 1");
    $total_produtos = $stmt->fetchColumn();
    
    // Total de categorias
    $stmt = $pdo->query("SELECT COUNT(*) FROM categorias");
    $total_categorias = $stmt->fetchColumn();
    
    // Total de parceiros
    $stmt = $pdo->query("SELECT COUNT(*) FROM parceiros");
    $total_parceiros = $stmt->fetchColumn();
    
    // Produtos em destaque
    $stmt = $pdo->query("SELECT COUNT(*) FROM produtos WHERE ativo = 1 AND destaque = 1");
    $produtos_destaque = $stmt->fetchColumn();
    
    // Últimos produtos adicionados
    $stmt = $pdo->prepare("
        SELECT p.nome, p.slug, c.nome as categoria_nome, p.data_criacao 
        FROM produtos p 
        JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.ativo = 1 
        ORDER BY p.data_criacao DESC 
        LIMIT 5
    ");
    $stmt->execute();
    $ultimos_produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $total_produtos = 0;
    $total_categorias = 0;
    $total_parceiros = 0;
    $produtos_destaque = 0;
    $ultimos_produtos = [];
}

include 'templates/admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'templates/admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="content-wrapper">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-download me-1"></i>Exportar
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Boas-vindas -->
                <div class="alert alert-primary" role="alert">
                    <i class="fas fa-user-circle me-2"></i>
                    Bem-vindo de volta, <strong><?php echo htmlspecialchars($_SESSION['admin_nome']); ?></strong>!
                </div>
                
                <!-- Cards de Estatísticas -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total de Produtos
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_produtos; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-cube fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Categorias
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_categorias; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-tags fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Parceiros
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_parceiros; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Em Destaque
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $produtos_destaque; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-star fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Links Rápidos -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-rocket me-2"></i>Ações Rápidas
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <a href="produtos.php?action=add" class="btn btn-primary w-100">
                                            <i class="fas fa-plus me-2"></i>Novo Produto
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <a href="categorias.php?action=add" class="btn btn-success w-100">
                                            <i class="fas fa-tag me-2"></i>Nova Categoria
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <a href="parceiros.php?action=add" class="btn btn-info w-100">
                                            <i class="fas fa-user-plus me-2"></i>Novo Parceiro
                                        </a>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <a href="../" target="_blank" class="btn btn-outline-secondary w-100">
                                            <i class="fas fa-external-link-alt me-2"></i>Ver Site
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Últimos Produtos -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-clock me-2"></i>Últimos Produtos Adicionados
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($ultimos_produtos)): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Nenhum produto cadastrado ainda.</p>
                                    <a href="produtos.php?action=add" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Adicionar Primeiro Produto
                                    </a>
                                </div>
                                <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Produto</th>
                                                <th>Categoria</th>
                                                <th>Data</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($ultimos_produtos as $produto): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                                <td>
                                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($produto['categoria_nome']); ?></span>
                                                </td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($produto['data_criacao'])); ?></td>
                                                <td>
                                                    <a href="produtos.php?action=edit&id=<?php echo $produto['slug']; ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="../produto.php?slug=<?php echo $produto['slug']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="text-center">
                                    <a href="produtos.php" class="btn btn-outline-primary">
                                        <i class="fas fa-list me-2"></i>Ver Todos os Produtos
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informações do Sistema -->
                    <div class="col-lg-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-info-circle me-2"></i>Informações do Sistema
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Versão:</strong> 1.0.0<br>
                                    <strong>PHP:</strong> <?php echo PHP_VERSION; ?><br>
                                    <strong>Último Login:</strong> <?php echo date('d/m/Y H:i'); ?>
                                </div>
                                
                                <hr>
                                
                                <h6>Links Úteis</h6>
                                <ul class="list-unstyled">
                                    <li><a href="../" target="_blank"><i class="fas fa-home me-2"></i>Ver Site</a></li>
                                    <li><a href="backup.php"><i class="fas fa-download me-2"></i>Backup</a></li>
                                    <li><a href="configuracoes.php"><i class="fas fa-cog me-2"></i>Configurações</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include 'templates/admin_footer.php'; ?>

