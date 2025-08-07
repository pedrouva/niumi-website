<?php
require_once '../includes/config.php';

// Verificar se está logado
if (!isLoggedIn()) {
    redirect('/admin/login.php');
}

$page_title = 'Backup - Painel Administrativo NiUMi';
include 'templates/admin_header.php';

$message = '';

// Processar ação de backup
if (isset($_POST['action']) && $_POST['action'] === 'backup') {
    try {
        $backup_dir = '../backups/';
        if (!is_dir($backup_dir)) {
            mkdir($backup_dir, 0755, true);
        }

        $filename = 'backup_niumi_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = $backup_dir . $filename;

        // Comando mysqldump
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            escapeshellarg(DB_USER),
            escapeshellarg(DB_PASS),
            escapeshellarg(DB_HOST),
            escapeshellarg(DB_NAME),
            escapeshellarg($filepath)
        );

        exec($command, $output, $return_var);

        if ($return_var === 0 && file_exists($filepath)) {
            $message = '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>Backup criado com sucesso: ' . $filename . '</div>';
        } else {
            $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Erro ao criar backup. Verifique as permissões e configurações do banco de dados.</div>';
        }
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Erro ao criar backup: ' . $e->getMessage() . '</div>';
    }
}

// Processar ação de restauração
if (isset($_POST['action']) && $_POST['action'] === 'restore' && isset($_POST['backup_file'])) {
    try {
        $filename = basename($_POST['backup_file']);
        $filepath = '../backups/' . $filename;
        
        if (file_exists($filepath) && pathinfo($filename, PATHINFO_EXTENSION) === 'sql') {
            // Comando mysql para restaurar
            $command = sprintf(
                'mysql --user=%s --password=%s --host=%s %s < %s',
                escapeshellarg(DB_USER),
                escapeshellarg(DB_PASS),
                escapeshellarg(DB_HOST),
                escapeshellarg(DB_NAME),
                escapeshellarg($filepath)
            );

            exec($command, $output, $return_var);

            if ($return_var === 0) {
                $message = '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>Backup restaurado com sucesso!</div>';
            } else {
                $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Erro ao restaurar backup. Verifique as permissões e configurações do banco de dados.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Arquivo de backup não encontrado.</div>';
        }
    } catch (Exception $e) {
        $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Erro ao restaurar backup: ' . $e->getMessage() . '</div>';
    }
}

// Listar backups existentes
$backups = [];
$backup_dir = '../backups/';
if (is_dir($backup_dir)) {
    $files = scandir($backup_dir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
            $backups[] = [
                'filename' => $file,
                'size' => filesize($backup_dir . $file),
                'date' => filemtime($backup_dir . $file)
            ];
        }
    }
    // Ordenar por data (mais recente primeiro)
    usort($backups, function($a, $b) {
        return $b['date'] - $a['date'];
    });
}

// Processar download de backup
if (isset($_GET['download']) && !empty($_GET['download'])) {
    $filename = basename($_GET['download']);
    $filepath = $backup_dir . $filename;
    
    if (file_exists($filepath) && pathinfo($filename, PATHINFO_EXTENSION) === 'sql') {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
}

// Processar exclusão de backup
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $filename = basename($_GET['delete']);
    $filepath = $backup_dir . $filename;
    
    if (file_exists($filepath) && pathinfo($filename, PATHINFO_EXTENSION) === 'sql') {
        if (unlink($filepath)) {
            $message = '<div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>Backup excluído com sucesso.</div>';
        } else {
            $message = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Erro ao excluir backup.</div>';
        }
        // Recarregar a lista de backups
        header('Location: backup.php');
        exit;
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'templates/admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="content-wrapper">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Backup do Sistema</h1>
                </div>
                
                <p class="mb-4">Gerencie os backups do banco de dados do seu marketplace.</p>

                <?= $message ?>

                <!-- Criar Novo Backup -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-plus-circle me-2"></i>Criar Novo Backup
                        </h6>
                    </div>
                    <div class="card-body">
                        <p>Crie um backup completo do banco de dados. O arquivo será salvo no servidor e poderá ser baixado posteriormente.</p>
                        <form method="POST">
                            <input type="hidden" name="action" value="backup">
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Deseja criar um novo backup do banco de dados?');">
                                <i class="fas fa-download me-2"></i>Criar Backup Agora
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Lista de Backups -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list me-2"></i>Backups Existentes
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (empty($backups)): ?>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>Nenhum backup encontrado.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nome do Arquivo</th>
                                            <th>Tamanho</th>
                                            <th>Data de Criação</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($backups as $backup): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($backup['filename']) ?></td>
                                                <td><?= number_format($backup['size'] / 1024, 2) ?> KB</td>
                                                <td><?= date('d/m/Y H:i:s', $backup['date']) ?></td>
                                                <td>
                                                    <a href="backup.php?download=<?= urlencode($backup['filename']) ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-download"></i> Baixar
                                                    </a>
                                                    <form method="POST" style="display: inline-block;" onsubmit="return confirm('Tem certeza que deseja restaurar este backup? Esta ação irá sobrescrever todos os dados atuais!');">
                                                        <input type="hidden" name="action" value="restore">
                                                        <input type="hidden" name="backup_file" value="<?= htmlspecialchars($backup['filename']) ?>">
                                                        <button type="submit" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-undo"></i> Restaurar
                                                        </button>
                                                    </form>
                                                    <a href="backup.php?delete=<?= urlencode($backup['filename']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este backup?');">
                                                        <i class="fas fa-trash"></i> Excluir
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Informações Importantes -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Informações Importantes
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li><strong>Frequência:</strong> Recomendamos fazer backups regulares, especialmente antes de atualizações importantes.</li>
                            <li><strong>Armazenamento:</strong> Os backups são salvos no servidor. Para maior segurança, baixe e armazene em local seguro.</li>
                            <li><strong>Restauração:</strong> Use o botão "Restaurar" para restaurar um backup. <strong>ATENÇÃO:</strong> Esta ação irá sobrescrever todos os dados atuais!</li>
                            <li><strong>Conteúdo:</strong> O backup inclui todas as tabelas e dados do banco de dados, mas não inclui arquivos de imagem.</li>
                            <li><strong>Segurança:</strong> Mantenha os backups em local seguro e faça cópias regulares para evitar perda de dados.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include 'templates/admin_footer.php'; ?>

