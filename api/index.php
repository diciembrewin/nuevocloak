<?php
// ============================================================
// REDIRECCIÓN POR IP - CON MÚLTIPLES APIs (MÁS ROBUSTA)
// ============================================================

$URL_ESPANOL = '/entra.html';
$URL_INGLES = '/blob.html';

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

// Lista de APIs para intentar
$apis = [
    [
        'url' => "https://ipinfo.io/{$ip}/json",
        'country_key' => 'country'
    ],
    [
        'url' => "https://ip-api.com/json/",
        'country_key' => 'countryCode'
    ],
    [
        'url' => "https://freegeoip.app/json/{$ip}",
        'country_key' => 'country_code'
    ]
];

$pais = null;

// Intentar cada API hasta que una funcione
foreach ($apis as $api) {
    $geo = @file_get_contents($api['url']);
    
    if ($geo !== false) {
        $data = json_decode($geo, true);
        if (isset($data[$api['country_key']])) {
            $pais = $data[$api['country_key']];
            break;
        }
    }
}

// Si no se pudo detectar el país, usar inglés por defecto
if ($pais === null) {
    header('Location: ' . $URL_INGLES, true, 302);
    exit;
}

// Redirigir según país
if ($pais === 'AR') {
    header('Location: ' . $URL_ESPANOL, true, 302);
} else {
    header('Location: ' . $URL_INGLES, true, 302);
}
exit;
?>
