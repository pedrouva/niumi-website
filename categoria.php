<?php
include_once 'includes/config.php';
include_once 'templates/header.php';

$categoria_slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';

if (empty($categoria_slug)) {
    // Redirecionar para uma página de erro ou página inicial se o slug estiver vazio
    redirect('index.php');
}

// Obter informações da categoria
try {
    $stmt = $pdo->prepare("SELECT * FROM categorias WHERE slug = ?");
    $stmt->execute([$categoria_slug]);
    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$categoria) {
        // Redirecionar para 404 se a categoria não for encontrada
        redirect('404.php');
    }
} catch (PDOException $e) {
    die("Erro ao buscar categoria: " . $e->getMessage());
}

// Configurações de paginação
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = ITEMS_PER_PAGE;
$offset = ($page - 1) * $items_per_page;

// Obter produtos da categoria
try {
    $order_by = 'p.data_criacao DESC'; // Ordem padrão
    if (isset($_GET['ordenar'])) {
        switch ($_GET['ordenar']) {
            case 'mais-recentes':
                $order_by = 'p.data_criacao DESC';
                break;
            case 'mais-antigos':
                $order_by = 'p.data_criacao ASC';
                break;
            case 'nome-asc':
                $order_by = 'p.nome ASC';
                break;
            case 'nome-desc':
                $order_by = 'p.nome DESC';
                break;
        }
    }

    $stmt = $pdo->prepare("
        SELECT p.*, c.nome as categoria_nome, pa.nome as parceiro_nome
        FROM produtos p
        JOIN categorias c ON p.categoria_id = c.id
        JOIN parceiros pa ON p.parceiro_id = pa.id
        WHERE c.slug = ? AND p.ativo = 1
        ORDER BY $order_by
        LIMIT ?, ?
    ");
    $stmt->bindValue(1, $categoria_slug, PDO::PARAM_STR);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->bindValue(3, $items_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Contar total de produtos para paginação
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM produtos p JOIN categorias c ON p.categoria_id = c.id WHERE c.slug = ? AND p.ativo = 1");
    $stmt->execute([$categoria_slug]);
    $total_produtos = $stmt->fetchColumn();
    $total_pages = ceil($total_produtos / $items_per_page);

} catch (PDOException $e) {
    die("Erro ao buscar produtos: " . $e->getMessage());
}

$category_icons = [
    'cursos-online' => 'fas fa-graduation-cap',
    'inteligencia-artificial' => 'fas fa-brain',
    'ferramentas-digitais' => 'fas fa-tools',
    'e-books' => 'fas fa-book-open'
];
$current_category_icon = isset($category_icons[$categoria_slug]) ? $category_icons[$categoria_slug] : 'fas fa-cube';

?>

<main class="container my-5">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <i class="<?= $current_category_icon ?> display-4 text-primary mb-3"></i>
            <h1 class="display-4 fw-bold"><?= htmlspecialchars($categoria['nome']) ?></h1>
            <p class="lead text-muted"><?= htmlspecialchars($categoria['descricao']) ?></p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 offset-md-6">
            <div class="input-group">
                <label class="input-group-text" for="ordenar">Ordenar por:</label>
                <select class="form-select" id="ordenar" onchange="changeSort(this.value)">
                    <option value="mais-recentes" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'mais-recentes') ? 'selected' : '' ?>>Mais Recentes</option>
                    <option value="mais-antigos" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'mais-antigos') ? 'selected' : '' ?>>Mais Antigos</option>
                    <option value="nome-asc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'nome-asc') ? 'selected' : '' ?>>Nome (A-Z)</option>
                    <option value="nome-desc" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] == 'nome-desc') ? 'selected' : '' ?>>Nome (Z-A)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <?php if (empty($produtos)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    Nenhum produto encontrado nesta categoria.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm product-card">
                        <?php if ($produto["imagem"]): ?>
                        <img src="assets/images/produtos/<?= htmlspecialchars($produto["imagem"]) ?>" class="card-img-top" alt="<?= htmlspecialchars($produto["nome"]) ?>">
                        <?php elseif ($produto["imagem_url"]): ?>
                        <img src="<?= htmlspecialchars($produto["imagem_url"]) ?>" class="card-img-top" alt="<?= htmlspecialchars($produto["nome"]) ?>">
                        <?php else: ?>
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
                            <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($produto['descricao'], 0, 100)) ?>...</p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted"><i class="fas fa-tag me-1"></i><?= htmlspecialchars($produto['categoria_nome']) ?></small>
                                <small class="text-muted"><i class="fas fa-user me-1"></i><?= htmlspecialchars($produto['parceiro_nome']) ?></small>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="/produto.php?slug=<?= htmlspecialchars($produto['slug']) ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>Ver Detalhes
                                </a>
                                <a href="<?= htmlspecialchars($produto['link_afiliado']) ?>" target="_blank" rel="noopener" class="btn btn-primary" data-affiliate="true" data-product-id="<?= $produto['id'] ?>">
                                    <i class="fas fa-external-link-alt me-2"></i>Saiba Mais
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Paginação -->
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Paginação de produtos">
            <ul class="pagination justify-content-center mt-4">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?slug=<?= htmlspecialchars($categoria_slug) ?>&page=<?= $page - 1 ?>&ordenar=<?= isset($_GET['ordenar']) ? htmlspecialchars($_GET['ordenar']) : 'mais-recentes' ?>">
                            <i class="fas fa-chevron-left"></i> Anterior
                        </a>
                    </li>
                <?php endif; ?>

                <?php
                $start = max(1, $page - 2);
                $end = min($total_pages, $page + 2);

                for ($i = $start; $i <= $end; $i++):
                    ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?slug=<?= htmlspecialchars($categoria_slug) ?>&page=<?= $i ?>&ordenar=<?= isset($_GET['ordenar']) ? htmlspecialchars($_GET['ordenar']) : 'mais-recentes' ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?slug=<?= htmlspecialchars($categoria_slug) ?>&page=<?= $page + 1 ?>&ordenar=<?= isset($_GET['ordenar']) ? htmlspecialchars($_GET['ordenar']) : 'mais-recentes' ?>">
                            Próxima <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</main>

<!-- Outras Categorias -->
<section class="section bg-light-custom">
    <div class="container">
        <h2 class="section-title">Explore Outras Categorias</h2>
        <div class="row">
            <?php
            try {
                $stmt = $pdo->prepare("
                    SELECT c.*, COUNT(p.id) as total_produtos 
                    FROM categorias c 
                    LEFT JOIN produtos p ON c.id = p.categoria_id AND p.ativo = 1 
                    WHERE c.id != ? 
                    GROUP BY c.id 
                    ORDER BY c.nome 
                    LIMIT 3
                ");
                $stmt->execute([$categoria['id']]);
                $outras_categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                $category_icons = [
                    'cursos-online' => 'fas fa-graduation-cap',
                    'inteligencia-artificial' => 'fas fa-brain',
                    'ferramentas-digitais' => 'fas fa-tools',
                    'e-books' => 'fas fa-book-open'
                ];
                
                foreach ($outras_categorias as $cat):
                    $icon = isset($category_icons[$cat['slug']]) ? $category_icons[$cat['slug']] : 'fas fa-cube';
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <a href="/categoria.php?slug=<?= htmlspecialchars($cat['slug']) ?>" class="text-decoration-none">
                    <div class="category-card h-100">
                        <div class="category-icon">
                            <i class="<?= $icon ?>"></i>
                        </div>
                        <h3><?= htmlspecialchars($cat['nome']) ?></h3>
                        <p><?= htmlspecialchars(substr($cat['descricao'], 0, 100)) ?>...</p>
                        <div class="mt-auto">
                            <span class="badge bg-primary"><?= $cat['total_produtos'] ?> produtos</span>
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

