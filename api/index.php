<?php
// api/index.php - Redirige según país usando headers de Vercel
// Versión ultra ligera y sin deprecations

// ============================================================
// 1. OBTENER PAÍS DESDE HEADERS DE VERCEL
// ============================================================
function obtenerPaisPorIP() {
    // Usar headers de Vercel (más rápido y sin llamadas externas)
    if (!empty($_SERVER['VERCEL_GEO_COUNTRY'])) {
        return [
            'codigo' => $_SERVER['VERCEL_GEO_COUNTRY'],
            'nombre' => $_SERVER['VERCEL_GEO_COUNTRY_NAME'] ?? 'Desconocido'
        ];
    }
    
    // Fallback: si no hay headers de Vercel, forzar Argentina para pruebas
    return ['codigo' => 'AR', 'nombre' => 'Argentina (modo desarrollo)'];
}

// ============================================================
// 2. REDIRIGIR SEGÚN PAÍS
// ============================================================
$pais = obtenerPaisPorIP();
$codigoPais = $pais['codigo'];

// REGLA: SOLO ARGENTINA → index.html, el resto → global.html
if ($codigoPais === 'AR') {
    header('Location: /index.html');
} else {
    header('Location: /global.html');
}
exit();
?>
