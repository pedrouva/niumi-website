<?php
require_once '../includes/config.php';

// Verificar se está logado
if (!isLoggedIn()) {
    redirect('/admin/login.php');
}

$page_title = 'Usuários Admin - Painel Administrativo';
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
                // Não permitir excluir o próprio usuário
                if ($id == $_SESSION["admin_id"]) {
                    $error = "Você não pode excluir seu próprio usuário.";
                } else {
                    $stmt = $pdo->prepare("DELETE FROM admin_users WHERE id = ?");
                    $stmt->execute([$id]);
                    $success = "Usuário excluído com sucesso!";
                    // Redirecionar para evitar reenvio do formulário
                    header("Location: usuarios.php?success=" . urlencode($success));
                    exit;
                }
            } catch(PDOException $e) {
                $error = "Erro ao excluir usuário: " . $e->getMessage();
            }
        }
    }
    if ($action === 'add' || $action === 'edit') {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $ativo = isset($_POST['ativo']) ? 1 : 0;
        
        try {
            if ($action === 'add') {
                // Verificar se email já existe
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetchColumn() > 0) {
                    $error = "Este email já está sendo usado por outro usuário.";
                } else {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO admin_users (nome, email, senha, ativo) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$nome, $email, $senha_hash, $ativo]);
                    $success = "Usuário adicionado com sucesso!";
                }
            } else {
                if (!empty($senha)) {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE admin_users SET nome = ?, email = ?, senha = ?, ativo = ? WHERE id = ?");
                    $stmt->execute([$nome, $email, $senha_hash, $ativo, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE admin_users SET nome = ?, email = ?, ativo = ? WHERE id = ?");
                    $stmt->execute([$nome, $email, $ativo, $id]);
                }
                $success = "Usuário atualizado com sucesso!";
            }
        } catch(PDOException $e) {
            $error = "Erro ao salvar usuário: " . $e->getMessage();
        }
    }
    
    if ($action === 'delete' && $id) {
        try {
            // Não permitir excluir o próprio usuário
            if ($id == $_SESSION['admin_id']) {
                $error = "Você não pode excluir seu próprio usuário.";
            } else {
                $stmt = $pdo->prepare("DELETE FROM admin_users WHERE id = ?");
                $stmt->execute([$id]);
                $success = "Usuário excluído com sucesso!";
                // Redirecionar para evitar reenvio do formulário
                header("Location: usuarios.php?success=" . urlencode($success));
                exit;
            }
        } catch(PDOException $e) {
            $error = "Erro ao excluir usuário: " . $e->getMessage();
        }
    }
}

// Buscar usuários
try {
    $stmt = $pdo->query("SELECT * FROM admin_users ORDER BY nome");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $usuarios = [];
}

// Buscar usuário específico para edição
$usuario = null;
if ($action === 'edit' && $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $error = "Usuário não encontrado.";
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
                        Adicionar Usuário Admin
                    <?php elseif ($action === 'edit'): ?>
                        Editar Usuário Admin
                    <?php else: ?>
                        Usuários Admin
                    <?php endif; ?>
                </h1>
                
                <?php if ($action === 'list'): ?>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="usuarios.php?action=add" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Novo Usuário
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="usuarios.php" class="btn btn-sm btn-outline-secondary">
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
            <!-- Formulário de Usuário -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-user-shield me-2"></i>
                                <?php echo $action === 'add' ? 'Novo Usuário Admin' : 'Editar Usuário Admin'; ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome Completo *</label>
                                    <input type="text" class="form-control" id="nome" name="nome" 
                                           value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="senha" class="form-label">
                                        Senha <?php echo $action === 'edit' ? '(deixe em branco para manter a atual)' : '*'; ?>
                                    </label>
                                    <input type="password" class="form-control" id="senha" name="senha" 
                                           <?php echo $action === 'add' ? 'required' : ''; ?>>
                                    <?php if ($action === 'edit'): ?>
                                    <div class="form-text">Deixe em branco se não quiser alterar a senha.</div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="ativo" name="ativo" 
                                               <?php echo ($usuario['ativo'] ?? 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="ativo">
                                            Usuário Ativo
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="usuarios.php" class="btn btn-outline-secondary me-md-2">
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
                                    Usuários admin têm acesso total ao painel administrativo.
                                </small>
                            </p>
                            
                            <?php if ($usuario): ?>
                            <div class="mb-3">
                                <strong>ID:</strong><br>
                                <code><?php echo $usuario['id']; ?></code>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Criado em:</strong><br>
                                <?php echo date('d/m/Y H:i', strtotime($usuario['data_criacao'])); ?>
                            </div>
                            
                            <?php if ($usuario['ultimo_login']): ?>
                            <div class="mb-3">
                                <strong>Último Login:</strong><br>
                                <?php echo date('d/m/Y H:i', strtotime($usuario['ultimo_login'])); ?>
                            </div>
                            <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Lista de Usuários -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Lista de Usuários Admin
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (empty($usuarios)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhum usuário cadastrado ainda.</p>
                        <a href="usuarios.php?action=add" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Adicionar Primeiro Usuário
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Último Login</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($usuario['nome']); ?></strong>
                                        <?php if ($usuario['id'] == $_SESSION['admin_id']): ?>
                                        <span class="badge bg-info text-dark ms-1">Você</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                    <td>
                                        <?php if ($usuario['ativo']): ?>
                                        <span class="badge bg-success">Ativo</span>
                                        <?php else: ?>
                                        <span class="badge bg-danger">Inativo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($usuario['ultimo_login']): ?>
                                            <?php echo date('d/m/Y H:i', strtotime($usuario['ultimo_login'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">Nunca</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="usuarios.php?action=edit&id=<?php echo $usuario['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($usuario['id'] != $_SESSION['admin_id']): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmarExclusao(<?php echo $usuario['id']; ?>, '<?php echo htmlspecialchars($usuario['nome']); ?>')" 
                                                    title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger" disabled 
                                                    title="Você não pode excluir seu próprio usuário">
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
                <p>Tem certeza que deseja excluir o usuário <strong id="nomeUsuario"></strong>?</p>
                <p class="text-danger"><small>Esta ação não pode ser desfeita.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="idUsuario">
                    <button type="submit" class="btn btn-danger">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarExclusao(id, nome) {
    document.getElementById('nomeUsuario').textContent = nome;
    document.getElementById('idUsuario').value = id;
    new bootstrap.Modal(document.getElementById('modalExclusao')).show();
}
</script>

<?php include 'templates/admin_footer.php'; ?>

