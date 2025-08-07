<?php
require_once __DIR__ . 
'/includes/config.php';
require_once __DIR__ . 
'/includes/seo_functions.php';

generateSitemap($pdo);
generateRobotsTxt();

echo "Sitemap e robots.txt gerados com sucesso!\n";

?>

