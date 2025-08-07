    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Admin JavaScript -->
    <script>
        // Confirmar exclusões
        function confirmDelete(message = 'Tem certeza que deseja excluir este item?') {
            return confirm(message);
        }
        
        // Mostrar/ocultar loading
        function showLoading(button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processando...';
            button.disabled = true;
            
            return function() {
                button.innerHTML = originalText;
                button.disabled = false;
            };
        }
        
        // Auto-gerar slug
        function generateSlug(text) {
            return text
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
        }
        
        // Auto-completar slug baseado no nome
        document.addEventListener('DOMContentLoaded', function() {
            const nomeInput = document.querySelector('input[name="nome"]');
            const slugInput = document.querySelector('input[name="slug"]');
            
            if (nomeInput && slugInput) {
                nomeInput.addEventListener('input', function() {
                    if (!slugInput.dataset.manual) {
                        slugInput.value = generateSlug(this.value);
                    }
                });
                
                slugInput.addEventListener('input', function() {
                    this.dataset.manual = 'true';
                });
            }
            
            // Validação de formulários
            const forms = document.querySelectorAll('.needs-validation');
            forms.forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            });
            
            // Tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
        
        // Função para preview de imagem
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).src = e.target.result;
                    document.getElementById(previewId).style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Função para copiar texto
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Mostrar feedback visual
                const toast = document.createElement('div');
                toast.className = 'toast position-fixed top-0 end-0 m-3';
                toast.innerHTML = `
                    <div class="toast-header">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong class="me-auto">Sucesso</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        Texto copiado para a área de transferência!
                    </div>
                `;
                document.body.appendChild(toast);
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
                
                setTimeout(() => {
                    toast.remove();
                }, 5000);
            });
        }
        
        // Função para filtrar tabelas
        function filterTable(inputId, tableId) {
            const input = document.getElementById(inputId);
            const table = document.getElementById(tableId);
            const rows = table.getElementsByTagName('tr');
            
            input.addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                
                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    let found = false;
                    
                    for (let j = 0; j < cells.length; j++) {
                        if (cells[j].textContent.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                    
                    row.style.display = found ? '' : 'none';
                }
            });
        }
    </script>
    
    <!-- Scripts específicos da página -->
    <?php if (isset($additional_scripts)) echo $additional_scripts; ?>
</body>
</html>

