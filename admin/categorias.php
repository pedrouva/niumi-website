<?php
require_once '../includes/config.php';

// Verificar se está logado
if (!isLoggedIn()) {
    redirect('/admin/login.php');
}

$page_title = 'Categorias - Painel Administrativo';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Verificar se há mensagem de sucesso via GET
if (isset($_GET['success'])) {
    $success = $_GET['success'];
}

// Processar ações
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "delete") {
        $id = $_POST["id"] ?? null;
        if ($id) {
            try {
                // Verificar se há produtos nesta categoria
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM produtos WHERE categoria_id = ?");
                $stmt->execute([$id]);
                $count = $stmt->fetchColumn();
                
                if ($count > 0) {
                    $error = "Não é possível excluir esta categoria pois há produtos associados a ela.";
                } else {
                    $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
                    $stmt->execute([$id]);
                    $success = "Categoria excluída com sucesso!";
                    // Redirecionar para evitar reenvio do formulário
                    header("Location: categorias.php?success=" . urlencode($success));
                    exit;
                }
            } catch(PDOException $e) {
                $error = "Erro ao excluir categoria: " . $e->getMessage();
            }
        }
    }
    if ($action === 'add' || $action === 'edit') {
        $nome = $_POST['nome'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        
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
        
        // Gerar slug
        $slug = generateSlug($nome);
        
        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO categorias (nome, slug, descricao, seo_title, seo_description, seo_keywords, seo_canonical, seo_og_title, seo_og_description, seo_og_image, seo_schema_data, seo_focus_keyword) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nome, $slug, $descricao, $seo_title, $seo_description, $seo_keywords, $seo_canonical, $seo_og_title, $seo_og_description, $seo_og_image, $seo_schema_data, $seo_focus_keyword]);
                $success = "Categoria adicionada com sucesso!";
            } else {
                $stmt = $pdo->prepare("UPDATE categorias SET nome = ?, slug = ?, descricao = ?, seo_title = ?, seo_description = ?, seo_keywords = ?, seo_canonical = ?, seo_og_title = ?, seo_og_description = ?, seo_og_image = ?, seo_schema_data = ?, seo_focus_keyword = ? WHERE slug = ?");
                $stmt->execute([$nome, $slug, $descricao, $seo_title, $seo_description, $seo_keywords, $seo_canonical, $seo_og_title, $seo_og_description, $seo_og_image, $seo_schema_data, $seo_focus_keyword, $id]);
                $success = "Categoria atualizada com sucesso!";
            }
        } catch(PDOException $e) {
            $error = "Erro ao salvar categoria: " . $e->getMessage();
        }
    }
    
    if ($action === 'delete' && $id) {
        try {
            // Verificar se há produtos nesta categoria
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM produtos WHERE categoria_id = ?");
            $stmt->execute([$id]);
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                $error = "Não é possível excluir esta categoria pois há produtos associados a ela.";
            } else {
                $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
                $stmt->execute([$id]);
                $success = "Categoria excluída com sucesso!";
                // Redirecionar para evitar reenvio do formulário
                header("Location: categorias.php?success=" . urlencode($success));
                exit;
            }
        } catch(PDOException $e) {
            $error = "Erro ao excluir categoria: " . $e->getMessage();
        }
    }
}

// Buscar categorias
try {
    $stmt = $pdo->prepare("
        SELECT c.*, COUNT(p.id) as total_produtos 
        FROM categorias c 
        LEFT JOIN produtos p ON c.id = p.categoria_id 
        GROUP BY c.id 
        ORDER BY c.nome
    ");
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $categorias = [];
}

// Buscar categoria específica para edição
$categoria = null;
if ($action === 'edit' && $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Categoria não encontrada.";
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
                        Adicionar Categoria
                    <?php elseif ($action === 'edit'): ?>
                        Editar Categoria
                    <?php else: ?>
                        Categorias
                    <?php endif; ?>
                </h1>
                
                <?php if ($action === 'list'): ?>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="categorias.php?action=add" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Nova Categoria
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="categorias.php" class="btn btn-sm btn-outline-secondary">
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
            <!-- Formulário de Categoria -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-tags me-2"></i>
                                <?php echo $action === 'add' ? 'Nova Categoria' : 'Editar Categoria'; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="action" value="<?php echo htmlspecialchars($action); ?>">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome da Categoria *</label>
                                    <input type="text" class="form-control" id="nome" name="nome" 
                                           value="<?php echo htmlspecialchars($categoria['nome'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="descricao" class="form-label">Descrição</label>
                                    <textarea class="form-control" id="descricao" name="descricao" rows="4"><?php echo htmlspecialchars($categoria['descricao'] ?? ''); ?></textarea>
                                </div>
                                
                                <!-- Seção de SEO -->
                                <div class="card border-primary mt-4">
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
                                                       value="<?php echo htmlspecialchars($categoria['seo_title'] ?? ''); ?>" 
                                                       maxlength="60" placeholder="Título otimizado para SEO">
                                                <div class="form-text">Máximo 60 caracteres - Aparece nos resultados de busca</div>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label for="seo_focus_keyword" class="form-label">Palavra-chave Principal</label>
                                                <input type="text" class="form-control" id="seo_focus_keyword" name="seo_focus_keyword" 
                                                       value="<?php echo htmlspecialchars($categoria['seo_focus_keyword'] ?? ''); ?>" 
                                                       placeholder="palavra-chave principal">
                                                <div class="form-text">Palavra-chave principal para rankeamento</div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="seo_description" class="form-label">Meta Description</label>
                                                <textarea class="form-control" id="seo_description" name="seo_description" rows="3" 
                                                          maxlength="160"><?php echo htmlspecialchars($categoria['seo_description'] ?? ''); ?></textarea>
                                                <div class="form-text">Máximo 160 caracteres - Descrição que aparece nos resultados de busca</div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="seo_keywords" class="form-label">Palavras-chave Secundárias</label>
                                                <input type="text" class="form-control" id="seo_keywords" name="seo_keywords" 
                                                       value="<?php echo htmlspecialchars($categoria['seo_keywords'] ?? ''); ?>" 
                                                       placeholder="palavra1, palavra2, palavra3">
                                                <div class="form-text">Palavras-chave relacionadas separadas por vírgula</div>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label for="seo_canonical" class="form-label">URL Canônica</label>
                                                <input type="url" class="form-control" id="seo_canonical" name="seo_canonical" 
                                                       value="<?php echo htmlspecialchars($categoria['seo_canonical'] ?? ''); ?>" 
                                                       placeholder="https://niumi.com.br/categoria/nome-categoria">
                                                <div class="form-text">URL canônica para evitar conteúdo duplicado</div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="seo_og_title" class="form-label">Título Open Graph</label>
                                                <input type="text" class="form-control" id="seo_og_title" name="seo_og_title" 
                                                       value="<?php echo htmlspecialchars($categoria['seo_og_title'] ?? ''); ?>" 
                                                       placeholder="Título para redes sociais">
                                                <div class="form-text">Título otimizado para compartilhamento</div>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label for="seo_og_image" class="form-label">Imagem Open Graph</label>
                                                <input type="url" class="form-control" id="seo_og_image" name="seo_og_image" 
                                                       value="<?php echo htmlspecialchars($categoria['seo_og_image'] ?? ''); ?>" 
                                                       placeholder="https://niumi.com.br/assets/images/categoria.jpg">
                                                <div class="form-text">Imagem para compartilhamento em redes sociais (1200x630px)</div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="seo_og_description" class="form-label">Descrição Open Graph</label>
                                                <textarea class="form-control" id="seo_og_description" name="seo_og_description" rows="2"><?php echo htmlspecialchars($categoria['seo_og_description'] ?? ''); ?></textarea>
                                                <div class="form-text">Descrição para compartilhamento em redes sociais</div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="seo_schema_data" class="form-label">Schema.org JSON-LD</label>
                                                <textarea class="form-control" id="seo_schema_data" name="seo_schema_data" rows="4" 
                                                          placeholder='{"@context": "https://schema.org", "@type": "CategoryCode", "name": "Nome da Categoria"}'><?php echo htmlspecialchars($categoria['seo_schema_data'] ?? ''); ?></textarea>
                                                <div class="form-text">Dados estruturados em formato JSON-LD para rich snippets</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="categorias.php" class="btn btn-outline-secondary me-md-2">
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
                                    As categorias ajudam a organizar seus produtos.
                                </small>
                            </p>
                            
                            <?php if ($categoria): ?>
                            <div class="mb-3">
                                <strong>ID:</strong><br>
                                <code><?php echo $categoria['id']; ?></code>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Lista de Categorias -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Lista de Categorias
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($categorias)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhuma categoria cadastrada ainda.</p>
                        <a href="categorias.php?action=add" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Adicionar Primeira Categoria
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>Produtos</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categorias as $categoria): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($categoria['nome']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($categoria['descricao'] ?: '-'); ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo $categoria['total_produtos']; ?> produto(s)
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="categorias.php?action=edit&id=<?php echo $categoria['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($categoria['total_produtos'] == 0): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmarExclusao(<?php echo $categoria['id']; ?>, '<?php echo htmlspecialchars($categoria['nome']); ?>')" 
                                                    title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger" disabled 
                                                    title="Não é possível excluir - há produtos associados">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
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
                <p>Tem certeza que deseja excluir a categoria <strong id="nomeCategoria"></strong>?</p>
                <p class="text-danger"><small>Esta ação não pode ser desfeita.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="idCategoria">
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarExclusao(id, nome) {
    document.getElementById('nomeCategoria').textContent = nome;
    document.getElementById('idCategoria').value = id;
    new bootstrap.Modal(document.getElementById('modalExclusao')).show();
}
</script>

<?php include 'templates/admin_footer.php'; ?>




// Gerar sitemap e robots.txt após operações de categoria
require_once '../includes/seo_functions.php';
generateSitemap($pdo);
generateRobotsTxt();


