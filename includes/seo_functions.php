<?php

function generateSitemap($pdo) {
    $sitemap_content = '<?xml version="1.0" encoding="UTF-8"?>\n';
    $sitemap_content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';

    // Páginas estáticas
    $static_pages = [
        '/',
        '/sobre.php',
        '/contato.php',
        '/busca.php'
    ];

    foreach ($static_pages as $page) {
        $sitemap_content .= '  <url>\n';
        $sitemap_content .= '    <loc>' . SITE_URL . $page . '</loc>\n';
        $sitemap_content .= '    <lastmod>' . date('Y-m-d') . '</lastmod>\n';
        $sitemap_content .= '    <changefreq>daily</changefreq>\n';
        $sitemap_content .= '    <priority>0.8</priority>\n';
        $sitemap_content .= '  </url>\n';
    }

    // Categorias
    try {
        $stmt = $pdo->query("SELECT slug, data_atualizacao FROM categorias");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sitemap_content .= '  <url>\n';
            $sitemap_content .= '    <loc>' . SITE_URL . '/categoria.php?slug=' . htmlspecialchars($row['slug']) . '</loc>\n';
            $sitemap_content .= '    <lastmod>' . date('Y-m-d', strtotime($row['data_atualizacao'])) . '</lastmod>\n';
            $sitemap_content .= '    <changefreq>weekly</changefreq>\n';
            $sitemap_content .= '    <priority>0.7</priority>\n';
            $sitemap_content .= '  </url>\n';
        }
    } catch(PDOException $e) {
        error_log("Erro ao gerar sitemap para categorias: " . $e->getMessage());
    }

    // Produtos
    try {
        $stmt = $pdo->query("SELECT slug, data_atualizacao FROM produtos WHERE ativo = 1");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sitemap_content .= '  <url>\n';
            $sitemap_content .= '    <loc>' . SITE_URL . '/produto.php?slug=' . htmlspecialchars($row['slug']) . '</loc>\n';
            $sitemap_content .= '    <lastmod>' . date('Y-m-d', strtotime($row['data_atualizacao'])) . '</lastmod>\n';
            $sitemap_content .= '    <changefreq>daily</changefreq>\n';
            $sitemap_content .= '    <priority>0.9</priority>\n';
            $sitemap_content .= '  </url>\n';
        }
    } catch(PDOException $e) {
        error_log("Erro ao gerar sitemap para produtos: " . $e->getMessage());
    }

    $sitemap_content .= '</urlset>';

    file_put_contents(__DIR__ . '/../sitemap.xml', $sitemap_content);
}

function generateRobotsTxt() {
    $robots_content = "User-agent: *\n";
    $robots_content .= "Allow: /\n\n";
    $robots_content .= "Sitemap: " . SITE_URL . "/sitemap.xml\n";

    file_put_contents(__DIR__ . '/../robots.txt', $robots_content);
}

?>

