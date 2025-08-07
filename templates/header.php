<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'NiUMi - Marketplace de Produtos e Serviços Digitais'; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Descubra infoprodutos, ferramentas digitais, cursos online e soluções de IA na NiUMi, seu marketplace afiliado de confiança.'; ?>">
    <meta name="keywords" content="<?php echo isset($page_keywords) ? $page_keywords : 'infoprodutos, marketplace, produtos digitais, cursos online, ferramentas digitais, inteligência artificial'; ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo isset($page_title) ? $page_title : 'NiUMi - Marketplace de Produtos e Serviços Digitais'; ?>">
    <meta property="og:description" content="<?php echo isset($page_description) ? $page_description : 'Descubra infoprodutos, ferramentas digitais, cursos online e soluções de IA na NiUMi, seu marketplace afiliado de confiança.'; ?>">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/assets/images/niumi-og-image.jpg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php echo isset($page_title) ? $page_title : 'NiUMi - Marketplace de Produtos e Serviços Digitais'; ?>">
    <meta property="twitter:description" content="<?php echo isset($page_description) ? $page_description : 'Descubra infoprodutos, ferramentas digitais, cursos online e soluções de IA na NiUMi, seu marketplace afiliado de confiança.'; ?>">
    <meta property="twitter:image" content="<?php echo SITE_URL; ?>/assets/images/niumi-og-image.jpg">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo SITE_URL; ?>/assets/images/favicon.ico">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    
    <!-- Google Analytics (opcional) -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script> -->
</head>
<body>
    <!-- Navegação Principal -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo SITE_URL; ?>">
                <i class="fas fa-cube me-2"></i>NiUMi
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>">
                            <i class="fas fa-home me-1"></i>Início
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-th-large me-1"></i>Categorias
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php
                            // Buscar categorias para o menu
                            try {
                                $stmt = $pdo->query("SELECT nome, slug FROM categorias ORDER BY nome");
                                while ($categoria = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<li><a class="dropdown-item" href="' . SITE_URL . '/categoria.php?slug=' . $categoria['slug'] . '">' . htmlspecialchars($categoria['nome']) . '</a></li>';
                                }
                            } catch(PDOException $e) {
                                // Em caso de erro, não exibir nada
                            }
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/sobre.php">
                            <i class="fas fa-info-circle me-1"></i>Sobre
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/contato.php">
                            <i class="fas fa-envelope me-1"></i>Contato
                        </a>
                    </li>
                </ul>
                
                <!-- Barra de Pesquisa -->
                <form class="d-flex" method="GET" action="<?php echo SITE_URL; ?>/busca.php">
                    <div class="input-group">
                        <input class="form-control search-input" type="search" name="q" placeholder="Buscar produtos..." aria-label="Buscar" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </nav>
    
    <!-- Espaçamento para navbar fixa -->
    <div style="height: 80px;"></div>

