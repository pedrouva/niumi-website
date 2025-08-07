<?php
require_once 'includes/config.php';

// Configurações da página
$page_title = 'Sobre a NiUMi - Marketplace de Produtos e Serviços Digitais';
$page_description = 'Conheça a história da NiUMi, nossa missão de organizar a oferta de produtos digitais na internet e como conectamos consumidores a produtores de conteúdo digital.';
$page_keywords = 'sobre niumi, marketplace digital, pedro uva, produtos digitais, infoprodutos, história da empresa';

include 'templates/header.php';
?>

<!-- Breadcrumb -->
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sobre Nós</li>
        </ol>
    </nav>
</div>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="fade-in-up">Sobre a NiUMi</h1>
                <p class="fade-in-up">Organizando a oferta de produtos digitais na internet, conectando consumidores a produtores de conteúdo digital através de uma vitrine moderna e categorizada.</p>
            </div>
            <div class="col-lg-6">
                <div class="text-center fade-in-up">
                    <i class="fas fa-handshake" style="font-size: 8rem; opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nossa História -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="section-title">Nossa História</h2>
                <div class="content-text">
                    <p class="lead">A <strong>NiUMi</strong> nasceu da visão de criar uma plataforma digital especializada na curadoria e divulgação de produtos e serviços digitais de qualidade.</p>
                    
                    <p>Fundada por <strong>Pedro Uva</strong>, profissional com mais de 20 anos de experiência no setor de tapetes e empreendedor digital, a NiUMi representa a evolução natural de quem sempre entendeu a importância de conectar pessoas aos produtos certos.</p>
                    
                    <p>Com foco em <strong>infoprodutos, ferramentas digitais, cursos online e soluções baseadas em inteligência artificial</strong>, nossa plataforma atua como um marketplace afiliado, onde cada produto é cuidadosamente selecionado e redirecionado para a página oficial de venda do produtor.</p>
                    
                    <p>Acreditamos que a internet está repleta de produtos digitais valiosos, mas muitas vezes é difícil encontrar exatamente o que precisamos. Por isso, criamos a NiUMi: para organizar, categorizar e apresentar essas soluções de forma clara e acessível.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Nossa Missão -->
<section class="section bg-light-custom">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="text-center">
                    <div class="category-icon mb-3">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Nossa Missão</h3>
                    <p>Organizar a oferta de produtos digitais na internet, reunindo em um só lugar recomendações validadas, categorizadas e otimizadas para SEO.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="text-center">
                    <div class="category-icon mb-3">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Nossa Visão</h3>
                    <p>Ser a principal referência em curadoria de produtos digitais, promovendo a visibilidade de pequenos e grandes produtores.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="text-center">
                    <div class="category-icon mb-3">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Nossos Valores</h3>
                    <p>Transparência, qualidade, inovação e compromisso em ajudar o consumidor final a encontrar o que precisa com agilidade e segurança.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Como Funcionamos -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Como Funcionamos</h2>
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-search fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4>Curadoria Especializada</h4>
                        <p>Nossa equipe analisa e seleciona cuidadosamente cada produto digital, garantindo qualidade e relevância para nossos usuários.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-tags fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4>Categorização Inteligente</h4>
                        <p>Organizamos os produtos em categorias claras e intuitivas, facilitando a navegação e descoberta de novos conteúdos.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-link fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4>Redirecionamento Direto</h4>
                        <p>Conectamos você diretamente ao produtor através de links de afiliado, garantindo que você acesse o produto original.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4>Otimização para SEO</h4>
                        <p>Cada produto é otimizado para mecanismos de busca, aumentando a visibilidade tanto para consumidores quanto para produtores.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tecnologia -->
<section class="section bg-light-custom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title text-start">Tecnologia e Inovação</h2>
                <p>A plataforma NiUMi está sendo desenvolvida com tecnologias modernas e confiáveis:</p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>PHP puro</strong> - Garantindo leveza e performance</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>Bootstrap</strong> - Design responsivo e moderno</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>MySQL</strong> - Banco de dados robusto e escalável</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i><strong>SEO Otimizado</strong> - URLs amigáveis e meta tags configuráveis</li>
                </ul>
                <p class="text-muted">Futuramente, expandiremos para um <strong>aplicativo em Flutter</strong>, mantendo a mesma lógica de usabilidade e estrutura.</p>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="fas fa-code" style="font-size: 8rem; color: var(--primary-blue); opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Fundador -->
<section class="section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="fas fa-user-tie" style="font-size: 8rem; color: var(--primary-blue); opacity: 0.8;"></i>
                </div>
            </div>
            <div class="col-lg-6">
                <h2 class="section-title text-start">Conheça o Fundador</h2>
                <h4>Pedro Uva</h4>
                <p class="lead">Empreendedor com mais de 20 anos de experiência</p>
                <p>Pedro Uva traz para a NiUMi uma vasta experiência no setor empresarial, tendo atuado por mais de duas décadas no setor de tapetes. Sua transição para o mundo digital representa a evolução natural de um empreendedor que sempre entendeu a importância de conectar pessoas aos produtos certos.</p>
                <p>Com visão estratégica e paixão por inovação, Pedro fundou a NiUMi com o objetivo de democratizar o acesso a produtos digitais de qualidade, criando uma ponte entre produtores de conteúdo e consumidores que buscam soluções eficazes.</p>
            </div>
        </div>
    </div>
</section>

<!-- Nossos Números -->
<section class="section bg-light-custom">
    <div class="container">
        <h2 class="section-title">A NiUMi em Números</h2>
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="bg-white p-4 rounded-custom">
                    <i class="fas fa-cube fa-3x text-primary mb-3"></i>
                    <h3 class="text-primary">100+</h3>
                    <p>Produtos Curados</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="bg-white p-4 rounded-custom">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h3 class="text-primary">50+</h3>
                    <p>Parceiros Ativos</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="bg-white p-4 rounded-custom">
                    <i class="fas fa-tags fa-3x text-primary mb-3"></i>
                    <h3 class="text-primary">10+</h3>
                    <p>Categorias</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="bg-white p-4 rounded-custom">
                    <i class="fas fa-star fa-3x text-primary mb-3"></i>
                    <h3 class="text-primary">4.8</h3>
                    <p>Avaliação Média</p>
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
                <h2 class="mb-4" style="color: white;">Faça Parte da Nossa Jornada</h2>
                <p class="lead mb-4">Seja você um consumidor em busca de produtos digitais de qualidade ou um produtor que deseja ampliar seu alcance, a NiUMi é o lugar certo para você.</p>
                <div>
                    <a href="/" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-shopping-cart me-2"></i>Explorar Produtos
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

