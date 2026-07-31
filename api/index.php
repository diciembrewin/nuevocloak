<?php
// api/index.php - Carga index.html o global.html según el país
// VERSIÓN SIN REDIRECCIONES (usa include)

function obtenerPaisPorIP() {
    // Usar headers de Vercel (más rápido y preciso)
    if (!empty($_SERVER['VERCEL_GEO_COUNTRY'])) {
        return [
            'codigo' => $_SERVER['VERCEL_GEO_COUNTRY'],
            'nombre' => $_SERVER['VERCEL_GEO_COUNTRY_NAME'] ?? 'Desconocido'
        ];
    }
    // Fallback: forzar Argentina para pruebas
    return ['codigo' => 'AR', 'nombre' => 'Argentina (modo pruebas)'];
}

$pais = obtenerPaisPorIP();
$codigoPais = $pais['codigo'];

// REGLA: SOLO ARGENTINA → index.html, el resto → global.html
if ($codigoPais === 'AR') {
    // Cargar index.html (español) SIN redireccionar
    include __DIR__ . '/../index.html';
} else {
    // Cargar global.html (inglés) SIN redireccionar
    include __DIR__ . '/../global.html';
}
?>
