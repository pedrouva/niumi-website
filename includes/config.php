<?php
// Configurações do banco de dados
define("DB_HOST", "localhost");
define("DB_NAME", "niumi_db");
define("DB_USER", "niumi_db");
define("DB_PASS", "E}6nTRZNu;mLdI)r");

// Configurações gerais do site
define("SITE_NAME", "NiUMi");
define("SITE_URL", "https://niumi.com.br");
define("SITE_DESCRIPTION", "Marketplace de Produtos e Serviços Digitais");

// Configurações de upload
define("UPLOAD_PATH", "assets/images/uploads/");
define("MAX_FILE_SIZE", 5242880); // 5MB

// Configurações de paginação
define("ITEMS_PER_PAGE", 12);

// Configurações de sessão
session_start();

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Função para sanitizar dados
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Função para gerar slug
function generateSlug($string) {
    $string = strtolower($string);
    $string = preg_replace("/[^a-z0-9\s-]/", "", $string);
    $string = preg_replace("/[\s-]+/", "-", $string);
    return trim($string, "-");
}

// Função para verificar se o usuário está logado
function isLoggedIn() {
    return isset($_SESSION["admin_id"]);
}

// Função para redirecionar
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Função para verificar modo manutenção
function checkMaintenanceMode() {
    global $pdo;
    
    // Não verificar manutenção para páginas admin ou se não for um ambiente web
    $current_path = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "";
    if (strpos($current_path, 
"/admin/") !== false || strpos($current_path, "maintenance.php") !== false) {
        return false;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT valor FROM configuracoes WHERE chave = \'manutencao\'");
        $stmt->execute();
        $result = $stmt->fetchColumn();
        
        if ($result === "1") {
            header("Location: /maintenance.php");
            exit();
        }
    } catch(PDOException $e) {
        // Se houver erro na consulta, não ativar manutenção
        return false;
    }
    
    return false;
}

// Verificar modo manutenção automaticamente
checkMaintenanceMode();
?>



