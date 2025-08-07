<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Painel Administrativo - NiUMi'; ?></title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        :root {
            --sidebar-width: 250px;
        }
        
        body {
            font-size: 0.875rem;
            background: #f8f9fa;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-green));
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .navbar-brand {
            padding-top: .75rem;
            padding-bottom: .75rem;
            font-size: 1rem;
            background: var(--primary-blue);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            color: var(--white) !important;
        }
        
        .navbar .navbar-toggler {
            top: .25rem;
            right: 1rem;
        }
        
        .navbar .form-control {
            padding: .75rem 1rem;
            border-width: 0;
            border-radius: 0;
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius);
            margin: 0.25rem 0.5rem;
            transition: var(--transition);
        }
        
        .sidebar .nav-link:hover {
            color: var(--white);
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            color: var(--white);
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .nav-link .fas {
            margin-right: 8px;
            width: 16px;
            text-align: center;
        }
        
        .sidebar-heading {
            font-size: .75rem;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.6);
            padding: 1rem 1rem 0.5rem;
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        main {
            margin-left: var(--sidebar-width);
            background: var(--white);
            min-height: 100vh;
        }
        
        .content-wrapper {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin: 1rem;
            padding: 2rem;
        }
        
        .navbar-nav .dropdown-menu {
            position: absolute;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .border-left-primary {
            border-left: 0.25rem solid var(--primary-blue) !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid var(--secondary-green) !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid var(--accent-yellow) !important;
        }
        
        .text-xs {
            font-size: 0.7rem;
        }
        
        .text-gray-300 {
            color: #dddfeb !important;
        }
        
        .text-gray-800 {
            color: #5a5c69 !important;
        }
        
        .card {
            border-radius: var(--border-radius);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: none;
        }
        
        .btn {
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        @media (max-width: 767.98px) {
            .sidebar {
                top: 0;
                width: 100%;
                height: auto;
                position: relative;
                background: var(--primary-blue);
            }
            
            main {
                margin-left: 0;
            }
            
            .content-wrapper {
                margin: 0.5rem;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar Superior -->
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="dashboard.php">
            <i class="fas fa-cube me-2"></i>NiUMi
        </a>
        
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="navbar-nav">
            <div class="nav-item text-nowrap">
                <div class="dropdown">
                    <a class="nav-link px-3 dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-2"></i><?php echo htmlspecialchars($_SESSION['admin_nome']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-user me-2"></i>Meu Perfil</a></li>
                        <li><a class="dropdown-item" href="configuracoes.php"><i class="fas fa-cog me-2"></i>Configurações</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../" target="_blank"><i class="fas fa-external-link-alt me-2"></i>Ver Site</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

