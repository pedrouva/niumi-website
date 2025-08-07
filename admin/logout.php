<?php
require_once '../includes/config.php';

// Destruir a sessão
session_destroy();

// Redirecionar para a página de login
redirect('/admin/login.php');
?>

