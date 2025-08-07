<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site em Manutenção - NiUMi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .maintenance-container {
            text-align: center;
            color: white;
            max-width: 600px;
            padding: 2rem;
        }
        
        .maintenance-icon {
            font-size: 5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .maintenance-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .maintenance-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .maintenance-description {
            font-size: 1rem;
            margin-bottom: 2rem;
            opacity: 0.8;
            line-height: 1.6;
        }
        
        .contact-info {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
            backdrop-filter: blur(10px);
        }
        
        .contact-info h5 {
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        .contact-info p {
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }
        
        .social-links {
            margin-top: 1.5rem;
        }
        
        .social-links a {
            color: white;
            font-size: 1.5rem;
            margin: 0 0.5rem;
            transition: opacity 0.3s ease;
        }
        
        .social-links a:hover {
            opacity: 0.7;
        }
        
        .spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 3px solid white;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon pulse">
            <i class="fas fa-tools"></i>
        </div>
        
        <h1 class="maintenance-title">Site em Manutenção</h1>
        
        <p class="maintenance-subtitle">
            Estamos trabalhando para melhorar sua experiência
        </p>
        
        <div class="spinner"></div>
        
        <p class="maintenance-description">
            Nosso site está temporariamente fora do ar para manutenção e melhorias. 
            Estamos trabalhando duro para voltar o mais rápido possível com novidades incríveis!
        </p>
        
        <p class="maintenance-description">
            <strong>Tempo estimado:</strong> Algumas horas<br>
            <strong>Última atualização:</strong> <?php echo date('d/m/Y H:i'); ?>
        </p>
        
        <div class="contact-info">
            <h5><i class="fas fa-envelope me-2"></i>Precisa de Ajuda?</h5>
            <p><strong>Email:</strong> contato@niumi.com.br</p>
            <p><strong>Telefone:</strong> +55 (11) 9999-9999</p>
            
            <div class="social-links">
                <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" title="Facebook"><i class="fab fa-facebook"></i></a>
            </div>
        </div>
        
        <div class="mt-4">
            <small class="opacity-75">
                <i class="fas fa-shield-alt me-1"></i>
                Seus dados estão seguros conosco
            </small>
        </div>
    </div>
    
    <script>
        // Auto-refresh da página a cada 5 minutos
        setTimeout(function() {
            location.reload();
        }, 300000); // 5 minutos
        
        // Atualizar horário em tempo real
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleString('pt-BR');
            // Você pode adicionar um elemento para mostrar o horário atual se desejar
        }
        
        setInterval(updateTime, 1000);
    </script>
</body>
</html>

