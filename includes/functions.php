<?php

function generateSlug($string) {
    $string = strtolower($string);
    $string = preg_replace("/[^a-z0-9\s-]/", "", $string);
    $string = preg_replace("/\s+/", "-", $string);
    $string = trim($string, "-");
    return $string;
}

function isLoggedIn() {
    // Implementar lógica de verificação de login aqui
    // Por enquanto, retorna true para permitir o acesso ao painel
    return true;
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

?>

