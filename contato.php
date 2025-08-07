<?php
include_once 'includes/config.php';
include_once 'templates/header.php';
?>

<main class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1 class="text-center mb-4">Entre em Contato</h1>
            <p class="lead text-center">Tem alguma dúvida, sugestão ou precisa de suporte? Preencha o formulário abaixo ou utilize nossos canais de contato.</p>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <form action="#" method="POST">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Endereço de E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="assunto" class="form-label">Assunto</label>
                            <input type="text" class="form-control" id="assunto" name="assunto" required>
                        </div>
                        <div class="mb-3">
                            <label for="mensagem" class="form-label">Sua Mensagem</label>
                            <textarea class="form-control" id="mensagem" name="mensagem" rows="5" required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Enviar Mensagem</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-5 text-center">
                <h2>Outros Canais de Contato</h2>
                <p class="lead">Prefere falar diretamente conosco?</p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-envelope me-2"></i> E-mail: contato@niumi.com.br</li>
                    <li><i class="fas fa-phone me-2"></i> Telefone: +55 (11) 9999-9999</li>
                    <li><i class="fas fa-map-marker-alt me-2"></i> Endereço: São Paulo, SP - Brasil</li>
                </ul>
                <div class="social-icons mt-4">
                    <a href="#" class="btn btn-outline-secondary mx-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-outline-secondary mx-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-outline-secondary mx-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="btn btn-outline-secondary mx-2"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include_once 'templates/footer.php';
?>

