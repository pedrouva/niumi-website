<?php
require_once '../includes/config.php';

// Verificar se está logado
if (!isLoggedIn()) {
    redirect('/admin/login.php');
}

$page_title = 'Parceiros - Painel Administrativo';
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
                $stmt = $pdo->prepare("DELETE FROM parceiros WHERE id = ?");
                $stmt->execute([$id]);
                $success = "Parceiro excluído com sucesso!";
                header("Location: parceiros.php?success=" . urlencode($success));
                exit;
            } catch(PDOException $e) {
                $error = "Erro ao excluir parceiro: " . $e->getMessage();
            }
        }
    }
    if ($action === 'add' || $action === 'edit') {
        $nome = $_POST['nome'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $ativo = isset($_POST['ativo']) ? 1 : 0;
        
        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO parceiros (nome, descricao, ativo) VALUES (?, ?, ?)");
                $stmt->execute([$nome, $descricao, $ativo]);
                $success = "Parceiro adicionado com sucesso!";
            } else {
                $stmt = $pdo->prepare("UPDATE parceiros SET nome = ?, descricao = ?, ativo = ? WHERE id = ?");
                $stmt->execute([$nome, $descricao, $ativo, $id]);
                $success = "Parceiro atualizado com sucesso!";
            }
        } catch(PDOException $e) {
            $error = "Erro ao salvar parceiro: " . $e->getMessage();
        }
    }
    
    if ($action === 'delete' && $id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM parceiros WHERE id = ?");
            $stmt->execute([$id]);
            $success = "Parceiro excluído com sucesso!";
            // Redirecionar para evitar reenvio do formulário
            header("Location: parceiros.php?success=" . urlencode($success));
            exit;
        } catch(PDOException $e) {
            $error = "Erro ao excluir parceiro: " . $e->getMessage();
        }
    }
}

// Buscar parceiros
try {
    $stmt = $pdo->query("SELECT * FROM parceiros ORDER BY nome");
    $parceiros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $parceiros = [];
}

// Buscar parceiro específico para edição
$parceiro = null;
if ($action === 'edit' && $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM parceiros WHERE id = ?");
        $stmt->execute([$id]);
        $parceiro = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Parceiro não encontrado.";
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
                        Adicionar Parceiro
                    <?php elseif ($action === 'edit'): ?>
                        Editar Parceiro
                    <?php else: ?>
                        Parceiros
                    <?php endif; ?>
                </h1>
                
                <?php if ($action === 'list'): ?>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="parceiros.php?action=add" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Novo Parceiro
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="parceiros.php" class="btn btn-sm btn-outline-secondary">
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
            <!-- Formulário de Parceiro -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-users me-2"></i>
                                <?php echo $action === 'add' ? 'Novo Parceiro' : 'Editar Parceiro'; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome do Parceiro *</label>
                                    <input type="text" class="form-control" id="nome" name="nome" 
                                           value="<?php echo htmlspecialchars($parceiro['nome'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="descricao" class="form-label">Descrição</label>
                                    <textarea class="form-control" id="descricao" name="descricao" rows="4"><?php echo htmlspecialchars($parceiro['descricao'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="ativo" name="ativo" 
                                               <?php echo ($parceiro['ativo'] ?? 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="ativo">
                                            Parceiro Ativo
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="parceiros.php" class="btn btn-outline-secondary me-md-2">
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
                                    Os parceiros podem ser exibidos na página principal do site.
                                </small>
                            </p>
                            
                            <?php if ($parceiro): ?>
                            <div class="mb-3">
                                <strong>ID:</strong><br>
                                <code><?php echo $parceiro['id']; ?></code>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Lista de Parceiros -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Lista de Parceiros
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($parceiros)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhum parceiro cadastrado ainda.</p>
                        <a href="parceiros.php?action=add" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Adicionar Primeiro Parceiro
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>Status</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($parceiros as $parceiro): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($parceiro['nome']); ?></strong></td>
                                    <td><?php echo htmlspecialchars(substr($parceiro['descricao'] ?: '-', 0, 100)); ?><?php echo strlen($parceiro['descricao']) > 100 ? '...' : ''; ?></td>
                                    <td>
                                        <?php if ($parceiro['ativo']): ?>
                                        <span class="badge bg-success">Ativo</span>
                                        <?php else: ?>
                                        <span class="badge bg-danger">Inativo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="parceiros.php?action=edit&id=<?php echo $parceiro['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmarExclusao(<?php echo $parceiro['id']; ?>, '<?php echo htmlspecialchars($parceiro['nome']); ?>')" 
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
                <p>Tem certeza que deseja excluir o parceiro <strong id="nomeParceiro"></strong>?</p>
                <p class="text-danger"><small>Esta ação não pode ser desfeita.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="idParceiro">
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarExclusao(id, nome) {
    document.getElementById('nomeParceiro').textContent = nome;
    document.getElementById('idParceiro').value = id;
    new bootstrap.Modal(document.getElementById('modalExclusao')).show();
}
</script>

<?php include 'templates/admin_footer.php'; ?>

