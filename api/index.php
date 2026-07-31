<?php
// api/index.php - Carga index.html o global.html según el país

function obtenerPaisPorIP() {
    if (!empty($_SERVER['VERCEL_GEO_COUNTRY'])) {
        return [
            'codigo' => $_SERVER['VERCEL_GEO_COUNTRY'],
            'nombre' => $_SERVER['VERCEL_GEO_COUNTRY_NAME'] ?? 'Desconocido'
        ];
    }
    return ['codigo' => 'AR', 'nombre' => 'Argentina (modo pruebas)'];
}

$pais = obtenerPaisPorIP();
$codigoPais = $pais['codigo'];

// REGLA: SOLO ARGENTINA → index.html, el resto → global.html
if ($codigoPais === 'AR') {
    // Mostrar index.html (español) SOLO para Argentina
    include __DIR__ . '/../index.html';
} else {
    // Mostrar global.html (inglés) para el RESTO del mundo
    include __DIR__ . '/../global.html';
}
?>
