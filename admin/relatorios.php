<?php
require_once '../includes/config.php';

// Verificar se está logado
if (!isLoggedIn()) {
    redirect('/admin/login.php');
}

$page_title = 'Relatórios - Painel Administrativo';

// Buscar dados para relatórios
try {
    // Estatísticas gerais
    $stmt = $pdo->query("SELECT COUNT(*) FROM produtos WHERE ativo = 1");
    $total_produtos = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM categorias");
    $total_categorias = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM parceiros WHERE ativo = 1");
    $total_parceiros = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM produtos WHERE ativo = 1 AND destaque = 1");
    $produtos_destaque = $stmt->fetchColumn();
    
    // Produtos por categoria
    $stmt = $pdo->prepare("
        SELECT c.nome, COUNT(p.id) as total 
        FROM categorias c 
        LEFT JOIN produtos p ON c.id = p.categoria_id AND p.ativo = 1
        GROUP BY c.id, c.nome 
        ORDER BY total DESC
    ");
    $stmt->execute();
    $produtos_por_categoria = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Produtos mais recentes
    $stmt = $pdo->prepare("
        SELECT p.nome, p.slug, c.nome as categoria_nome, p.data_criacao 
        FROM produtos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.ativo = 1 
        ORDER BY p.data_criacao DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $produtos_recentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Produtos em destaque
    $stmt = $pdo->prepare("
        SELECT p.nome, p.slug, c.nome as categoria_nome, p.data_criacao 
        FROM produtos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.ativo = 1 AND p.destaque = 1 
        ORDER BY p.data_criacao DESC
    ");
    $stmt->execute();
    $produtos_em_destaque = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $total_produtos = 0;
    $total_categorias = 0;
    $total_parceiros = 0;
    $produtos_destaque = 0;
    $produtos_por_categoria = [];
    $produtos_recentes = [];
    $produtos_em_destaque = [];
}

include 'templates/admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'templates/admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Relatórios</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>Imprimir
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Resumo Geral -->
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
                                        Parceiros Ativos
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
            
            <div class="row">
                <!-- Produtos por Categoria -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-chart-pie me-2"></i>Produtos por Categoria
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if (empty($produtos_por_categoria)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Nenhum dado disponível.</p>
                            </div>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Categoria</th>
                                            <th class="text-end">Produtos</th>
                                            <th class="text-end">%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($produtos_por_categoria as $categoria): ?>
                                        <?php $percentual = $total_produtos > 0 ? round(($categoria['total'] / $total_produtos) * 100, 1) : 0; ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($categoria['nome']); ?></td>
                                            <td class="text-end"><?php echo $categoria['total']; ?></td>
                                            <td class="text-end"><?php echo $percentual; ?>%</td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Produtos em Destaque -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-star me-2"></i>Produtos em Destaque
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if (empty($produtos_em_destaque)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Nenhum produto em destaque.</p>
                            </div>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Categoria</th>
                                            <th>Data</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($produtos_em_destaque as $produto): ?>
                                        <tr>
                                            <td>
                                                <a href="produtos.php?action=edit&id=<?php echo $produto['slug']; ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($produto['nome']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?php echo htmlspecialchars($produto['categoria_nome'] ?? 'Sem categoria'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($produto['data_criacao'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Produtos Mais Recentes -->
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-clock me-2"></i>Produtos Mais Recentes
                            </h6>
                        </div>
                        <div class="card-body">
                            <?php if (empty($produtos_recentes)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Nenhum produto cadastrado.</p>
                            </div>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produto</th>
                                            <th>Categoria</th>
                                            <th>Data de Criação</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($produtos_recentes as $produto): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?php echo htmlspecialchars($produto['categoria_nome'] ?? 'Sem categoria'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($produto['data_criacao'])); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="produtos.php?action=edit&id=<?php echo $produto['slug']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="../produto.php?slug=<?php echo $produto['slug']; ?>" 
                                                       target="_blank" class="btn btn-sm btn-outline-secondary" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informações do Sistema -->
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-info-circle me-2"></i>Informações do Sistema
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>Versão do Sistema:</strong></td>
                                            <td>1.0.0</td>
                                        </tr>
                                        <tr>
                                            <td><strong>PHP:</strong></td>
                                            <td><?php echo PHP_VERSION; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Servidor:</strong></td>
                                            <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>Data do Relatório:</strong></td>
                                            <td><?php echo date('d/m/Y H:i:s'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Usuário:</strong></td>
                                            <td><?php echo htmlspecialchars($_SESSION['admin_nome']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Último Login:</strong></td>
                                            <td><?php echo date('d/m/Y H:i:s'); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
@media print {
    .sidebar, .navbar, .btn-toolbar {
        display: none !important;
    }
    
    main {
        margin-left: 0 !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
    }
}
</style>

<?php include 'templates/admin_footer.php'; ?>

