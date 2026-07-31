<?php
// api/index.php - Geobloqueo: Argentina = Español, resto = Inglés
// Versión optimizada SIN redirecciones

// ============================================================
// 1. FUNCIÓN PARA OBTENER PAÍS POR IP
// ============================================================
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

// ============================================================
// 2. DETECTAR PAÍS Y DECIDIR IDIOMA
// ============================================================
$pais = obtenerPaisPorIP();
$codigoPais = $pais['codigo'];
$nombrePais = $pais['nombre'];

// REGLA: SOLO ARGENTINA ve español, el resto inglés
$mostrarEspanol = ($codigoPais === 'AR');
$idioma = $mostrarEspanol ? 'es' : 'en';

// Bandera
function obtenerBandera($codigo) {
    $banderas = [
        'AR' => '🇦🇷', 'ES' => '🇪🇸', 'MX' => '🇲🇽', 'CO' => '🇨🇴',
        'CL' => '🇨🇱', 'PE' => '🇵🇪', 'VE' => '🇻🇪', 'EC' => '🇪🇨',
        'US' => '🇺🇸', 'GB' => '🇬🇧', 'CA' => '🇨🇦', 'DE' => '🇩🇪',
        'FR' => '🇫🇷', 'IT' => '🇮🇹', 'BR' => '🇧🇷', 'AU' => '🇦🇺',
        'JP' => '🇯🇵', 'CN' => '🇨🇳', 'RU' => '🇷🇺', 'IN' => '🇮🇳'
    ];
    return $banderas[$codigo] ?? '🌍';
}
$bandera = obtenerBandera($codigoPais);
?>
<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $mostrarEspanol ? '🇦🇷 Contenido en español' : '🌍 English content'; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            max-width: 800px;
            width: 100%;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .badge {
            display: inline-block;
            background: #1e293b;
            color: white;
            padding: 6px 18px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }
        h1 { font-size: 2rem; margin-bottom: 0.5rem; color: #0f172a; }
        .subtitulo {
            color: #475569;
            margin-bottom: 25px;
            font-size: 1.1rem;
            border-left: 4px solid #3b82f6;
            padding-left: 15px;
        }
        .contenido-idioma {
            padding: 1.5rem;
            border-radius: 12px;
            margin: 20px 0;
            line-height: 1.7;
        }
        .espanol { background: #ecfdf5; border-left: 6px solid #10b981; }
        .ingles { background: #eff6ff; border-left: 6px solid #3b82f6; }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 10px;
            text-decoration: none;
        }
        .btn-verde { background: #10b981; color: white; }
        .btn-verde:hover { transform: scale(1.03); box-shadow: 0 4px 12px rgba(16,185,129,0.4); }
        .btn-azul { background: #3b82f6; color: white; }
        .btn-azul:hover { transform: scale(1.03); box-shadow: 0 4px 12px rgba(59,130,246,0.4); }
        .aviso-legal {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            font-size: 0.85rem;
            color: #64748b;
            text-align: center;
        }
        .pais-detectado {
            display: inline-block;
            background: #f1f5f9;
            padding: 4px 16px;
            border-radius: 30px;
            font-size: 0.9rem;
            color: #1e293b;
            margin-bottom: 15px;
        }
        ul { padding-left: 20px; margin: 10px 0; }
        ul li { margin: 6px 0; }
        .debug {
            margin-top: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 0.75rem;
            color: #6c757d;
            border: 1px solid #dee2e6;
        }
        .destacado {
            background: #fef9e7;
            border: 2px solid #f39c12;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="badge">⚖️ Licencias territoriales</div>

    <h1><?php echo $mostrarEspanol ? '📺 Contenido exclusivo para Argentina' : '📺 Exclusive content'; ?></h1>
    <div class="subtitulo">
        <?php echo $mostrarEspanol ? 'Versión en español' : 'English version'; ?>
    </div>

    <div class="pais-detectado">
        📍 <?php echo $bandera; ?> 
        <?php echo $mostrarEspanol ? 'País detectado:' : 'Country detected:'; ?> 
        <strong><?php echo htmlspecialchars($nombrePais); ?></strong> 
        (<?php echo htmlspecialchars($codigoPais); ?>)
    </div>

    <div id="contenido-dinamico">
        <?php if ($mostrarEspanol): ?>
            <div class="destacado">
                🇦🇷 ¡Bienvenido a la versión exclusiva para Argentina!
            </div>
            <div class="contenido-idioma espanol">
                <h2 style="color: #065f46; margin-bottom: 10px;">🇦🇷 Versión en español</h2>
                <p><strong>¡Hola, <?php echo htmlspecialchars($nombrePais); ?>!</strong></p>
                <p>Disfruta de nuestra serie original <strong>"El Viaje de los Andes"</strong> completamente en español.</p>
                <ul>
                    <li>🎬 8 episodios en 4K</li>
                    <li>🎧 Audio en español (castellano rioplatense)</li>
                    <li>📝 Subtítulos en español</li>
                    <li>📅 Estreno exclusivo para Argentina</li>
                </ul>
                <a href="#" class="btn btn-verde">▶️ Ver ahora en español</a>
                <p style="margin-top: 12px; font-size: 0.9rem; color: #065f46;">
                    ✅ Contenido disponible por licencia en Argentina.
                </p>
            </div>

        <?php else: ?>
            <div class="destacado" style="background: #eaf2f8; border-color: #2980b9;">
                🌎 Welcome to the international version
            </div>
            <div class="contenido-idioma ingles">
                <h2 style="color: #1e3a8a; margin-bottom: 10px;">🇬🇧 English version</h2>
                <p><strong>Welcome, <?php echo htmlspecialchars($nombrePais); ?>!</strong></p>
                <p>Enjoy our international series <strong>"The Andes Journey"</strong> in English.</p>
                <ul>
                    <li>🎬 8 episodes in 4K</li>
                    <li>🎧 English audio (original version)</li>
                    <li>📝 English subtitles</li>
                    <li>📅 Available worldwide</li>
                </ul>
                <a href="#" class="btn btn-azul">▶️ Watch now in English</a>
                <p style="margin-top: 12px; font-size: 0.9rem; color: #1e3a8a;">
                    ✅ Content licensed for your region.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <div class="aviso-legal">
        <?php echo $mostrarEspanol 
            ? '⚠️ Este contenido está restringido por licencias. Solo disponible en Argentina.'
            : '⚠️ This content is restricted by licensing. Only available in Argentina in Spanish.'; ?>
        <br />
        <span style="font-size: 0.75rem; color: #94a3b8;">
            IP detectada: <?php echo htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'Desconocida'); ?>
        </span>
    </div>

    <div class="debug">
        <strong>🔍 Debug:</strong><br />
        Código país: <?php echo htmlspecialchars($codigoPais); ?><br />
        Nombre país: <?php echo htmlspecialchars($nombrePais); ?><br />
        Idioma: <?php echo $idioma; ?><br />
        ¿Español?: <?php echo $mostrarEspanol ? '✅ Sí' : '❌ No'; ?><br />
        <?php if (!empty($_SERVER['VERCEL_GEO_COUNTRY'])): ?>
            Vercel GEO: <?php echo htmlspecialchars($_SERVER['VERCEL_GEO_COUNTRY']); ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
