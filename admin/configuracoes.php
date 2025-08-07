<?php
require_once '../includes/config.php';

// Verificar se está logado
if (!isLoggedIn()) {
    redirect('/admin/login.php');
}

$page_title = 'Configurações - Painel Administrativo';

// Function to generate sitemap.xml
function generateSitemap($pdo, $site_url) {
    $sitemap_content = '<?xml version="1.0" encoding="UTF-8"?>\n';
    $sitemap_content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';

    // Add static pages
    $sitemap_content .= '  <url>\n    <loc>' . htmlspecialchars($site_url) . '</loc>\n    <changefreq>daily</changefreq>\n    <priority>1.0</priority>\n  </url>\n';
    $sitemap_content .= '  <url>\n    <loc>' . htmlspecialchars($site_url) . '/produtos.php</loc>\n    <changefreq>weekly</changefreq>\n    <priority>0.8</priority>\n  </url>\n';
    $sitemap_content .= '  <url>\n    <loc>' . htmlspecialchars($site_url) . '/categorias.php</loc>\n    <changefreq>weekly</changefreq>\n    <priority>0.8</priority>\n  </url>\n';
    $sitemap_content .= '  <url>\n    <loc>' . htmlspecialchars($site_url) . '/parceiros.php</loc>\n    <changefreq>weekly</changefreq>\n    <priority>0.8</priority>\n  </url>\n';
    $sitemap_content .= '  <url>\n    <loc>' . htmlspecialchars($site_url) . '/contato.php</loc>\n    <changefreq>monthly</changefreq>\n    <priority>0.7</priority>\n  </url>\n';

    // Add dynamic products
    $stmt = $pdo->query("SELECT id, slug FROM produtos");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sitemap_content .= '  <url>\n    <loc>' . htmlspecialchars($site_url) . '/produto.php?id=' . $row['id'] . '&slug=' . $row['slug'] . '</loc>\n    <changefreq>weekly</changefreq>\n    <priority>0.9</priority>\n  </url>\n';
    }

    // Add dynamic categories
    $stmt = $pdo->query("SELECT id, slug FROM categorias");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sitemap_content .= '  <url>\n    <loc>' . htmlspecialchars($site_url) . '/categoria.php?id=' . $row['id'] . '&slug=' . $row['slug'] . '</loc>\n    <changefreq>weekly</changefreq>\n    <priority>0.9</priority>\n  </url>\n';
    }

    // Add dynamic partners
    $stmt = $pdo->query("SELECT id, slug FROM parceiros");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sitemap_content .= '  <url>\n    <loc>' . htmlspecialchars($site_url) . '/parceiro.php?id=' . $row['id'] . '&slug=' . $row['slug'] . '</loc>\n    <changefreq>weekly</changefreq>\n    <priority>0.9</priority>\n  </url>\n';
    }

    $sitemap_content .= '</urlset>';
    file_put_contents('../sitemap.xml', $sitemap_content);
}

// Function to generate robots.txt
function generateRobotsTxt($sitemap_url, $meta_robots) {
    $robots_content = "User-agent: *\n";
    if (strpos($meta_robots, 'noindex') !== false) {
        $robots_content .= "Disallow: /\n";
    } else {
        $robots_content .= "Allow: /\n";
    }
    $robots_content .= "\nSitemap: " . htmlspecialchars($sitemap_url) . "\n";
    file_put_contents('../robots.txt', $robots_content);
}

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $configuracoes = [
        'site_nome' => $_POST['site_nome'] ?? '',
        'site_descricao' => $_POST['site_descricao'] ?? '',
        'site_email' => $_POST['site_email'] ?? '',
        'site_telefone' => $_POST['site_telefone'] ?? '',
        'site_endereco' => $_POST['site_endereco'] ?? '',
        'meta_keywords' => $_POST['meta_keywords'] ?? '',
        'meta_description' => $_POST['meta_description'] ?? '',
        'meta_title' => $_POST['meta_title'] ?? '',
        'meta_author' => $_POST['meta_author'] ?? '',
        'meta_robots' => $_POST['meta_robots'] ?? '',
        'canonical_url' => $_POST['canonical_url'] ?? '',
        'og_title' => $_POST['og_title'] ?? '',
        'og_description' => $_POST['og_description'] ?? '',
        'og_image' => $_POST['og_image'] ?? '',
        'og_type' => $_POST['og_type'] ?? '',
        'twitter_card' => $_POST['twitter_card'] ?? '',
        'twitter_site' => $_POST['twitter_site'] ?? '',
        'schema_org_type' => $_POST['schema_org_type'] ?? '',
        'schema_org_data' => $_POST['schema_org_data'] ?? '',
        'sitemap_url' => $_POST['sitemap_url'] ?? '',
        'robots_txt' => $_POST['robots_txt'] ?? '',
        'google_site_verification' => $_POST['google_site_verification'] ?? '',
        'bing_site_verification' => $_POST['bing_site_verification'] ?? '',
        'google_analytics' => $_POST['google_analytics'] ?? '',
        'google_tag_manager' => $_POST['google_tag_manager'] ?? '',
        'facebook_pixel' => $_POST['facebook_pixel'] ?? '',
        'manutencao' => isset($_POST['manutencao']) ? 1 : 0
    ];

    // Handle logo upload
    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/images/';
        $uploadFile = $uploadDir . basename($_FILES['site_logo']['name']);
        if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $uploadFile)) {
            $configuracoes['site_logo'] = $uploadFile;
        } else {
            $error = 'Erro ao fazer upload do logo.';
        }
    } else if (isset($_POST['delete_site_logo'])) {
        $configuracoes['site_logo'] = ''; // Clear logo path
        // Optionally delete the file from server
        // unlink($configuracoes['site_logo']);
    }

    // Handle favicon upload
    if (isset($_FILES['site_favicon']) && $_FILES['site_favicon']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../assets/images/';
        $uploadFile = $uploadDir . basename($_FILES['site_favicon']['name']);
        if (move_uploaded_file($_FILES['site_favicon']['tmp_name'], $uploadFile)) {
            $configuracoes['site_favicon'] = $uploadFile;
        } else {
            $error = 'Erro ao fazer upload do favicon.';
        }
    } else if (isset($_POST['delete_site_favicon'])) {
        $configuracoes['site_favicon'] = ''; // Clear favicon path
        // Optionally delete the file from server
        // unlink($configuracoes['site_favicon']);
    }
    
    try {
        foreach ($configuracoes as $chave => $valor) {
            $stmt = $pdo->prepare("
                INSERT INTO configuracoes (chave, valor) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE valor = VALUES(valor)
            ");
            $stmt->execute([$chave, $valor]);
        }
        $success = "Configurações salvas com sucesso!";

        // Generate sitemap and robots.txt after saving configurations
        generateSitemap($pdo, 'https://niumi.com.br'); // Replace with actual site URL
        generateRobotsTxt($configuracoes['sitemap_url'], $configuracoes['meta_robots']);

    } catch(PDOException $e) {
        $error = "Erro ao salvar configurações: " . $e->getMessage();
    }
}

// Buscar configurações atuais
$configuracoes = [];
try {
    $stmt = $pdo->query("SELECT chave, valor FROM configuracoes");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $configuracoes[$row['chave']] = $row['valor'];
    }
} catch(PDOException $e) {
    // Configurações padrão se não existirem
    $configuracoes = [
        'site_nome' => 'NiUMi',
        'site_descricao' => 'Plataforma de produtos digitais',
        'site_email' => 'contato@niumi.com.br',
        'site_telefone' => '',
        'site_endereco' => '',
        'meta_keywords' => '',
        'meta_description' => '',
        'meta_title' => '',
        'meta_author' => '',
        'meta_robots' => 'index, follow',
        'canonical_url' => '',
        'og_title' => '',
        'og_description' => '',
        'og_image' => '',
        'og_type' => 'website',
        'twitter_card' => 'summary_large_image',
        'twitter_site' => '',
        'schema_org_type' => 'Organization',
        'schema_org_data' => '',
        'sitemap_url' => '',
        'robots_txt' => '',
        'google_site_verification' => '',
        'bing_site_verification' => '',
        'google_analytics' => '',
        'google_tag_manager' => '',
        'facebook_pixel' => '',
        'manutencao' => 0,
        'site_logo' => '',
        'site_favicon' => ''
    ];
}

include 'templates/admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'templates/admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Configurações</h1>
            </div>
            
            <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <!-- Configurações Gerais -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-cog me-2"></i>Configurações Gerais
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="site_nome" class="form-label">Nome do Site</label>
                                    <input type="text" class="form-control" id="site_nome" name="site_nome" 
                                           value="<?php echo htmlspecialchars($configuracoes['site_nome'] ?? ''); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="site_descricao" class="form-label">Descrição do Site</label>
                                    <textarea class="form-control" id="site_descricao" name="site_descricao" rows="3"><?php echo htmlspecialchars($configuracoes['site_descricao'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="site_email" class="form-label">Email de Contato</label>
                                    <input type="email" class="form-control" id="site_email" name="site_email" 
                                           value="<?php echo htmlspecialchars($configuracoes['site_email'] ?? ''); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="site_telefone" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" id="site_telefone" name="site_telefone" 
                                           value="<?php echo htmlspecialchars($configuracoes['site_telefone'] ?? ''); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="site_endereco" class="form-label">Endereço</label>
                                    <textarea class="form-control" id="site_endereco" name="site_endereco" rows="2"><?php echo htmlspecialchars($configuracoes['site_endereco'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SEO Básico -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-search me-2"></i>SEO Básico
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                           value="<?php echo htmlspecialchars($configuracoes["meta_title"] ?? ""); ?>"
                                           maxlength="60" placeholder="Título principal do site">
                                    <div class="form-text">Máximo 60 caracteres - Aparece na aba do navegador</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3" 
                                              maxlength="160"><?php echo htmlspecialchars($configuracoes["meta_description"] ?? ""); ?></textarea>
                                    <div class="form-text">Máximo 160 caracteres - Aparece nos resultados de busca</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                                           value="<?php echo htmlspecialchars($configuracoes["meta_keywords"] ?? ""); ?>"
                                           placeholder="palavra1, palavra2, palavra3">
                                    <div class="form-text">Palavras-chave separadas por vírgula</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="meta_author" class="form-label">Meta Author</label>
                                    <input type="text" class="form-control" id="meta_author" name="meta_author" 
                                           value="<?php echo htmlspecialchars($configuracoes["meta_author"] ?? ""); ?>"
                                           placeholder="Nome do autor ou empresa">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="meta_robots" class="form-label">Meta Robots</label>
                                    <select class="form-select" id="meta_robots" name="meta_robots">
                                        <option value="index, follow" <?php echo ($configuracoes["meta_robots"] ?? "") == "index, follow" ? "selected" : ""; ?>>Index, Follow (Recomendado)</option>
                                        <option value="index, nofollow" <?php echo ($configuracoes["meta_robots"] ?? "") == "index, nofollow" ? "selected" : ""; ?>>Index, NoFollow</option>
                                        <option value="noindex, follow" <?php echo ($configuracoes["meta_robots"] ?? "") == "noindex, follow" ? "selected" : ""; ?>>NoIndex, Follow</option>
                                        <option value="noindex, nofollow" <?php echo ($configuracoes["meta_robots"] ?? "") == "noindex, nofollow" ? "selected" : ""; ?>>NoIndex, NoFollow</option>
                                    </select>
                                    <div class="form-text">Controla como os buscadores indexam o site</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="canonical_url" class="form-label">URL Canônica</label>
                                    <input type="url" class="form-control" id="canonical_url" name="canonical_url" 
                                           value="<?php echo htmlspecialchars($configuracoes["canonical_url"] ?? ""); ?>"
                                           placeholder="https://niumi.com.br">
                                    <div class="form-text">URL principal do site para evitar conteúdo duplicado</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Open Graph (Facebook) -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fab fa-facebook me-2"></i>Open Graph (Facebook)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="og_title" class="form-label">OG Title</label>
                                    <input type="text" class="form-control" id="og_title" name="og_title" 
                                           value="<?php echo htmlspecialchars($configuracoes["og_title"] ?? ""); ?>"
                                           placeholder="Título para compartilhamento no Facebook">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="og_description" class="form-label">OG Description</label>
                                    <textarea class="form-control" id="og_description" name="og_description" rows="2"><?php echo htmlspecialchars($configuracoes["og_description"] ?? ""); ?></textarea>
                                    <div class="form-text">Descrição para compartilhamento no Facebook</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="og_image" class="form-label">OG Image URL</label>
                                    <input type="url" class="form-control" id="og_image" name="og_image" 
                                           value="<?php echo htmlspecialchars($configuracoes["og_image"] ?? ""); ?>"
                                           placeholder="https://niumi.com.br/assets/images/og-image.jpg">
                                    <div class="form-text">Imagem para compartilhamento (1200x630px recomendado)</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="og_type" class="form-label">OG Type</label>
                                    <select class="form-select" id="og_type" name="og_type">
                                        <option value="website" <?php echo ($configuracoes["og_type"] ?? "") == "website" ? "selected" : ""; ?>>Website</option>
                                        <option value="article" <?php echo ($configuracoes["og_type"] ?? "") == "article" ? "selected" : ""; ?>>Article</option>
                                        <option value="product" <?php echo ($configuracoes["og_type"] ?? "") == "product" ? "selected" : ""; ?>>Product</option>
                                        <option value="business.business" <?php echo ($configuracoes["og_type"] ?? "") == "business.business" ? "selected" : ""; ?>>Business</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Twitter Cards -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fab fa-twitter me-2"></i>Twitter Cards
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="twitter_card" class="form-label">Twitter Card Type</label>
                                    <select class="form-select" id="twitter_card" name="twitter_card">
                                        <option value="summary_large_image" <?php echo ($configuracoes["twitter_card"] ?? "") == "summary_large_image" ? "selected" : ""; ?>>Summary Card with Large Image</option>
                                        <option value="summary" <?php echo ($configuracoes["twitter_card"] ?? "") == "summary" ? "selected" : ""; ?>>Summary Card</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="twitter_site" class="form-label">Twitter Site</label>
                                    <input type="text" class="form-control" id="twitter_site" name="twitter_site" 
                                           value="<?php echo htmlspecialchars($configuracoes["twitter_site"] ?? ""); ?>"
                                           placeholder="@seuperfil">
                                    <div class="form-text">Nome de usuário do Twitter (com @)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Logo e Favicon -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-image me-2"></i>Logo e Favicon
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="site_logo" class="form-label">Logo do Site</label>
                                    <?php if (!empty($configuracoes['site_logo'])): ?>
                                        <div class="mb-2">
                                            <img src="<?php echo htmlspecialchars($configuracoes['site_logo']); ?>" alt="Logo do Site" style="max-width: 150px;">
                                            <button type="submit" name="delete_site_logo" class="btn btn-danger btn-sm ms-2">Excluir Logo</button>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" id="site_logo" name="site_logo">
                                    <div class="form-text">Faça upload do logo do seu site (PNG, JPG, SVG)</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="site_favicon" class="form-label">Favicon do Site</label>
                                    <?php if (!empty($configuracoes['site_favicon'])): ?>
                                        <div class="mb-2">
                                            <img src="<?php echo htmlspecialchars($configuracoes['site_favicon']); ?>" alt="Favicon do Site" style="max-width: 50px;">
                                            <button type="submit" name="delete_site_favicon" class="btn btn-danger btn-sm ms-2">Excluir Favicon</button>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" id="site_favicon" name="site_favicon">
                                    <div class="form-text">Faça upload do favicon do seu site (ICO, PNG, SVG - 32x32px recomendado)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Schema.org (Dados Estruturados) -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-sitemap me-2"></i>Schema.org (Dados Estruturados)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="schema_org_type" class="form-label">Schema Type</label>
                                    <input type="text" class="form-control" id="schema_org_type" name="schema_org_type" 
                                           value="<?php echo htmlspecialchars($configuracoes['schema_org_type'] ?? ''); ?>"
                                           placeholder="Organization, WebSite, Product, Article, etc.">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="schema_org_data" class="form-label">Schema.org JSON-LD</label>
                                    <textarea class="form-control" id="schema_org_data" name="schema_org_data" rows="5"><?php echo htmlspecialchars($configuracoes['schema_org_data'] ?? ''); ?></textarea>
                                    <div class="form-text">Dados estruturados em formato JSON-LD</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Verificações de Propriedade -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-check-circle me-2"></i>Verificações de Propriedade
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="google_site_verification" class="form-label">Google Site Verification</label>
                                    <input type="text" class="form-control" id="google_site_verification" name="google_site_verification" 
                                           value="<?php echo htmlspecialchars($configuracoes['google_site_verification'] ?? ''); ?>"
                                           placeholder="Meta tag de verificação do Google Search Console">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="bing_site_verification" class="form-label">Bing Site Verification</label>
                                    <input type="text" class="form-control" id="bing_site_verification" name="bing_site_verification" 
                                           value="<?php echo htmlspecialchars($configuracoes['bing_site_verification'] ?? ''); ?>"
                                           placeholder="Meta tag de verificação do Bing Webmaster Tools">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sitemap e Robots -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-robot me-2"></i>Sitemap e Robots
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="sitemap_url" class="form-label">Sitemap URL</label>
                                    <input type="url" class="form-control" id="sitemap_url" name="sitemap_url" 
                                           value="<?php echo htmlspecialchars($configuracoes['sitemap_url'] ?? ''); ?>"
                                           placeholder="https://niumi.com.br/sitemap.xml">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="robots_txt" class="form-label">Robots.txt Personalizado</label>
                                    <textarea class="form-control" id="robots_txt" name="robots_txt" rows="5"><?php echo htmlspecialchars($configuracoes['robots_txt'] ?? ''); ?></textarea>
                                    <div class="form-text">Conteúdo personalizado para robots.txt</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Analytics & Tracking Avançado -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-line me-2"></i>Analytics & Tracking Avançado
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="google_analytics" class="form-label">Google Analytics 4 ID</label>
                                    <input type="text" class="form-control" id="google_analytics" name="google_analytics" 
                                           value="<?php echo htmlspecialchars($configuracoes['google_analytics'] ?? ''); ?>"
                                           placeholder="G-XXXXXXXXXX">
                                    <div class="form-text">ID do Google Analytics 4</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="google_tag_manager" class="form-label">Google Tag Manager ID</label>
                                    <input type="text" class="form-control" id="google_tag_manager" name="google_tag_manager" 
                                           value="<?php echo htmlspecialchars($configuracoes['google_tag_manager'] ?? ''); ?>"
                                           placeholder="GTM-XXXXXXX">
                                    <div class="form-text">ID do Google Tag Manager</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="facebook_pixel" class="form-label">Facebook Pixel ID</label>
                                    <input type="text" class="form-control" id="facebook_pixel" name="facebook_pixel" 
                                           value="<?php echo htmlspecialchars($configuracoes['facebook_pixel'] ?? ''); ?>"
                                           placeholder="XXXXXXXXXXXXXXX">
                                    <div class="form-text">ID do Facebook Pixel</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sistema -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-cogs me-2"></i>Sistema
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3 form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="manutencao" name="manutencao" 
                                           <?php echo ($configuracoes['manutencao'] ?? 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="manutencao">
                                        Modo Manutenção
                                    </label>
                                    <div class="form-text">Quando ativado, apenas administradores podem acessar o site</div>
                                </div>
                                
                                <hr>
                                
                                <p class="text-muted mb-1">
                                    <strong>Informações do Sistema:</strong>
                                </p>
                                <p class="text-muted mb-1">
                                    PHP: <?php echo phpversion(); ?>
                                </p>
                                <p class="text-muted mb-1">
                                    Servidor: <?php echo $_SERVER['SERVER_SOFTWARE']; ?>
                                </p>
                                <p class="text-muted mb-0">
                                    Última atualização: <?php echo date("d/m/Y H:i", filemtime(__FILE__)); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Salvar Configurações
                        </button>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<?php include 'templates/admin_footer.php'; ?>

