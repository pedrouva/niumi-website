<?php
require_once '../includes/config.php';

// Se já estiver logado, redirecionar para o dashboard
if (isLoggedIn()) {
    redirect('/admin/dashboard.php');
}

$error_message = '';

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $error_message = 'Por favor, preencha todos os campos.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, nome, senha_hash FROM usuarios_admin WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
                $_SESSION['admin_id'] = $usuario['id'];
                $_SESSION['admin_nome'] = $usuario['nome'];
                $_SESSION['admin_email'] = $email;
                redirect('/admin/dashboard.php');
            } else {
                $error_message = 'E-mail ou senha incorretos.';
            }
        } catch(PDOException $e) {
            $error_message = 'Erro interno. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Painel Administrativo NiUMi</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        body {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-green));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        
        .login-header {
            background: var(--primary-blue);
            color: var(--white);
            padding: 2rem;
            text-align: center;
        }
        
        .login-body {
            padding: 2rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .btn-login {
            background: var(--primary-blue);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .btn-login:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h2><i class="fas fa-cube me-2"></i>NiUMi</h2>
            <p class="mb-0">Painel Administrativo</p>
        </div>
        
        <div class="login-body">
            <?php if ($error_message): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error_message; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i>E-mail
                    </label>
                    <input type="email" class="form-control" id="email" name="email" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                           placeholder="seu@email.com">
                </div>
                
                <div class="mb-4">
                    <label for="senha" class="form-label">
                        <i class="fas fa-lock me-2"></i>Senha
                    </label>
                    <input type="password" class="form-control" id="senha" name="senha" required 
                           placeholder="Sua senha">
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Entrar
                    </button>
                </div>
            </form>
            
            <hr class="my-4">
            
            <div class="text-center">
                <small class="text-muted">
                    <i class="fas fa-shield-alt me-1"></i>
                    Área restrita - Acesso apenas para administradores
                </small>
            </div>
            
            <div class="text-center mt-3">
                <a href="../" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i>Voltar ao site
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Focar no primeiro campo ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
        
        // Adicionar efeito de loading no botão ao submeter
        document.querySelector('form').addEventListener('submit', function() {
            const button = document.querySelector('.btn-login');
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Entrando...';
            button.disabled = true;
        });
    </script>
</body>
</html>

