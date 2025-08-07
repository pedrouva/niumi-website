    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5><i class="fas fa-cube me-2"></i>NiUMi</h5>
                    <p>Sua plataforma de confiança para descobrir os melhores produtos e serviços digitais. Conectamos você aos melhores infoprodutos, ferramentas digitais e soluções de IA do mercado.</p>
                    <div class="social-links">
                        <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Categorias</h5>
                    <ul class="list-unstyled">
                        <?php
                        // Buscar categorias para o footer
                        try {
                            $stmt = $pdo->query("SELECT nome, slug FROM categorias ORDER BY nome LIMIT 5");
                            while ($categoria = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<li><a href="' . SITE_URL . '/categoria.php?slug=' . $categoria['slug'] . '">' . htmlspecialchars($categoria['nome']) . '</a></li>';
                            }
                        } catch(PDOException $e) {
                            // Em caso de erro, exibir links estáticos
                            echo '<li><a href="' . SITE_URL . '/categoria.php?slug=cursos-online">Cursos Online</a></li>';
                            echo '<li><a href="' . SITE_URL . '/categoria.php?slug=inteligencia-artificial">Inteligência Artificial</a></li>';
                            echo '<li><a href="' . SITE_URL . '/categoria.php?slug=ferramentas-digitais">Ferramentas Digitais</a></li>';
                            echo '<li><a href="' . SITE_URL . '/categoria.php?slug=e-books">E-books</a></li>';
                        }
                        ?>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Links Úteis</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>">Início</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/sobre.php">Sobre Nós</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contato.php">Contato</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/politica-privacidade.php">Política de Privacidade</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/termos-uso.php">Termos de Uso</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>Contato</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i>contato@niumi.com</li>
                        <li><i class="fas fa-phone me-2"></i>+55 (11) 9999-9999</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>São Paulo, SP - Brasil</li>
                    </ul>
                    
                    <h6 class="mt-4">Newsletter</h6>
                    <p>Receba as novidades e lançamentos em primeira mão.</p>
                    <form class="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Seu e-mail" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="row">
                    <div class="col-md-6">
                        <p>&copy; <?php echo date('Y'); ?> NiUMi. Todos os direitos reservados.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p>Desenvolvido com <i class="fas fa-heart text-danger"></i> por Pedro Uva</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
    
    <!-- Scripts adicionais específicos da página -->
    <?php if (isset($additional_scripts)) echo $additional_scripts; ?>
</body>
</html>

