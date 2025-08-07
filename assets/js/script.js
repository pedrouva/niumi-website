// JavaScript principal para o site NiUMi

document.addEventListener('DOMContentLoaded', function() {
    
    // Inicialização de componentes
    initializeComponents();
    
    // Event listeners
    setupEventListeners();
    
    // Animações
    setupAnimations();
});

// Inicialização de componentes
function initializeComponents() {
    // Inicializar tooltips do Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Inicializar popovers do Bootstrap
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

// Configurar event listeners
function setupEventListeners() {
    // Smooth scroll para links âncora
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Formulário de newsletter
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', handleNewsletterSubmit);
    }
    
    // Busca com debounce
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Implementar busca em tempo real se necessário
                console.log('Busca:', this.value);
            }, 300);
        });
    }
    
    // Links de afiliado - tracking
    document.querySelectorAll('a[data-affiliate="true"]').forEach(link => {
        link.addEventListener('click', function() {
            // Tracking de cliques em links de afiliado
            trackAffiliateClick(this.href, this.dataset.productId);
        });
    });
}

// Configurar animações
function setupAnimations() {
    // Intersection Observer para animações de entrada
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observar elementos que devem ser animados
    document.querySelectorAll('.card, .category-card, .section-title').forEach(el => {
        observer.observe(el);
    });
}

// Manipular envio do formulário de newsletter
function handleNewsletterSubmit(e) {
    e.preventDefault();
    
    const email = e.target.querySelector('input[type="email"]').value;
    const button = e.target.querySelector('button');
    const originalText = button.innerHTML;
    
    // Validação básica
    if (!isValidEmail(email)) {
        showNotification('Por favor, insira um e-mail válido.', 'error');
        return;
    }
    
    // Mostrar loading
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    // Simular envio (implementar integração real)
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        e.target.reset();
        showNotification('Obrigado! Você foi inscrito na nossa newsletter.', 'success');
    }, 2000);
}

// Validar e-mail
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Mostrar notificações
function showNotification(message, type = 'info') {
    // Criar elemento de notificação
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Remover automaticamente após 5 segundos
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Tracking de cliques em links de afiliado
function trackAffiliateClick(url, productId) {
    // Implementar tracking analytics
    console.log('Clique em link de afiliado:', { url, productId });
    
    // Exemplo de integração com Google Analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'affiliate_click', {
            'product_id': productId,
            'affiliate_url': url
        });
    }
}

// Função para carregar mais produtos (paginação AJAX)
function loadMoreProducts(page, category = null) {
    const container = document.querySelector('#products-container');
    const loadButton = document.querySelector('#load-more-btn');
    
    if (!container || !loadButton) return;
    
    // Mostrar loading
    loadButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Carregando...';
    loadButton.disabled = true;
    
    // Construir URL
    let url = `ajax/load-products.php?page=${page}`;
    if (category) url += `&category=${category}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.products.length > 0) {
                // Adicionar produtos ao container
                container.insertAdjacentHTML('beforeend', data.html);
                
                // Atualizar botão
                if (data.hasMore) {
                    loadButton.innerHTML = 'Carregar Mais';
                    loadButton.disabled = false;
                    loadButton.dataset.page = page + 1;
                } else {
                    loadButton.style.display = 'none';
                }
                
                // Reobservar novos elementos para animação
                document.querySelectorAll('.card:not(.fade-in-up)').forEach(el => {
                    observer.observe(el);
                });
            } else {
                loadButton.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Erro ao carregar produtos:', error);
            loadButton.innerHTML = 'Erro ao carregar';
            loadButton.disabled = true;
        });
}

// Função para filtrar produtos
function filterProducts(filters) {
    const container = document.querySelector('#products-container');
    if (!container) return;
    
    // Mostrar loading
    container.innerHTML = '<div class="text-center"><div class="spinner"></div></div>';
    
    // Construir query string
    const queryString = new URLSearchParams(filters).toString();
    
    fetch(`ajax/filter-products.php?${queryString}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                container.innerHTML = data.html;
                
                // Reobservar elementos para animação
                document.querySelectorAll('.card').forEach(el => {
                    observer.observe(el);
                });
            } else {
                container.innerHTML = '<div class="text-center"><p>Nenhum produto encontrado.</p></div>';
            }
        })
        .catch(error => {
            console.error('Erro ao filtrar produtos:', error);
            container.innerHTML = '<div class="text-center"><p>Erro ao carregar produtos.</p></div>';
        });
}

// Função para compartilhar produto
function shareProduct(url, title) {
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        }).catch(console.error);
    } else {
        // Fallback para navegadores sem suporte
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Link copiado para a área de transferência!', 'success');
        }).catch(() => {
            showNotification('Não foi possível copiar o link.', 'error');
        });
    }
}

// Função para favoritar produto (se implementado)
function toggleFavorite(productId) {
    const button = document.querySelector(`[data-product-id="${productId}"]`);
    if (!button) return;
    
    const isFavorited = button.classList.contains('favorited');
    const action = isFavorited ? 'remove' : 'add';
    
    fetch('ajax/toggle-favorite.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            action: action
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.classList.toggle('favorited');
            const icon = button.querySelector('i');
            icon.className = isFavorited ? 'far fa-heart' : 'fas fa-heart';
            
            showNotification(
                isFavorited ? 'Produto removido dos favoritos' : 'Produto adicionado aos favoritos',
                'success'
            );
        }
    })
    .catch(error => {
        console.error('Erro ao favoritar produto:', error);
    });
}

// Utilitários
const Utils = {
    // Debounce function
    debounce: function(func, wait, immediate) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    },
    
    // Throttle function
    throttle: function(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },
    
    // Format currency
    formatCurrency: function(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    }
};

