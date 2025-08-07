<?php
require_once '../includes/config.php';

// Verificar se está logado
if (!isLoggedIn()) {
    redirect('/admin/login.php');
}

$page_title = 'Produtos - Painel Administrativo';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Verificar se há mensagem de sucesso via GET
if (isset($_GET['success'])) {
    $success = $_GET['success'];
}

// Buscar categorias para o formulário
try {
    $stmt = $pdo->query("SELECT * FROM categorias ORDER BY nome");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $categorias = [];
}

// Buscar parceiros para o formulário
try {
    $stmt = $pdo->query("SELECT * FROM parceiros WHERE ativo = 1 ORDER BY nome");
    $parceiros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $parceiros = [];
}

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id'] ?? null;
        if ($id) {
            try {
                $stmt = $pdo->prepare("DELETE FROM produtos WHERE slug = :id");
                $stmt->bindParam(":id", $id);
                $stmt->execute();
                redirect("/admin/produtos.php?success=Produto excluído com sucesso!");
            } catch (PDOException $e) {
                $error = "Erro ao excluir produto: " . $e->getMessage();
            }
        }
    }
    if ($action === 'add' || $action === 'edit') {
        $nome = $_POST['nome'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $preco = !empty($_POST['preco']) ? floatval($_POST['preco']) : null;
        $imagem_url = $_POST['imagem_url'] ?? '';
        $video_youtube = $_POST['video_youtube'] ?? '';
        $link_afiliado = $_POST['link_afiliado'] ?? '';
        $categoria_id = !empty($_POST['categoria_id']) ? intval($_POST['categoria_id']) : null;
        $parceiro_id = !empty($_POST['parceiro_id']) ? intval($_POST['parceiro_id']) : null;
        $destaque = isset($_POST['destaque']) ? 1 : 0;
        $ativo = isset($_POST['ativo']) ? 1 : 0;
        
        // Campos de SEO
        $seo_title = $_POST['seo_title'] ?? '';
        $seo_description = $_POST['seo_description'] ?? '';
        $seo_keywords = $_POST['seo_keywords'] ?? '';
        $seo_canonical = $_POST['seo_canonical'] ?? '';
        $seo_og_title = $_POST['seo_og_title'] ?? '';
        $seo_og_description = $_POST['seo_og_description'] ?? '';
        $seo_og_image = $_POST['seo_og_image'] ?? '';
        $seo_schema_data = $_POST['seo_schema_data'] ?? '';
        $seo_focus_keyword = $_POST['seo_focus_keyword'] ?? '';
        $seo_alt_text = $_POST['seo_alt_text'] ?? '';
        
        // Gerar slug
        $slug = generateSlug($nome);
               // Processar upload de imagem
        $imagem_nome = $produto['imagem'] ?? null; // Manter imagem atual se não houver upload
        
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../assets/images/produtos/';
            
            // Criar diretório se não existir
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_info = pathinfo($_FILES['imagem']['name']);
            $extension = strtolower($file_info['extension']);
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($extension, $allowed_extensions)) {
                $imagem_nome = $slug . '.' . $extension;
                $upload_path = $upload_dir . $imagem_nome;
                
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $upload_path)) {
                    // Upload realizado com sucesso
                } else {
                    $error = "Erro ao fazer upload da imagem.";
                }
            } else {
                $error = "Formato de imagem não permitido. Use JPG, PNG ou GIF.";
            }
        }
        
        if (!$error) {
            try {
                if ($action === 'add') {
                    $stmt = $pdo->prepare("
                        INSERT INTO produtos (nome, slug, descricao, preco, imagem, imagem_url, video_youtube, link_afiliado, categoria_id, parceiro_id, destaque, ativo, seo_title, seo_description, seo_keywords, seo_canonical, seo_og_title, seo_og_description, seo_og_image, seo_schema_data, seo_focus_keyword, seo_alt_text) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $nome, $slug, $descricao, $preco, $imagem_nome, $imagem_url, $video_youtube, 
                        $link_afiliado, $categoria_id, $parceiro_id, $destaque, $ativo,
                        $seo_title, $seo_description, $seo_keywords, $seo_canonical, $seo_og_title, 
                        $seo_og_description, $seo_og_image, $seo_schema_data, $seo_focus_keyword, $seo_alt_text
                    ]);
                } else {
                    $stmt = $pdo->prepare("
                        UPDATE produtos 
                        SET nome = ?, slug = ?, descricao = ?, preco = ?, imagem = ?, imagem_url = ?, video_youtube = ?, 
                            link_afiliado = ?, categoria_id = ?, parceiro_id = ?, destaque = ?, ativo = ?,
                            seo_title = ?, seo_description = ?, seo_keywords = ?, seo_canonical = ?, seo_og_title = ?, 
                            seo_og_description = ?, seo_og_image = ?, seo_schema_data = ?, seo_focus_keyword = ?, seo_alt_text = ?
                        WHERE slug = ?
                    ");
                    $stmt->execute([
                        $nome, $slug, $descricao, $preco, $imagem_nome, $imagem_url, $video_youtube,
                        $link_afiliado, $categoria_id, $parceiro_id, $destaque, $ativo,
                        $seo_title, $seo_description, $seo_keywords, $seo_canonical, $seo_og_title, 
                        $seo_og_description, $seo_og_image, $seo_schema_data, $seo_focus_keyword, $seo_alt_text, $id
                    ]);
                }
                
                $success = $action === 'add' ? "Produto adicionado com sucesso!" : "Produto atualizado com sucesso!";
            } catch(PDOException $e) {
                $error = "Erro ao salvar produto: " . $e->getMessage();
            }
        }
    }
    
    if ($action === 'delete' && $id) {
        try {
            // Debug: verificar se está chegando aqui
            error_log("Tentando excluir produto com slug: " . $id);
            
            $stmt = $pdo->prepare("DELETE FROM produtos WHERE slug = ?");
            $result = $stmt->execute([$id]);
            
            error_log("Resultado da exclusão: " . ($result ? 'sucesso' : 'falha'));
            error_log("Linhas afetadas: " . $stmt->rowCount());
            
            if ($stmt->rowCount() > 0) {
                $success = "Produto excluído com sucesso!";
                // Redirecionar para evitar reenvio do formulário
                header("Location: produtos.php?success=" . urlencode($success));
                exit;
            } else {
                $error = "Produto não encontrado para exclusão.";
            }
        } catch(PDOException $e) {
            $error = "Erro ao excluir produto: " . $e->getMessage();
            error_log("Erro na exclusão: " . $e->getMessage());
        }
    }
}

// Buscar produtos
try {
    $stmt = $pdo->prepare("
        SELECT p.*, c.nome as categoria_nome 
        FROM produtos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        ORDER BY p.data_criacao DESC
    ");
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $produtos = [];
}

// Buscar produto específico para edição
$produto = null;
if ($action === 'edit' && $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE slug = ?");
        $stmt->execute([$id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Produto não encontrado.";
    }
}

include 'templates/admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'templates/admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <?php if ($action === 'add'): ?>
                        Adicionar Produto
                    <?php elseif ($action === 'edit'): ?>
                        Editar Produto
                    <?php else: ?>
                        Produtos
                    <?php endif; ?>
                </h1>
                
                <?php if ($action === 'list'): ?>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="produtos.php?action=add" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Novo Produto
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="produtos.php" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Voltar
                        </a>
                    </div>
                </div>
                <?php endif; ?>
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
            
            <?php if ($action === 'add' || $action === 'edit'): ?>
            <!-- Formulário de Produto -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-cube me-2"></i>
                                <?php echo $action === 'add' ? 'Novo Produto' : 'Editar Produto'; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label for="nome" class="form-label">Nome do Produto *</label>
                                        <input type="text" class="form-control" id="nome" name="nome" 
                                               value="<?php echo htmlspecialchars($produto['nome'] ?? ''); ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="categoria_id" class="form-label">Categoria *</label>
                                        <select class="form-select" id="categoria_id" name="categoria_id" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach ($categorias as $categoria): ?>
                                            <option value="<?php echo $categoria['id']; ?>" 
                                                    <?php echo ($produto['categoria_id'] ?? '') == $categoria['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($categoria['nome']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="parceiro_id" class="form-label">Parceiro *</label>
                                        <select class="form-select" id="parceiro_id" name="parceiro_id" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach ($parceiros as $parceiro_item): ?>
                                            <option value="<?php echo $parceiro_item['id']; ?>" 
                                                    <?php echo ($produto['parceiro_id'] ?? '') == $parceiro_item['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($parceiro_item['nome']); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="descricao" class="form-label">Descrição</label>
                                         <textarea class="form-control" id="descricao" name="descricao" rows="4"><?php echo $produto["descricao"] ?? ''; ?></textarea>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="video_youtube" class="form-label">Link do Vídeo YouTube</label>
                                        <input type="url" class="form-control" id="video_youtube" name="video_youtube" value="<?php echo htmlspecialchars($produto['video_youtube'] ?? ''); ?>" placeholder="https://www.youtube.com/watch?v=...">
                                        <div class="form-text">URL completa do vídeo no YouTube</div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="imagem" class="form-label">Imagem do Produto</label>
                                        <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                                        <div class="form-text">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</div>
                                        
                                        <?php if (!empty($produto['imagem'])): ?>
                                        <div class="mt-2">
                                            <img src="/assets/images/produtos/<?php echo $produto['imagem']; ?>" alt="Imagem atual" class="img-thumbnail" style="max-width: 200px;">
                                            <p class="text-muted mt-1">Imagem atual: <?php echo $produto['imagem']; ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="imagem_url" class="form-label">URL da Imagem (alternativa)</label>
                                        <input type="url" class="form-control" id="imagem_url" name="imagem_url" value="<?php echo htmlspecialchars($produto['imagem_url'] ?? ''); ?>" placeholder="https://exemplo.com/imagem.jpg">
                                        <div class="form-text">Use este campo se preferir usar uma URL externa para a imagem</div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="preco" class="form-label">Comissão</label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="number" class="form-control" id="preco" name="preco" 
                                                   step="0.01" value="<?php echo $produto['preco'] ?? ''; ?>" placeholder="0.00">
                                        </div>
                                        <div class="form-text">Valor que você irá receber por cada venda gerada através do link de compra</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="link_afiliado" class="form-label">Link de Compra</label>
                                        <input type="url" class="form-control" id="link_afiliado" name="link_afiliado" 
                                               value="<?php echo htmlspecialchars($produto['link_afiliado'] ?? ''); ?>" placeholder="https://exemplo.com/produto">
                                        <div class="form-text">URL do link de afiliado ou compra</div>
                                    </div>
                                </div>
                                
                                <!-- Seção de SEO -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="m-0">
                                                    <i class="fas fa-search me-2"></i>SEO White-Hat - Otimização para Mecanismos de Busca
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="seo_title" class="form-label">SEO Title</label>
                                                        <input type="text" class="form-control" id="seo_title" name="seo_title" 
                                                               value="<?php echo htmlspecialchars($produto['seo_title'] ?? ''); ?>" 
                                                               maxlength="60" placeholder="Título otimizado para SEO">
                                                        <div class="form-text">Máximo 60 caracteres - Aparece nos resultados de busca</div>
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label for="seo_focus_keyword" class="form-label">Palavra-chave Principal</label>
                                                        <input type="text" class="form-control" id="seo_focus_keyword" name="seo_focus_keyword" 
                                                               value="<?php echo htmlspecialchars($produto['seo_focus_keyword'] ?? ''); ?>" 
                                                               placeholder="palavra-chave principal">
                                                        <div class="form-text">Palavra-chave principal para rankeamento</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label for="seo_description" class="form-label">Meta Description</label>
                                                        <textarea class="form-control" id="seo_description" name="seo_description" rows="3" 
                                                                  maxlength="160"><?php echo htmlspecialchars($produto['seo_description'] ?? ''); ?></textarea>
                                                        <div class="form-text">Máximo 160 caracteres - Descrição que aparece nos resultados de busca</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="seo_keywords" class="form-label">Palavras-chave Secundárias</label>
                                                        <input type="text" class="form-control" id="seo_keywords" name="seo_keywords" 
                                                               value="<?php echo htmlspecialchars($produto['seo_keywords'] ?? ''); ?>" 
                                                               placeholder="palavra1, palavra2, palavra3">
                                                        <div class="form-text">Palavras-chave relacionadas separadas por vírgula</div>
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label for="seo_canonical" class="form-label">URL Canônica</label>
                                                        <input type="url" class="form-control" id="seo_canonical" name="seo_canonical" 
                                                               value="<?php echo htmlspecialchars($produto['seo_canonical'] ?? ''); ?>" 
                                                               placeholder="https://niumi.com.br/produto/nome-produto">
                                                        <div class="form-text">URL canônica para evitar conteúdo duplicado</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="seo_alt_text" class="form-label">Texto Alternativo da Imagem</label>
                                                        <input type="text" class="form-control" id="seo_alt_text" name="seo_alt_text" 
                                                               value="<?php echo htmlspecialchars($produto['seo_alt_text'] ?? ''); ?>" 
                                                               placeholder="Descrição da imagem para acessibilidade">
                                                        <div class="form-text">Texto alternativo para acessibilidade e SEO</div>
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label for="seo_og_image" class="form-label">Imagem Open Graph</label>
                                                        <input type="url" class="form-control" id="seo_og_image" name="seo_og_image" 
                                                               value="<?php echo htmlspecialchars($produto['seo_og_image'] ?? ''); ?>" 
                                                               placeholder="https://niumi.com.br/assets/images/produto.jpg">
                                                        <div class="form-text">Imagem para compartilhamento em redes sociais (1200x630px)</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="seo_og_title" class="form-label">Título Open Graph</label>
                                                        <input type="text" class="form-control" id="seo_og_title" name="seo_og_title" 
                                                               value="<?php echo htmlspecialchars($produto['seo_og_title'] ?? ''); ?>" 
                                                               placeholder="Título para redes sociais">
                                                        <div class="form-text">Título otimizado para compartilhamento</div>
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-3">
                                                        <label for="seo_og_description" class="form-label">Descrição Open Graph</label>
                                                        <textarea class="form-control" id="seo_og_description" name="seo_og_description" rows="2"><?php echo htmlspecialchars($produto['seo_og_description'] ?? ''); ?></textarea>
                                                        <div class="form-text">Descrição para compartilhamento em redes sociais</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label for="seo_schema_data" class="form-label">Schema.org JSON-LD</label>
                                                        <textarea class="form-control" id="seo_schema_data" name="seo_schema_data" rows="4" 
                                                                  placeholder='{"@context": "https://schema.org", "@type": "Product", "name": "Nome do Produto"}'><?php echo htmlspecialchars($produto['seo_schema_data'] ?? ''); ?></textarea>
                                                        <div class="form-text">Dados estruturados em formato JSON-LD para rich snippets</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="destaque" name="destaque" 
                                                   <?php echo ($produto['destaque'] ?? 0) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="destaque">
                                                Produto em Destaque
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="ativo" name="ativo" 
                                                   <?php echo ($produto['ativo'] ?? 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="ativo">
                                                Produto Ativo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="produtos.php" class="btn btn-outline-secondary me-md-2">
                                        <i class="fas fa-times me-1"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        <?php echo $action === 'add' ? 'Adicionar' : 'Atualizar'; ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-info-circle me-2"></i>Informações
                            </h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                <small>
                                    <i class="fas fa-lightbulb me-1"></i>
                                    O slug será gerado automaticamente baseado no nome do produto.
                                </small>
                            </p>
                            
                            <?php if ($produto): ?>
                            <div class="mb-3">
                                <strong>Slug:</strong><br>
                                <code><?php echo htmlspecialchars($produto['slug']); ?></code>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Criado em:</strong><br>
                                <?php echo date('d/m/Y H:i', strtotime($produto['data_criacao'])); ?>
                            </div>
                            
                            <div class="mb-3">
                                <a href="../produto.php?slug=<?php echo $produto['slug']; ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-eye me-1"></i>Ver no Site
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Lista de Produtos -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Lista de Produtos
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($produtos)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhum produto cadastrado ainda.</p>
                        <a href="produtos.php?action=add" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Adicionar Primeiro Produto
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nome</th>
                                    <th>Categoria</th>
                                    <th>Comissão</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($produtos as $produto): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($produto['nome']); ?></strong>
                                        <?php if ($produto['destaque']): ?>
                                        <span class="badge bg-warning text-dark ms-1">
                                            <i class="fas fa-star"></i> Destaque
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?php echo htmlspecialchars($produto['categoria_nome'] ?? 'Sem categoria'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($produto['preco']): ?>
                                            R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($produto['ativo']): ?>
                                        <span class="badge bg-success">Ativo</span>
                                        <?php else: ?>
                                        <span class="badge bg-danger">Inativo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($produto['data_criacao'])); ?></td>
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
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmarExclusao('<?php echo $produto['slug']; ?>', '<?php echo htmlspecialchars($produto['nome']); ?>')" 
                                                    title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="modalExclusao" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o produto <strong id="nomeProduto"></strong>?</p>
                <p class="text-danger"><small>Esta ação não pode ser desfeita.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="idProduto">
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarExclusao(slug, nome) {
    document.getElementById('nomeProduto').textContent = nome;
    document.getElementById('idProduto').value = slug;
    new bootstrap.Modal(document.getElementById('modalExclusao')).show();
}
</script>

<?php include 'templates/admin_footer.php'; ?>




// Gerar sitemap e robots.txt após operações de produto
require_once '../includes/seo_functions.php';
generateSitemap($pdo);
generateRobotsTxt();


