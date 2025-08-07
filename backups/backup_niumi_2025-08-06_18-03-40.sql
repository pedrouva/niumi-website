/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.11-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: niumi_db
-- ------------------------------------------------------
-- Server version	10.11.11-MariaDB-ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `data_criacao` timestamp NULL DEFAULT current_timestamp(),
  `ultimo_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES
(1,'Administrador','admin@niumi.com','$2y$10$gXEDD9np6PeB24Wq4JqPNOQs3xFoPcKTYiwrIm6hIs3w/HaU0ESvW',1,'2025-08-02 14:17:00',NULL);
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `slug` varchar(150) NOT NULL,
  `meta_titulo` varchar(200) DEFAULT NULL,
  `meta_descricao` text DEFAULT NULL,
  `data_criacao` timestamp NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` text DEFAULT NULL,
  `seo_canonical` varchar(500) DEFAULT NULL,
  `seo_og_title` varchar(255) DEFAULT NULL,
  `seo_og_description` text DEFAULT NULL,
  `seo_og_image` varchar(500) DEFAULT NULL,
  `seo_schema_data` text DEFAULT NULL,
  `seo_focus_keyword` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES
(2,'Inteligência Artificial','Ferramentas e soluções baseadas em IA para otimizar processos e aumentar produtividade','inteligencia-artificial','Inteligência Artificial - NiUMi','Explore ferramentas de IA inovadoras na NiUMi. Soluções inteligentes para seu negócio.','2025-08-02 14:17:00','2025-08-02 14:17:00',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(10,'Cursos Online','Explore uma vasta seleção de cursos online para aprimorar suas habilidades, iniciar uma nova carreira ou expandir seus conhecimentos.','cursos-online',NULL,NULL,'2025-08-04 15:30:26','2025-08-04 15:30:26','','','','','','','','','');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracoes`
--

DROP TABLE IF EXISTS `configuracoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chave` varchar(100) NOT NULL,
  `valor` text DEFAULT NULL,
  `data_criacao` timestamp NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_chave` (`chave`)
) ENGINE=InnoDB AUTO_INCREMENT=306 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracoes`
--

LOCK TABLES `configuracoes` WRITE;
/*!40000 ALTER TABLE `configuracoes` DISABLE KEYS */;
INSERT INTO `configuracoes` VALUES
(1,'site_nome','NiUMi','2025-08-03 16:38:03','2025-08-03 16:38:03'),
(2,'site_descricao','Plataforma de Produtos Digitais','2025-08-03 16:38:03','2025-08-03 22:48:50'),
(3,'site_email','contato@niumi.com.br','2025-08-03 16:38:03','2025-08-03 16:38:03'),
(4,'site_telefone','','2025-08-03 16:38:03','2025-08-03 16:38:03'),
(5,'site_endereco','ewfewfgwe','2025-08-03 16:38:03','2025-08-06 19:58:36'),
(6,'meta_keywords','','2025-08-03 16:38:03','2025-08-03 16:38:03'),
(7,'meta_description','Plataforma de Produtos Digitais','2025-08-03 16:38:03','2025-08-03 22:48:50'),
(8,'google_analytics','','2025-08-03 16:38:03','2025-08-03 16:38:03'),
(9,'facebook_pixel','','2025-08-03 16:38:03','2025-08-03 16:38:03'),
(10,'manutencao','0','2025-08-03 16:38:03','2025-08-03 18:05:23'),
(71,'meta_title','NiUMi','2025-08-03 18:17:46','2025-08-03 22:48:50'),
(72,'meta_author','NiUMi','2025-08-03 18:17:46','2025-08-03 22:51:49'),
(73,'meta_robots','index, follow','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(74,'canonical_url','https://niumi.com.br/','2025-08-03 18:17:46','2025-08-03 22:51:30'),
(75,'og_title','','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(76,'og_description','','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(77,'og_image','','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(78,'og_type','website','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(79,'twitter_card','summary_large_image','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(80,'twitter_site','','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(81,'schema_org_type','Organization','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(82,'schema_org_data','','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(83,'sitemap_url','','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(84,'robots_txt','','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(85,'google_site_verification','','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(86,'bing_site_verification','','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(87,'google_tag_manager','','2025-08-03 18:17:46','2025-08-03 18:17:46'),
(250,'site_logo','','2025-08-03 23:38:56','2025-08-03 23:39:04');
/*!40000 ALTER TABLE `configuracoes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parceiros`
--

DROP TABLE IF EXISTS `parceiros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `parceiros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `redes_sociais` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`redes_sociais`)),
  `data_criacao` timestamp NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ativo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parceiros`
--

LOCK TABLES `parceiros` WRITE;
/*!40000 ALTER TABLE `parceiros` DISABLE KEYS */;
INSERT INTO `parceiros` VALUES
(6,'Manus Im',NULL,'Cansado de perder horas em tarefas repetitivas, sentindo que o potencial do seu negócio está estagnado? Já imaginou ter uma equipe de especialistas trabalhando incansavelmente para otimizar cada detalhe da sua empresa, 24 horas por dia, 7 dias por semana?\r\n\r\nPare de sonhar e comece a agir. O futuro da sua produtividade está aqui, e ele se chama Manus AI.',NULL,'2025-08-03 23:59:32','2025-08-03 23:59:32',1),
(8,'Cambly',NULL,'Cansado de sentir que seu inglês não avança, preso a métodos tradicionais ou com medo de falar? Imagine destravar sua fluência com conversas reais, a qualquer hora e em qualquer lugar, com falantes nativos que te guiam sem julgamento, adaptando cada aula aos seus objetivos, seja para uma promoção, uma viagem dos sonhos ou simplesmente para se comunicar com confiança. A Cambly oferece essa liberdade e progresso tangível, permitindo que você construa sua equipe de tutores ideais e veja seu crescimento em tempo real, transformando o aprendizado em uma jornada empolgante e eficaz. Não perca a chance de experimentar essa revolução no aprendizado de inglês e abrir as portas para um mundo de novas oportunidades.',NULL,'2025-08-04 19:08:11','2025-08-04 19:31:18',1);
/*!40000 ALTER TABLE `parceiros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `descricao` text NOT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `imagem_url` varchar(500) DEFAULT NULL,
  `video_youtube` varchar(255) DEFAULT NULL,
  `link_afiliado` varchar(500) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `parceiro_id` int(11) DEFAULT NULL,
  `slug` varchar(250) NOT NULL,
  `meta_titulo` varchar(200) DEFAULT NULL,
  `meta_descricao` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `destaque` tinyint(1) DEFAULT 0,
  `data_criacao` timestamp NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` text DEFAULT NULL,
  `seo_canonical` varchar(500) DEFAULT NULL,
  `seo_og_title` varchar(255) DEFAULT NULL,
  `seo_og_description` text DEFAULT NULL,
  `seo_og_image` varchar(500) DEFAULT NULL,
  `seo_schema_data` text DEFAULT NULL,
  `seo_focus_keyword` varchar(255) DEFAULT NULL,
  `seo_alt_text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `categoria_id` (`categoria_id`),
  KEY `parceiro_id` (`parceiro_id`),
  CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produtos_ibfk_2` FOREIGN KEY (`parceiro_id`) REFERENCES `parceiros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
INSERT INTO `produtos` VALUES
(7,'Descubra o Segredo para Impulsionar Seus Resultados: Chegou a Hora de Conhecer o Manus AI','Cansado de perder horas em tarefas repetitivas, sentindo que o potencial do seu negócio está estagnado? Já imaginou ter uma equipe de especialistas trabalhando incansavelmente para otimizar cada detalhe da sua empresa, 24 horas por dia, 7 dias por semana?\r\n\r\nPare de sonhar e comece a agir. O futuro da sua produtividade está aqui, e ele se chama Manus AI.\r\n\r\nA Mão Amiga que Faltava para Seu Negócio\r\nO Manus AI é muito mais que uma simples ferramenta de inteligência artificial; é uma extensão da sua equipe, uma \"mão amiga\" — como sugere seu nome em latim. Desenvolvida pela visionária startup chinesa Butterfly Effect, esta plataforma foi criada para ser a sua maior aliada.\r\n\r\nEla opera com uma capacidade impressionante de aprender e evoluir com cada nova informação que você fornece. Isso significa que, quanto mais você a usa, mais inteligente e adaptada ela se torna, entregando insights cada vez mais precisos e soluções cada vez mais eficientes. O Manus AI não apenas executa tarefas; ele aprende com você para te ajudar a vencer.\r\n\r\nPor que o Manus AI é Indispensável para Você?\r\nImagine poder focar no que realmente importa, na estratégia, na criatividade, na conexão com seus clientes, enquanto o Manus AI cuida da parte operacional. Ele faz isso de forma impecável, liberando você e sua equipe para atingirem novos patamares de sucesso.\r\n\r\nAqui estão alguns dos benefícios que a Manus AI irá destrancar para você:\r\n\r\nAutomação Estratégica: Esqueça as horas perdidas processando dados e gerando relatórios. O Manus AI automatiza essas tarefas, permitindo que seu foco se volte para o crescimento do seu negócio.\r\n\r\nAnálise de Dados Inteligente: Tomar decisões baseadas em intuição é coisa do passado. Com a Manus AI, você terá acesso a insights rápidos e precisos, transformando grandes volumes de dados em ações estratégicas.\r\n\r\nOtimização Total: Identifique gargalos e melhore processos em tempo real. O Manus AI funciona como um verdadeiro consultor, sugerindo melhorias para aumentar a eficiência e reduzir custos.\r\n\r\nMarketing de Alta Performance: Seus concorrentes já estão usando. O Manus AI otimiza suas campanhas de marketing, identificando os melhores canais e segmentando seu público-alvo com uma precisão cirúrgica. Prepare-se para ver seu ROI disparar.\r\n\r\nEscalabilidade sem Limites: Não importa o tamanho da sua empresa, o Manus AI cresce com você. Sua capacidade de se adaptar a grandes volumes de dados faz dele a escolha perfeita para negócios que buscam um crescimento acelerado e sustentável.\r\n\r\nAcessar o Futuro é Mais Fácil do que Você Pensa\r\nO acesso ao Manus AI é um privilégio, e não uma ferramenta massificada. Atualmente, a plataforma está em uma fase de pré-lançamento, com vagas limitadas para um grupo seleto de usuários. Isso significa que, ao fazer parte desse grupo, você terá uma vantagem competitiva inestimável.\r\n\r\nAproveite a oportunidade e seja um dos primeiros a explorar o poder do Manus AI. O processo é simples:\r\n\r\nVisite o site oficial para entender todos os detalhes da plataforma.\r\n\r\nInscreva-se na lista de espera: Ao fazer isso, você garante seu lugar na fila para receber um convite exclusivo.\r\n\r\nAguarde seu convite: Quando sua vaga for liberada, você receberá um e-mail com acesso.\r\n\r\nComece a usar: Faça login e inicie sua jornada rumo a uma eficiência e produtividade inigualáveis.\r\n\r\nO tempo para revolucionar a forma como você trabalha é agora. Não fique para trás. A fila está crescendo, e cada minuto de hesitação é uma oportunidade de crescimento que você está perdendo para a concorrência.\r\n\r\nLiberte-se da rotina e abrace a inteligência que o Manus AI oferece.\r\n\r\nDescubra o Poder do Manus AI e Transforme Seus Resultados\r\nA seguir, apresentamos a você um comparativo de como o Manus AI se destaca no mercado, oferecendo uma solução robusta, acessível e intuitiva, ideal para quem busca resultados práticos e rápidos.\r\n\r\nManus AI vs. DeepSeek\r\n\r\nCaracterística	Manus AI	DeepSeek\r\nUsabilidade	Extremamente intuitiva. Perfeita para quem não tem conhecimento técnico avançado.	Mais técnica e robusta. Exige um nível maior de conhecimento em IA.\r\nCusto	Flexível e acessível. Inclui uma versão gratuita para você testar sem compromisso.	Custo mais elevado. Voltado para grandes corporações com orçamentos maiores.\r\nFoco	Otimização de processos, automação prática e melhoria em marketing digital para todos os portes de empresa.	Análise de dados aprofundada e complexa, voltada para grandes empresas e pesquisa científica.\r\n\r\nExportar para as Planilhas\r\nSe a sua meta é acelerar o crescimento do seu negócio de forma prática e inteligente, o Manus AI foi feito para você.\r\n\r\nSeja um dos primeiros a dominar essa tecnologia. O futuro está em suas mãos. Ou melhor, nas mãos da Manus AI.\r\n\r\nNão espere o amanhã. O sucesso se constrói hoje.\r\nSe você está buscando uma plataforma que fala a sua língua, tanto no sentido literal (sim, o Manus AI tem suporte completo em português!) quanto no sentido de entender as suas necessidades e objetivos, sua busca acabou.\r\n\r\nAja agora. Clique no link abaixo, inscreva-se na lista de espera e prepare-se para ver seu negócio alcançar níveis que você nunca imaginou.\r\n\r\nO poder da inteligência artificial está a um passo de você.',100.00,NULL,'https://files.manuscdn.com/webapp/media/ogBanner.png','https://www.youtube.com/watch?v=0IcPkF0GzCM','https://manus.im/invitation/ZAT8HO6IVHRY',2,6,'descubra-o-segredo-para-impulsionar-seus-resultados-chegou-a-hora-de-conhecer-o-manus-ai',NULL,NULL,NULL,1,1,'2025-08-03 23:51:06','2025-08-04 15:33:58','Manus AI: Revolucione Sua Produtividade com IA | Guia Comple','Descubra o Manus AI, a plataforma de inteligência artificial que automatiza tarefas, otimiza processos e impulsiona seu negócio. Inscreva-se!','Inteligência Artificial, Automação, Análise de Dados, Otimização de Processos, Marketing Digital, Produtividade, Ferramenta de IA, Butterfly Effect, Inovação, Tecnologia, Startup Chinesa, Manus AI em Português','https://niumi.com.br/descubra-o-segredo-para-impulsionar-seus-resultados-chegou-a-hora-de-conhecer-o-manus-ai','Manus AI: A Plataforma de IA que Vai Transformar Seu Negócio','O Manus AI é a ferramenta de inteligência artificial que automatiza processos, otimiza dados e potencializa a produtividade da sua empresa. Conheça a plataforma!','https://files.manuscdn.com/webapp/media/ogBanner.png','{\r\n  \"@context\": \"https://schema.org\",\r\n  \"@type\": \"Article\",\r\n  \"mainEntityOfPage\": {\r\n    \"@type\": \"WebPage\",\r\n    \"@id\": \"https://seusite.com.br/manus-ai\"\r\n  },\r\n  \"headline\": \"Manus AI: Revolucione Sua Produtividade com IA | Guia Completo\",\r\n  \"image\": [\r\n    \"https://seusite.com.br/imagens/manus-ai-open-graph.jpg\"\r\n  ],\r\n  \"datePublished\": \"2025-08-03T20:45:40-03:00\",\r\n  \"dateModified\": \"2025-08-03T20:45:40-03:00\",\r\n  \"author\": {\r\n    \"@type\": \"Person\",\r\n    \"name\": \"Seu Nome ou Nome da Empresa\"\r\n  },\r\n  \"publisher\": {\r\n    \"@type\": \"Organization\",\r\n    \"name\": \"Seu Nome ou Nome da Empresa\",\r\n    \"logo\": {\r\n      \"@type\": \"ImageObject\",\r\n      \"url\": \"https://seusite.com.br/logo.png\"\r\n    }\r\n  },\r\n  \"description\": \"Descubra o Manus AI, a plataforma de inteligência artificial que automatiza tarefas, otimiza processos e impulsiona seu negócio. Inscreva-se para ter acesso exclusivo!\",\r\n  \"keywords\": \"Manus AI, Inteligência Artificial, Automação, Análise de Dados, Otimização de Processos, Marketing Digital, Produtividade, Ferramenta de IA, Butterfly Effect\"\r\n}','Manus AI','Manus AI: Revolucione Sua Produtividade com IA | Guia Comple'),
(13,' Cambly - Aulas de Inglês Online com Nativos','A Cambly é uma plataforma online inovadora que conecta alunos de inglês de todos os níveis com tutores falantes nativos de inglês para prática de conversação personalizada em tempo real, disponível 24 horas por dia, 7 dias por semana. Ela é projetada para facilitar o progresso genuíno e a confiança na comunicação em inglês. Os alunos podem acessar aulas de alta qualidade a qualquer hora, em qualquer lugar, via desktop ou pelo aplicativo móvel intuitivo, encaixando-se perfeitamente até mesmo nas agendas mais ocupadas. Essa flexibilidade incomparável remove as barreiras tradicionais ao aprendizado de idiomas.   \r\n\r\nA jornada de aprendizado é personalizada: os usuários iniciam sua jornada definindo seus objetivos específicos de aprendizado de inglês, e a Cambly recomenda de forma inteligente aulas, tópicos e atividades sob medida. Os alunos podem escolher entre uma vasta e diversificada biblioteca de cursos estruturados que abrangem inglês geral, inglês para negócios especializado, preparação abrangente para exames (por exemplo, IELTS, TOEFL) e desenvolvimento de habilidades direcionadas em pronúncia, gramática e vocabulário. A plataforma capacita os usuários a selecionar tutores com base em uma ampla gama de critérios, incluindo interesses, sotaques, estilos de ensino e formações profissionais, promovendo um ambiente de aprendizado confortável, envolvente e altamente eficaz.   \r\n\r\nA Cambly ajuda os usuários a monitorar seu crescimento e reforçar o aprendizado por meio do aprimoramento de habilidades durante as aulas e atividades personalizadas pós-aula. Isso permite que os alunos acompanhem visualmente sua melhoria ao longo do tempo, fornecendo prova tangível de seu progresso. A plataforma utiliza tecnologias avançadas de IA para aprimorar ainda mais a experiência de aprendizado, incluindo chatbots inteligentes para prática, análise detalhada de progresso e informações personalizadas, garantindo uma abordagem moderna, adaptativa e altamente eficaz para a aquisição de idiomas.   ',30.00,NULL,'https://logosbr.com.br/wp-content/uploads/2021/10/cambly-para-iniciantes.jpg','https://www.youtube.com/watch?v=JCWju3zEPoo','https://www.cambly.com/invite/6SB3EC2R?st=080425&sc=4',10,8,'cambly-aulas-de-ingls-online-com-nativos',NULL,NULL,NULL,1,1,'2025-08-04 19:25:51','2025-08-06 19:58:10','Cambly: Aprenda Inglês com Nativos 24/7. Progresso Real.','Transforme seu inglês com Cambly! Aulas personalizadas 24/7 com tutores nativos. Faça progresso real e abra novas portas. Comece hoje com descontos exclusivos!','curso de inglês online, aulas de conversação, professores nativos, inglês para negócios, preparação IELTS, fluência em inglês, inglês para viagens, aulas de pronúncia, gramática inglesa, inglês flexível, aprender inglês rápido, aulas de inglês com sotaque nativo','https://niumi.com.br/produto.php?slug=cambly-aulas-de-ingls-online-com-nativos','Cambly: Aprenda Inglês Online com Tutores Nativos e Descontos Exclusivos.','Transforme seu inglês com conversas reais 24/7. Tutores nativos, aulas personalizadas e progresso visível. Comece sua jornada para a fluência hoje!','https://logosbr.com.br/wp-content/uploads/2021/10/cambly-para-iniciantes.jpg','{\r\n  \"@context\": \"https://schema.org\",\r\n  \"@type\": \"Organization\",\r\n  \"name\": \"Cambly\",\r\n  \"url\": \"https://www.cambly.com/\",\r\n  \"logo\": \"https://logosbr.com.br/wp-content/uploads/2021/10/cambly-para-iniciantes.jpg\",\r\n  \"sameAs\": [\r\n    \"https://www.instagram.com/cambly_global/\",\r\n    \"https://www.facebook.com/camblyapp\",\r\n    \"https://www.youtube.com/user/CamblyEnglish\",\r\n    \"https://twitter.com/cambly\"\r\n  ],\r\n  \"description\": \"Plataforma líder para aprender inglês online com tutores nativos a qualquer hora, em qualquer lugar, focada em progresso real e conversação.\"\r\n}','Aulas de Inglês Online','Logo oficial da Cambly, plataforma líder de aulas de inglês online com tutores nativos e acesso 24/7.');
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios_admin`
--

DROP TABLE IF EXISTS `usuarios_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `data_criacao` timestamp NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios_admin`
--

LOCK TABLES `usuarios_admin` WRITE;
/*!40000 ALTER TABLE `usuarios_admin` DISABLE KEYS */;
INSERT INTO `usuarios_admin` VALUES
(1,'Administrador','admin@niumi.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','2025-08-02 14:17:00','2025-08-02 14:17:00');
/*!40000 ALTER TABLE `usuarios_admin` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-06 18:03:40
