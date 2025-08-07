<?php
require_once 'includes/config.php';

// Configurações da página
$page_title = 'NiUMi - Marketplace de Produtos e Serviços Digitais';
$page_description = 'Descubra infoprodutos, ferramentas digitais, cursos online e soluções de IA na NiUMi, seu marketplace afiliado de confiança.';
$page_keywords = 'infoprodutos, marketplace, produtos digitais, cursos online, ferramentas digitais, inteligência artificial, e-books';

// Buscar produtos em destaque
try {
    $stmt = $pdo->prepare("
        SELECT p.*, c.nome as categoria_nome, c.slug as categoria_slug, pr.nome as parceiro_nome 
        FROM produtos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        LEFT JOIN parceiros pr ON p.parceiro_id = pr.id 
        WHERE p.ativo = 1 AND p.destaque = 1 
        ORDER BY p.data_criacao DESC 
        LIMIT 8
    ");
    $stmt->execute();
    $produtos_destaque = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $produtos_destaque = [];
}

// Buscar categorias
try {
    $stmt = $pdo->prepare("
        SELECT c.*, COUNT(p.id) as total_produtos 
        FROM categorias c 
        LEFT JOIN produtos p ON c.id = p.categoria_id AND p.ativo = 1 
        GROUP BY c.id 
        ORDER BY c.nome
    ");
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $categorias = [];
}

include 'templates/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="fade-in-up">Descubra os Melhores Produtos Digitais</h1>
                <p class="fade-in-up">Na NiUMi, você encontra uma curadoria especial de infoprodutos, ferramentas digitais, cursos online e soluções de inteligência artificial para impulsionar seu crescimento pessoal e profissional.</p>
                <div class="fade-in-up">
                    <a href="#categorias" class="btn btn-primary btn-lg me-3">Explorar Produtos</a>
                    <a href="/sobre.php" class="btn btn-outline-light btn-lg">Sobre a NiUMi</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center fade-in-up">
                    <i class="fas fa-rocket" style="font-size: 8rem; opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Barra de Pesquisa Destacada -->
<section class="section bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <h2 class="section-title">O que você está procurando?</h2>
                    <p class="section-subtitle">Encontre rapidamente o produto digital ideal para suas necessidades</p>
                </div>
                <form method="GET" action="/busca.php" class="search-form">
                    <div class="input-group input-group-lg">
                        <input type="text" name="q" class="form-control search-input" placeholder="Ex: curso de marketing, ferramenta de IA, e-book de finanças..." aria-label="Buscar produtos">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search me-2"></i>Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Categorias em Destaque -->
<section id="categorias" class="section bg-light-custom">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Explore por Categoria</h2>
            <p class="section-subtitle">Navegue pelas nossas categorias cuidadosamente organizadas</p>
        </div>
        
        <div class="row">
            <?php 
            $category_icons = [
                'cursos-online' => 'fas fa-graduation-cap',
                'inteligencia-artificial' => 'fas fa-brain',
                'ferramentas-digitais' => 'fas fa-tools',
                'e-books' => 'fas fa-book-open'
            ];
            
            foreach ($categorias as $categoria): 
                $icon = isset($category_icons[$categoria['slug']]) ? $category_icons[$categoria['slug']] : 'fas fa-cube';
            ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <a href="/categoria.php?slug=<?php echo $categoria['slug']; ?>" class="text-decoration-none">
                    <div class="category-card h-100">
                        <div class="category-icon">
                            <i class="<?php echo $icon; ?>"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($categoria['nome']); ?></h3>
                        <p><?php echo htmlspecialchars($categoria['descricao']); ?></p>
                        <div class="mt-auto">
                            <span class="badge bg-primary"><?php echo $categoria['total_produtos']; ?> produtos</span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Produtos em Destaque -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Produtos em Destaque</h2>
            <p class="section-subtitle">Selecionamos os melhores produtos para você</p>
        </div>
        
        <div class="row" id="produtos-destaque">
            <?php foreach ($produtos_destaque as $produto): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="position-relative">
                        <?php if ($produto["imagem"]): ?>
                        <img src="/assets/images/produtos/<?php echo $produto["imagem"]; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($produto["nome"]); ?>">
                        <?php elseif ($produto["imagem_url"]): ?>
                        <img src="<?php echo htmlspecialchars($produto["imagem_url"]); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($produto["nome"]); ?>">
                        <?php else: ?>
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        <?php endif; ?>
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-warning">Destaque</span>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($produto['nome']); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo htmlspecialchars(substr($produto['descricao'], 0, 100)) . '...'; ?></p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-tag me-1"></i><?php echo htmlspecialchars($produto['categoria_nome']); ?>
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
        
        <?php if (count($produtos_destaque) >= 8): ?>
        <div class="text-center mt-4">
            <a href="/categoria.php" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-th-large me-2"></i>Ver Todos os Produtos
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Sobre a NiUMi -->
<section class="section bg-light-custom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title text-start">Sobre a NiUMi</h2>
                <p class="lead">Somos uma plataforma digital especializada na curadoria e divulgação de produtos e serviços digitais de qualidade.</p>
                <p>Nossa missão é organizar a oferta de produtos digitais na internet, reunindo em um só lugar recomendações validadas, categorizadas e otimizadas, promovendo a visibilidade de pequenos e grandes produtores.</p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Curadoria especializada de produtos</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Categorização inteligente</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Transparência e confiabilidade</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Suporte a produtores independentes</li>
                </ul>
                <a href="/sobre.php" class="btn btn-primary">
                    <i class="fas fa-info-circle me-2"></i>Saiba Mais
                </a>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="fas fa-handshake" style="font-size: 8rem; color: var(--primary-blue); opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section" style="background: linear-gradient(135deg, var(--primary-blue), var(--secondary-green)); color: white;">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="mb-4" style="color: white;">Pronto para Descobrir Seu Próximo Produto Digital?</h2>
                <p class="lead mb-4">Junte-se a milhares de pessoas que já encontraram soluções incríveis na NiUMi.</p>
                <div>
                    <a href="#categorias" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-rocket me-2"></i>Começar Agora
                    </a>
                    <a href="/contato.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>Entre em Contato
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'templates/footer.php'; ?>

