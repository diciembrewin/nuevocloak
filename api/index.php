<?php
// api/index.php - Redirige a index.html o global.html según el país
// Versión optimizada para PHP 8.5

// ============================================================
// 1. FUNCIÓN PARA OBTENER PAÍS POR IP
// ============================================================
function obtenerPaisPorIP() {
    // INTENTO 1: Usar headers de Vercel (más rápido y preciso)
    if (!empty($_SERVER['VERCEL_GEO_COUNTRY'])) {
        return [
            'codigo' => $_SERVER['VERCEL_GEO_COUNTRY'],
            'nombre' => $_SERVER['VERCEL_GEO_COUNTRY_NAME'] ?? 'Desconocido'
        ];
    }
    
    // Obtener IP real del usuario
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    
    // Si está detrás de proxy (Cloudflare, etc.)
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    
    $ip = trim($ip);
    
    // Si es IP local, intentar obtener IP pública
    if ($ip === '127.0.0.1' || $ip === '::1' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
        try {
            $respuesta = @file_get_contents('https://api.ipify.org');
            if ($respuesta) {
                $ip = trim($respuesta);
            }
        } catch (Exception $e) {
            // Si no se puede obtener IP pública, forzar Argentina para pruebas
            return ['codigo' => 'AR', 'nombre' => 'Argentina (modo pruebas)'];
        }
    }
    
    // Consultar API gratuita ipapi.co
    try {
        $url = "https://ipapi.co/{$ip}/json/";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $respuesta = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // ELIMINADO: curl_close($ch); - Ya no es necesario en PHP 8.5
        
        if ($httpCode === 200 && $respuesta) {
            $datos = json_decode($respuesta, true);
            if (!empty($datos['country_code'])) {
                return [
                    'codigo' => $datos['country_code'],
                    'nombre' => $datos['country_name'] ?? 'Desconocido'
                ];
            }
        }
    } catch (Exception $e) {
        // Si falla, continuar con el fallback
    }
    
    // Fallback con ipinfo.io
    try {
        $url = "https://ipinfo.io/{$ip}/json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $respuesta = curl_exec($ch);
        // ELIMINADO: curl_close($ch); - Ya no es necesario en PHP 8.5
        
        if ($respuesta) {
            $datos = json_decode($respuesta, true);
            if (!empty($datos['country'])) {
                return [
                    'codigo' => $datos['country'],
                    'nombre' => $datos['country_name'] ?? 'Desconocido'
                ];
            }
        }
    } catch (Exception $e) {
        // Si todo falla, usar el header de Vercel si existe
        if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
            return [
                'codigo' => $_SERVER['HTTP_CF_IPCOUNTRY'],
                'nombre' => 'Detectado por Cloudflare'
            ];
        }
    }
    
    // Último recurso: forzar Argentina para pruebas
    return ['codigo' => 'AR', 'nombre' => 'Argentina (modo seguro)'];
}

// ============================================================
// 2. REDIRIGIR SEGÚN PAÍS
// ============================================================

// Suprimir cualquier output antes de la redirección
ob_start(); // Iniciar buffer de salida

$pais = obtenerPaisPorIP();
$codigoPais = $pais['codigo'];

// Limpiar el buffer antes de enviar headers
ob_end_clean();

// REGLA: SOLO ARGENTINA → index.html, el resto → global.html
if ($codigoPais === 'AR') {
    // Redirigir a la versión en español
    header('Location: /index.html');
} else {
    // Redirigir a la versión en inglés
    header('Location: /global.html');
}
exit();
?>
