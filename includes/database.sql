-- Criação do banco de dados NiUMi
CREATE DATABASE IF NOT EXISTS niumi_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE niumi_db;

-- Tabela de usuários administradores
CREATE TABLE usuarios_admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    slug VARCHAR(150) UNIQUE NOT NULL,
    meta_titulo VARCHAR(200),
    meta_descricao TEXT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de parceiros
CREATE TABLE parceiros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150),
    descricao TEXT,
    redes_sociais JSON,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de produtos
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    descricao TEXT NOT NULL,
    imagem VARCHAR(255),
    video_youtube VARCHAR(255),
    link_afiliado VARCHAR(500) NOT NULL,
    categoria_id INT NOT NULL,
    parceiro_id INT NOT NULL,
    slug VARCHAR(250) UNIQUE NOT NULL,
    meta_titulo VARCHAR(200),
    meta_descricao TEXT,
    meta_keywords TEXT,
    ativo BOOLEAN DEFAULT TRUE,
    destaque BOOLEAN DEFAULT FALSE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE,
    FOREIGN KEY (parceiro_id) REFERENCES parceiros(id) ON DELETE CASCADE
);

-- Inserção de dados de exemplo
INSERT INTO usuarios_admin (nome, email, senha_hash) VALUES 
('Administrador', 'admin@niumi.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- senha: password

INSERT INTO categorias (nome, descricao, slug, meta_titulo, meta_descricao) VALUES 
('Cursos Online', 'Cursos e treinamentos digitais para desenvolvimento pessoal e profissional', 'cursos-online', 'Cursos Online - NiUMi', 'Descubra os melhores cursos online na NiUMi. Desenvolvimento pessoal, profissional e muito mais.'),
('Inteligência Artificial', 'Ferramentas e soluções baseadas em IA para otimizar processos e aumentar produtividade', 'inteligencia-artificial', 'Inteligência Artificial - NiUMi', 'Explore ferramentas de IA inovadoras na NiUMi. Soluções inteligentes para seu negócio.'),
('Ferramentas Digitais', 'Software e aplicativos para produtividade, design e gestão de negócios', 'ferramentas-digitais', 'Ferramentas Digitais - NiUMi', 'Encontre as melhores ferramentas digitais na NiUMi. Software e apps para produtividade.'),
('E-books', 'Livros digitais sobre diversos temas: negócios, desenvolvimento pessoal, tecnologia', 'e-books', 'E-books - NiUMi', 'Biblioteca digital com os melhores e-books. Conhecimento ao seu alcance na NiUMi.');

INSERT INTO parceiros (nome, email, descricao, redes_sociais) VALUES 
('TechEdu Academy', 'contato@techedu.com', 'Especialista em cursos de tecnologia e desenvolvimento', '{"instagram": "@techedu", "linkedin": "techedu-academy"}'),
('AI Solutions', 'hello@aisolutions.com', 'Desenvolvedora de ferramentas de inteligência artificial', '{"website": "aisolutions.com", "twitter": "@aisolutions"}'),
('Digital Tools Pro', 'info@digitaltoolspro.com', 'Criadora de software para produtividade empresarial', '{"linkedin": "digital-tools-pro"}');

INSERT INTO produtos (nome, descricao, imagem, video_youtube, link_afiliado, categoria_id, parceiro_id, slug, meta_titulo, meta_descricao, meta_keywords, destaque) VALUES 
('Curso Completo de Python', 'Aprenda Python do zero ao avançado com projetos práticos e certificado de conclusão. Mais de 40 horas de conteúdo.', 'python-course.jpg', 'dQw4w9WgXcQ', 'https://exemplo.com/python-course', 1, 1, 'curso-completo-python', 'Curso Completo de Python - NiUMi', 'Aprenda Python do zero ao avançado. Curso completo com projetos práticos e certificado.', 'python, programação, curso online, desenvolvimento', TRUE),
('ChatBot IA para Atendimento', 'Ferramenta de IA para automatizar atendimento ao cliente com respostas inteligentes e aprendizado contínuo.', 'chatbot-ai.jpg', '', 'https://exemplo.com/chatbot-ai', 2, 2, 'chatbot-ia-atendimento', 'ChatBot IA para Atendimento - NiUMi', 'Automatize seu atendimento com ChatBot IA. Respostas inteligentes e aprendizado contínuo.', 'chatbot, inteligência artificial, atendimento, automação', TRUE),
('Planilha de Gestão Financeira', 'Planilha completa para controle financeiro pessoal e empresarial com dashboards e relatórios automáticos.', 'planilha-financeira.jpg', '', 'https://exemplo.com/planilha-financeira', 3, 3, 'planilha-gestao-financeira', 'Planilha de Gestão Financeira - NiUMi', 'Controle suas finanças com planilha profissional. Dashboards e relatórios automáticos.', 'planilha, gestão financeira, controle, excel', FALSE),
('E-book: Marketing Digital 2024', 'Guia completo com as estratégias mais atuais de marketing digital, cases de sucesso e templates práticos.', 'ebook-marketing.jpg', '', 'https://exemplo.com/ebook-marketing', 4, 1, 'ebook-marketing-digital-2024', 'E-book Marketing Digital 2024 - NiUMi', 'Guia completo de marketing digital 2024. Estratégias atuais e cases de sucesso.', 'marketing digital, e-book, estratégias, 2024', TRUE);

