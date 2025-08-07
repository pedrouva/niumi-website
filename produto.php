<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'includes/config.php';
require_once 'includes/Parsedown.php';

// Obter slug do produto
$produto_slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

if (empty($produto_slug)) {
    redirect('/');
}

// Buscar informações do produto
try {
    $stmt = $pdo->prepare("
        SELECT p.*, c.nome as categoria_nome, c.slug as categoria_slug, 
               pr.nome as parceiro_nome, pr.descricao as parceiro_descricao, pr.redes_sociais
        FROM produtos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        LEFT JOIN parceiros pr ON p.parceiro_id = pr.id 
        WHERE p.slug = ? AND p.ativo = 1
    ");
    $stmt->execute([$produto_slug]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$produto) {
        redirect('/');
    }
} catch(PDOException $e) {
    redirect('/');
}

// Configurações da página
$page_title = $produto['meta_titulo'] ?: $produto['nome'] . ' - NiUMi';
$page_description = $produto['meta_descricao'] ?: 'Saiba mais sobre ' . $produto['nome'] . ' da ' . $produto['parceiro_nome'] . ' na NiUMi. ' . substr($produto['descricao'], 0, 150);
$page_keywords = $produto['meta_keywords'] ?: $produto['nome'] . ', ' . $produto['categoria_nome'] . ', ' . $produto['parceiro_nome'] . ', produtos digitais';

// Buscar produtos relacionados
try {
    $stmt = $pdo->prepare("
        SELECT p.*, c.nome as categoria_nome, c.slug as categoria_slug, pr.nome as parceiro_nome 
        FROM produtos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        LEFT JOIN parceiros pr ON p.parceiro_id = pr.id 
        WHERE p.categoria_id = ? AND p.id != ? AND p.ativo = 1 
        ORDER BY p.destaque DESC, p.data_criacao DESC 
        LIMIT 4
    ");
    $stmt->execute([$produto['categoria_id'], $produto['id']]);
    $produtos_relacionados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $produtos_relacionados = [];
}

// Decodificar redes sociais do parceiro
$redes_sociais = (is_string($produto['redes_sociais']) ? json_decode($produto['redes_sociais'], true) : []) ?: [];

include 'templates/header.php';
?>

<!-- Breadcrumb -->
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Início</a></li>
            <li class="breadcrumb-item"><a href="/categoria.php?slug=<?php echo $produto['categoria_slug']; ?>"><?php echo htmlspecialchars($produto['categoria_nome']); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($produto['nome']); ?></li>
        </ol>
    </nav>
</div>

<!-- Detalhes do Produto -->
<section class="section">
    <div class="container">
        <div class="row">
            <!-- Imagem e Vídeo -->
            <div class="col-lg-6 mb-4">
                <div class="product-media">
                    <?php if ($produto["imagem"]): ?>
                    <img src="/assets/images/produtos/<?php echo $produto["imagem"]; ?>" class="img-fluid rounded-custom mb-3" alt="<?php echo htmlspecialchars($produto["nome"]); ?>">
                    <?php elseif ($produto["imagem_url"]): ?>
                    <img src="<?php echo htmlspecialchars($produto["imagem_url"]); ?>" class="img-fluid rounded-custom mb-3" alt="<?php echo htmlspecialchars($produto["nome"]); ?>">
                    <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center bg-light rounded-custom mb-3" style="height: 400px;">
                        <i class="fas fa-image fa-5x text-muted"></i>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($produto["video_youtube"]): 
                        $video_id = '';
                        $url_parts = parse_url($produto["video_youtube"]);
                        if (isset($url_parts["query"])) {
                            parse_str($url_parts["query"], $query_params);
                            if (isset($query_params["v"])) {
                                $video_id = $query_params["v"];
                            }
                        } else if (isset($url_parts["path"])) {
                            $path_parts = explode('/', rtrim($url_parts["path"], '/'));
                            $video_id = end($path_parts);
                        }
                    ?>
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/<?php echo htmlspecialchars($video_id); ?>" title="Vídeo do produto" allowfullscreen class="rounded-custom"></iframe>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Informações do Produto -->
            <div class="col-lg-6">
                <div class="product-info">
                    <?php if ($produto['destaque']): ?>
                    <span class="badge bg-warning mb-3">Produto em Destaque</span>
                    <?php endif; ?>
                    
                    <h1 class="mb-3"><?php echo htmlspecialchars($produto['nome']); ?></h1>
                    
                    <div class="product-meta mb-4">
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <strong>Categoria:</strong><br>
                                <a href="/categoria.php?slug=<?php echo $produto['categoria_slug']; ?>" class="text-primary">
                                    <i class="fas fa-tag me-1"></i><?php echo htmlspecialchars($produto['categoria_nome']); ?>
                                </a>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>Parceiro:</strong><br>
                                <span class="text-muted">
                                    <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($produto['parceiro_nome']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="product-description mb-4">
                        <h3>Descrição</h3>
                        <div class="text-muted">
                            <?php
                            $Parsedown = new Parsedown();
                            echo $Parsedown->text($produto["descricao"]);
                            ?>
                        </div>
                    </div>
                    
                    <!-- Ações -->
                    <div class="product-actions mb-4">
                        <div class="d-grid gap-2">
                            <a href="<?php echo $produto['link_afiliado']; ?>" target="_blank" rel="noopener" class="btn btn-primary btn-lg" data-affiliate="true" data-product-id="<?php echo $produto['id']; ?>">
                                <i class="fas fa-external-link-alt me-2"></i>Acessar Produto
                            </a>
                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-outline-secondary w-100" onclick="shareProduct('<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>', '<?php echo htmlspecialchars($produto['nome']); ?>')">
                                        <i class="fas fa-share-alt me-2"></i>Compartilhar
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-outline-danger w-100" onclick="toggleFavorite(<?php echo $produto['id']; ?>)">
                                        <i class="far fa-heart me-2"></i>Favoritar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informações do Parceiro -->
                    <div class="partner-info bg-light-custom p-3 rounded-custom">
                        <h5>Sobre o Parceiro</h5>
                        <h6><?php echo htmlspecialchars($produto['parceiro_nome']); ?></h6>
                        <?php if ($produto['parceiro_descricao']): ?>
                        <p class="text-muted mb-2"><?php echo htmlspecialchars($produto['parceiro_descricao']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($redes_sociais)): ?>
                        <div class="social-links">
                            <strong>Redes Sociais:</strong><br>
                            <?php foreach ($redes_sociais as $rede => $link): ?>
                            <a href="<?php echo htmlspecialchars($link); ?>" target="_blank" rel="noopener" class="me-2">
                                <i class="fab fa-<?php echo $rede; ?>"></i> <?php echo ucfirst($rede); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Produtos Relacionados -->
<?php if (!empty($produtos_relacionados)): ?>
<section class="section bg-light-custom">
    <div class="container">
        <h2 class="section-title">Produtos Relacionados</h2>
        <p class="section-subtitle">Outros produtos da categoria <?php echo htmlspecialchars($produto['categoria_nome']); ?></p>
        
        <div class="row">
            <?php foreach ($produtos_relacionados as $relacionado): ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="position-relative">
                        <?php if ($relacionado['imagem']): ?>
                        <img src="/assets/images/produtos/<?php echo $relacionado['imagem']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($relacionado['nome']); ?>">
                        <?php else: ?>
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        <?php endif; ?>
                        <?php if ($relacionado['destaque']): ?>
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-warning">Destaque</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($relacionado['nome']); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo htmlspecialchars(substr($relacionado['descricao'], 0, 100)) . '...'; ?></p>
                        
                        <div class="mt-auto">
                            <small class="text-muted d-block mb-3">
                                <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($relacionado['parceiro_nome']); ?>
                            </small>
                            
                            <div class="d-grid gap-2">
                                <a href="/produto.php?slug=<?php echo $relacionado['slug']; ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>Ver Detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/categoria.php?slug=<?php echo $produto['categoria_slug']; ?>" class="btn btn-primary">
                <i class="fas fa-th-large me-2"></i>Ver Todos em <?php echo htmlspecialchars($produto['categoria_nome']); ?>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Schema.org JSON-LD para SEO -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "<?php echo htmlspecialchars($produto['nome']); ?>",
    "description": "<?php echo htmlspecialchars($produto['descricao']); ?>",
    "category": "<?php echo htmlspecialchars($produto['categoria_nome']); ?>",
    "brand": {
        "@type": "Brand",
        "name": "<?php echo htmlspecialchars($produto['parceiro_nome']); ?>"
    },
    "url": "<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>",
    <?php if ($produto['imagem']): ?>
    "image": "<?php echo SITE_URL; ?>/assets/images/produtos/<?php echo $produto['imagem']; ?>",
    <?php endif; ?>
    "offers": {
        "@type": "Offer",
        "url": "<?php echo htmlspecialchars($produto['link_afiliado']); ?>",
        "availability": "https://schema.org/InStock"
    }
}
</script>

<?php include 'templates/footer.php'; ?>

