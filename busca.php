<?php
require_once 'includes/config.php';

// Obter termo de busca
$termo_busca = isset($_GET['q']) ? sanitize($_GET['q']) : '';

// Configurações da página
$page_title = !empty($termo_busca) ? 'Resultados da Pesquisa por "' . $termo_busca . '" - NiUMi' : 'Buscar Produtos - NiUMi';
$page_description = !empty($termo_busca) ? 'Encontre produtos e serviços digitais relacionados a "' . $termo_busca . '" na plataforma NiUMi.' : 'Busque por produtos e serviços digitais na NiUMi.';
$page_keywords = 'busca, pesquisa, produtos digitais, ' . $termo_busca;

// Paginação
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = ITEMS_PER_PAGE;
$offset = ($page - 1) * $items_per_page;

$produtos = [];
$total_produtos = 0;
$total_pages = 0;

// Realizar busca se houver termo
if (!empty($termo_busca)) {
    try {
        // Preparar termo para busca
        $termo_like = '%' . $termo_busca . '%';
        
        // Contar total de produtos
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM produtos p 
            JOIN categorias c ON p.categoria_id = c.id 
            JOIN parceiros pr ON p.parceiro_id = pr.id 
            WHERE p.ativo = 1 AND (
                p.nome LIKE ? OR 
                p.descricao LIKE ? OR 
                c.nome LIKE ? OR 
                pr.nome LIKE ? OR
                p.meta_keywords LIKE ?
            )
        ");
        $stmt->execute([$termo_like, $termo_like, $termo_like, $termo_like, $termo_like]);
        $total_produtos = $stmt->fetchColumn();
        
        // Buscar produtos com paginação
        $stmt = $pdo->prepare("
            SELECT p.*, c.nome as categoria_nome, c.slug as categoria_slug, pr.nome as parceiro_nome 
            FROM produtos p 
            JOIN categorias c ON p.categoria_id = c.id 
            JOIN parceiros pr ON p.parceiro_id = pr.id 
            WHERE p.ativo = 1 AND (
                p.nome LIKE ? OR 
                p.descricao LIKE ? OR 
                c.nome LIKE ? OR 
                pr.nome LIKE ? OR
                p.meta_keywords LIKE ?
            )
            ORDER BY 
                CASE 
                    WHEN p.nome LIKE ? THEN 1
                    WHEN p.descricao LIKE ? THEN 2
                    WHEN c.nome LIKE ? THEN 3
                    ELSE 4
                END,
                p.destaque DESC, 
                p.data_criacao DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([
            $termo_like, $termo_like, $termo_like, $termo_like, $termo_like,
            $termo_like, $termo_like, $termo_like,
            $items_per_page, $offset
        ]);
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calcular paginação
        $total_pages = ceil($total_produtos / $items_per_page);
        
    } catch(PDOException $e) {
        $produtos = [];
        $total_produtos = 0;
        $total_pages = 0;
    }
}

include 'templates/header.php';
?>

<!-- Breadcrumb -->
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page">Busca</li>
        </ol>
    </nav>
</div>

<!-- Cabeçalho da Busca -->
<section class="section bg-light-custom">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <?php if (!empty($termo_busca)): ?>
                <h1 class="section-title">Resultados da Pesquisa</h1>
                <p class="section-subtitle">Você pesquisou por: <strong>"<?php echo htmlspecialchars($termo_busca); ?>"</strong></p>
                <div class="mb-4">
                    <span class="badge bg-primary fs-6"><?php echo $total_produtos; ?> resultado(s) encontrado(s)</span>
                </div>
                <?php else: ?>
                <h1 class="section-title">Buscar Produtos</h1>
                <p class="section-subtitle">Encontre rapidamente o produto digital ideal para suas necessidades</p>
                <?php endif; ?>
                
                <!-- Formulário de Busca -->
                <form method="GET" action="/busca.php" class="search-form">
                    <div class="input-group input-group-lg">
                        <input type="text" name="q" class="form-control search-input" 
                               placeholder="Ex: curso de marketing, ferramenta de IA, e-book de finanças..." 
                               value="<?php echo htmlspecialchars($termo_busca); ?>" 
                               aria-label="Buscar produtos">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Resultados da Busca -->
<section class="section">
    <div class="container">
        <?php if (!empty($termo_busca)): ?>
            <?php if (empty($produtos)): ?>
            <!-- Nenhum resultado encontrado -->
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h3>Nenhum resultado encontrado</h3>
                        <p class="text-muted">Não encontramos produtos que correspondam à sua pesquisa "<strong><?php echo htmlspecialchars($termo_busca); ?></strong>".</p>
                        
                        <div class="mt-4">
                            <h5>Sugestões:</h5>
                            <ul class="list-unstyled">
                                <li>• Verifique a ortografia das palavras</li>
                                <li>• Tente usar termos mais gerais</li>
                                <li>• Use palavras-chave diferentes</li>
                                <li>• Navegue pelas nossas categorias</li>
                            </ul>
                        </div>
                        
                        <div class="mt-4">
                            <a href="/" class="btn btn-primary me-3">Voltar ao Início</a>
                            <a href="/categoria.php" class="btn btn-outline-primary">Ver Categorias</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- Filtros e Ordenação -->
            <div class="row mb-4">
                <div class="col-lg-6">
                    <p class="text-muted">
                        Mostrando <?php echo count($produtos); ?> de <?php echo $total_produtos; ?> resultados
                        <?php if ($total_pages > 1): ?>
                        (página <?php echo $page; ?> de <?php echo $total_pages; ?>)
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex justify-content-end">
                        <select class="form-select" style="width: auto;" onchange="changeSort(this.value)">
                            <option value="relevancia">Mais Relevantes</option>
                            <option value="recentes">Mais Recentes</option>
                            <option value="nome">Nome A-Z</option>
                            <option value="destaque">Em Destaque</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Lista de Produtos -->
            <div class="row" id="produtos-container">
                <?php foreach ($produtos as $produto): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            <?php if ($produto['imagem']): ?>
                            <img src="/assets/images/produtos/<?php echo $produto['imagem']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                            <?php else: ?>
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                            <?php endif; ?>
                            <?php if ($produto['destaque']): ?>
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-warning">Destaque</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($produto['nome']); ?></h5>
                            <p class="card-text flex-grow-1"><?php echo htmlspecialchars(substr($produto['descricao'], 0, 120)) . '...'; ?></p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-tag me-1"></i>
                                        <a href="/categoria.php?slug=<?php echo $produto['categoria_slug']; ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($produto['categoria_nome']); ?>
                                        </a>
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($produto['parceiro_nome']); ?>
                                    </small>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="/produto.php?slug=<?php echo $produto['slug']; ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-2"></i>Ver Detalhes
                                    </a>
                                    <a href="<?php echo $produto['link_afiliado']; ?>" target="_blank" rel="noopener" class="btn btn-primary" data-affiliate="true" data-product-id="<?php echo $produto['id']; ?>">
                                        <i class="fas fa-external-link-alt me-2"></i>Saiba Mais
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Paginação -->
            <?php if ($total_pages > 1): ?>
            <nav aria-label="Paginação de resultados">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?q=<?php echo urlencode($termo_busca); ?>&page=<?php echo $page - 1; ?>">
                            <i class="fas fa-chevron-left"></i> Anterior
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php
                    $start = max(1, $page - 2);
                    $end = min($total_pages, $page + 2);
                    
                    for ($i = $start; $i <= $end; $i++):
                    ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?q=<?php echo urlencode($termo_busca); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?q=<?php echo urlencode($termo_busca); ?>&page=<?php echo $page + 1; ?>">
                            Próxima <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
        <!-- Página inicial de busca -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="text-center mb-5">
                    <h2>Categorias Populares</h2>
                    <p class="text-muted">Explore nossas categorias mais procuradas</p>
                </div>
                
                <div class="row">
                    <?php
                    try {
                        $stmt = $pdo->prepare("
                            SELECT c.*, COUNT(p.id) as total_produtos 
                            FROM categorias c 
                            LEFT JOIN produtos p ON c.id = p.categoria_id AND p.ativo = 1 
                            GROUP BY c.id 
                            ORDER BY total_produtos DESC 
                            LIMIT 4
                        ");
                        $stmt->execute();
                        $categorias_populares = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        $category_icons = [
                            'cursos-online' => 'fas fa-graduation-cap',
                            'inteligencia-artificial' => 'fas fa-brain',
                            'ferramentas-digitais' => 'fas fa-tools',
                            'e-books' => 'fas fa-book-open'
                        ];
                        
                        foreach ($categorias_populares as $categoria):
                            $icon = isset($category_icons[$categoria['slug']]) ? $category_icons[$categoria['slug']] : 'fas fa-cube';
                    ?>
                    <div class="col-lg-6 col-md-6 mb-4">
                        <a href="/categoria.php?slug=<?php echo $categoria['slug']; ?>" class="text-decoration-none">
                            <div class="category-card h-100">
                                <div class="category-icon">
                                    <i class="<?php echo $icon; ?>"></i>
                                </div>
                                <h3><?php echo htmlspecialchars($categoria['nome']); ?></h3>
                                <p><?php echo htmlspecialchars(substr($categoria['descricao'], 0, 100)) . '...'; ?></p>
                                <div class="mt-auto">
                                    <span class="badge bg-primary"><?php echo $categoria['total_produtos']; ?> produtos</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php 
                        endforeach;
                    } catch(PDOException $e) {
                        // Em caso de erro, não exibir nada
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
function changeSort(value) {
    const url = new URL(window.location);
    url.searchParams.set('ordenar', value);
    url.searchParams.set('page', '1'); // Reset para primeira página
    window.location.href = url.toString();
}
</script>

<?php include 'templates/footer.php'; ?>

