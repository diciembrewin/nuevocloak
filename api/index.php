<?php
// ============================================================
// REDIRECCIÓN POR IP - SOLO ARGENTINA VE ESPAÑOL
// ============================================================

// Configuración
$URL_ESPANOL = '/entra.html';
$URL_INGLES = '/blob.html';

// Obtener IP del visitante
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

$ip = getUserIP();

// Consultar API de geolocalización
$geo = file_get_contents("https://ipapi.co/{$ip}/json/");
$data = json_decode($geo, true);

// Redirigir según país
if ($data['country'] === 'AR') {
    header('Location: ' . $URL_ESPANOL, true, 302);
} else {
    header('Location: ' . $URL_INGLES, true, 302);
}
exit;
?>
