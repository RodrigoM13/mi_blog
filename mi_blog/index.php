<?php
require_once 'datos.php';

$descripciones_habilidades = [
    'redes y configuracion basica' => [
        'texto'   => 'Comprensión de conexiones, direccionamiento y diagnóstico de red.',
        'enfoque' => 'Infraestructura y conectividad'
    ],
    'programacion' => [
        'texto'   => 'Desarrollo de lógica, scripts y soluciones estructuradas.',
        'enfoque' => 'Lógica y código'
    ],
    'resolucion de problemas' => [
        'texto'   => 'Análisis paso a paso para identificar y corregir fallos.',
        'enfoque' => 'Pensamiento analítico'
    ],
    'analisis de datos' => [
        'texto'   => 'Limpieza, exploración e interpretación de datos para encontrar patrones y apoyar decisiones.',
        'enfoque' => 'Datos y métricas'
    ]
];

$descripciones_intereses = [
    'tecnologia' => [
        'texto'   => 'Exploración de herramientas digitales, sistemas y nuevos entornos.',
        'enfoque' => 'Aprendizaje continuo'
    ],
    'deportes' => [
        'texto'   => 'Trabajo en equipo, constancia y estrategia (como el básquet).',
        'enfoque' => 'Dinámica y equipo'
    ]
];

function normalizar_clave(string $texto): string {
    $minuscula = mb_strtolower(trim($texto), 'UTF-8');
    $sin_acentos = strtr($minuscula, [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        'ü' => 'u', 'ñ' => 'n'
    ]);
    return $sin_acentos;
}

function obtener_info_item(string $item, array $catalogo, string $fallback_texto, string $fallback_enfoque): array {
    $clave = normalizar_clave($item);
    return $catalogo[$clave] ?? [
        'texto'   => $fallback_texto,
        'enfoque' => $fallback_enfoque
    ];
}

$total_etapas = count($educacion);
$etapas_concluidas = count(array_filter($educacion, function ($e) {
    return strtolower(trim($e['estado'] ?? '')) === 'concluido';
}));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($perfil['nombre_completo']); ?> — Perfil Personal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="top">

    <!-- BARRA DE PROGRESO SUPERIOR -->
    <div class="reading-progress" id="readingProgress"></div>

    <!-- PESTAÑAS DE NAVEGACIÓN EN LA MISMA PÁGINA -->
    <nav class="tabs-nav" aria-label="Secciones del perfil">
        <div class="container tabs-wrapper">
            <a href="#top" class="brand-mini">
                <span class="status-dot"></span>
                <?php echo htmlspecialchars($perfil['nombre_completo']); ?>
            </a>
            <div class="tabs-list">
                <a href="#sobre-mi" class="tab-btn active">Sobre mí</a>
                <a href="#educacion" class="tab-btn">Educación</a>
                <a href="#habilidades" class="tab-btn">Habilidades</a>
                <a href="#intereses" class="tab-btn">Intereses</a>
                <a href="#contacto" class="tab-btn">Contacto</a>
            </div>
        </div>
    </nav>

    <!-- ENCABEZADO OSCURO AZULADO -->
    <header class="hero">
        <div class="hero-glow"></div>
        <div class="container hero-content">
            <div class="avatar-ring">
                <img src="<?php echo htmlspecialchars($perfil['foto_perfil']); ?>" alt="Foto de <?php echo htmlspecialchars($perfil['nombre_completo']); ?>" class="avatar">
            </div>
            <div class="hero-text">
                <div class="hero-badges">
                    <span class="badge badge-sky"><?php echo htmlspecialchars($perfil['ubicacion']); ?></span>
                    <span class="badge badge-indigo"><?php echo htmlspecialchars($perfil['apodo_o_rol']); ?></span>
                    <span class="badge badge-cyan" id="saludoDinamico">Hola</span>
                </div>
                <h1><?php echo htmlspecialchars($perfil['nombre_completo']); ?></h1>
                <blockquote class="quote">“<?php echo htmlspecialchars($perfil['frase_destacada']); ?>”</blockquote>
            </div>
        </div>
    </header>

    <!-- DISEÑO PRINCIPAL DE 2 COLUMNAS EN PC -->
    <main class="container page-grid">

        <!-- COLUMNA PRINCIPAL -->
        <div class="main-col">

            <!-- SOBRE MÍ -->
            <section id="sobre-mi" class="card section-block">
                <div class="section-heading">
                    <h2>Sobre mí</h2>
                    <span class="hint-chip">Perfil</span>
                </div>
                <p class="bio-text"><?php echo nl2br(htmlspecialchars($perfil['sobre_mi'])); ?></p>
                <div class="highlights-strip">
                    <div class="mini-pill">
                        <span class="mini-label">Formación actual</span>
                        <strong>UNS — Ing. de Sistemas</strong>
                    </div>
                    <div class="mini-pill">
                        <span class="mini-label">Deporte favorito</span>
                        <strong>Básquet</strong>
                    </div>
                    <div class="mini-pill">
                        <span class="mini-label">Ubicación</span>
                        <strong><?php echo htmlspecialchars($perfil['ubicacion']); ?></strong>
                    </div>
                </div>
            </section>

            <!-- TRAYECTORIA EDUCATIVA -->
            <section id="educacion" class="card section-block">
                <div class="section-heading">
                    <h2>Trayectoria educativa</h2>
                    <div class="filter-group" role="group" aria-label="Filtrar trayectoria educativa">
                        <button type="button" class="filter-btn active" data-filter="all">Todas</button>
                        <button type="button" class="filter-btn" data-filter="En curso">En curso</button>
                        <button type="button" class="filter-btn" data-filter="Concluido">Concluido</button>
                    </div>
                </div>

                <div class="edu-list">
                    <?php foreach ($educacion as $index => $item): ?>
                        <?php $es_actual = strtolower(trim($item['estado'] ?? '')) === 'en curso'; ?>
                        <article class="edu-item <?php echo $es_actual ? 'edu-current' : ''; ?>" data-status="<?php echo htmlspecialchars($item['estado']); ?>">
                            <div class="edu-marker">0<?php echo $index + 1; ?></div>
                            <div class="edu-body">
                                <div class="edu-header">
                                    <span class="edu-level"><?php echo htmlspecialchars($item['nivel']); ?></span>
                                    <span class="edu-period"><?php echo htmlspecialchars($item['periodo']); ?></span>
                                </div>
                                <h3><?php echo htmlspecialchars($item['institucion']); ?></h3>
                                <p class="edu-detail"><?php echo htmlspecialchars($item['detalle']); ?></p>
                                <?php if (!empty($item['estado'])): ?>
                                    <span class="edu-status <?php echo $es_actual ? 'status-active' : 'status-done'; ?>">
                                        <?php echo htmlspecialchars($item['estado']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- HABILIDADES E INTERESES -->
            <div class="two-cols">
                <section id="habilidades" class="card section-block">
                    <div class="section-heading">
                        <h2>Habilidades</h2>
                        <span class="hint-chip">Interactivo</span>
                    </div>
                    <div class="pill-list" id="habilidadesList">
                        <?php foreach ($habilidades as $idx => $hab): ?>
                            <?php if (!empty(trim($hab))): ?>
                                <?php
                                $info = obtener_info_item(
                                    $hab,
                                    $descripciones_habilidades,
                                    'Área en la que aplico y sigo fortaleciendo mis conocimientos.',
                                    'Desarrollo'
                                );
                                ?>
                                <button
                                    type="button"
                                    class="pill-btn <?php echo $idx === 0 ? 'selected' : ''; ?>"
                                    data-detail="<?php echo htmlspecialchars($info['texto']); ?>"
                                    data-focus="<?php echo htmlspecialchars($info['enfoque']); ?>">
                                    <?php echo htmlspecialchars($hab); ?>
                                </button>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php
                    $primera_hab = $habilidades[0] ?? '';
                    $primera_info = obtener_info_item(
                        $primera_hab,
                        $descripciones_habilidades,
                        'Selecciona una habilidad para ver su detalle.',
                        'Detalle'
                    );
                    ?>
                    <div class="info-callout" id="habilidadInfo">
                        <span class="callout-tag" id="habilidadFocus"><?php echo htmlspecialchars($primera_info['enfoque']); ?></span>
                        <p id="habilidadText"><?php echo htmlspecialchars($primera_info['texto']); ?></p>
                    </div>
                </section>

                <section id="intereses" class="card section-block">
                    <div class="section-heading">
                        <h2>Intereses</h2>
                        <span class="hint-chip">Interactivo</span>
                    </div>
                    <div class="pill-list pill-indigo" id="interesesList">
                        <?php foreach ($intereses as $idx => $int): ?>
                            <?php if (!empty(trim($int))): ?>
                                <?php
                                $info_int = obtener_info_item(
                                    $int,
                                    $descripciones_intereses,
                                    'Parte importante de lo que disfruto fuera de lo académico.',
                                    'Personal'
                                );
                                ?>
                                <button
                                    type="button"
                                    class="pill-btn <?php echo $idx === 0 ? 'selected' : ''; ?>"
                                    data-detail="<?php echo htmlspecialchars($info_int['texto']); ?>"
                                    data-focus="<?php echo htmlspecialchars($info_int['enfoque']); ?>">
                                    <?php echo htmlspecialchars($int); ?>
                                </button>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php
                    $primer_int = $intereses[0] ?? '';
                    $primer_info_int = obtener_info_item(
                        $primer_int,
                        $descripciones_intereses,
                        'Selecciona un interés para ver su detalle.',
                        'Detalle'
                    );
                    ?>
                    <div class="info-callout info-indigo" id="interesInfo">
                        <span class="callout-tag tag-indigo-soft" id="interesFocus"><?php echo htmlspecialchars($primer_info_int['enfoque']); ?></span>
                        <p id="interesText"><?php echo htmlspecialchars($primer_info_int['texto']); ?></p>
                    </div>
                </section>
            </div>

            <!-- CONTACTO -->
            <section id="contacto" class="card section-block">
                <div class="section-heading">
                    <h2>Contacto y redes</h2>
                    <span class="hint-chip">Conexión</span>
                </div>
                <div class="links-grid">
                    <?php foreach ($contacto as $etiqueta => $valor): ?>
                        <?php if (!empty(trim($valor))): ?>
                            <div class="link-box">
                                <div class="link-top">
                                    <strong><?php echo htmlspecialchars($etiqueta); ?></strong>
                                    <?php if (filter_var($valor, FILTER_VALIDATE_EMAIL)): ?>
                                        <button type="button" class="copy-btn" data-copy="<?php echo htmlspecialchars($valor); ?>">
                                            Copiar
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <?php if (filter_var($valor, FILTER_VALIDATE_URL)): ?>
                                    <a href="<?php echo htmlspecialchars($valor); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php echo htmlspecialchars($valor); ?>
                                    </a>
                                <?php elseif (filter_var($valor, FILTER_VALIDATE_EMAIL)): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($valor); ?>">
                                        <?php echo htmlspecialchars($valor); ?>
                                    </a>
                                <?php else: ?>
                                    <span><?php echo htmlspecialchars($valor); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </section>

        </div>

        <!-- COLUMNA LATERAL EN PC -->
        <aside class="side-col">
            <div class="card side-card">
                <h3>Panel de perfil</h3>
                <div class="stats-mini">
                    <div class="stat-box">
                        <span class="stat-num"><?php echo $total_etapas; ?></span>
                        <span class="stat-label">Etapas</span>
                    </div>
                    <div class="stat-box stat-cyan">
                        <span class="stat-num"><?php echo $etapas_concluidas; ?></span>
                        <span class="stat-label">Concluidas</span>
                    </div>
                    <div class="stat-box stat-indigo">
                        <span class="stat-num"><?php echo count($habilidades); ?></span>
                        <span class="stat-label">Skills</span>
                    </div>
                </div>

                <div class="side-meta">
                    <div class="meta-row">
                        <span>Universidad</span>
                        <strong>UNS</strong>
                    </div>
                    <div class="meta-row">
                        <span>Carrera</span>
                        <strong>Ing. de Sistemas</strong>
                    </div>
                    <div class="meta-row">
                        <span>Ciudad</span>
                        <strong><?php echo htmlspecialchars($perfil['ubicacion']); ?></strong>
                    </div>
                </div>

                <div class="side-actions">
                    <a href="#educacion" class="side-link-btn">Estudios</a>
                    <a href="#contacto" class="side-link-btn side-link-cyan">Contacto</a>
                </div>
            </div>

            <div class="card side-card side-tip">
                <h4>Explora el perfil</h4>
                <p>Usa las pestañas superiores para ir directo a cada sección o haz clic en las etiquetas de habilidades e intereses.</p>
            </div>
        </aside>

    </main>

    <!-- BOTÓN FLOTANTE VOLVER ARRIBA -->
    <a href="#top" class="scroll-top" id="scrollTopBtn" aria-label="Volver arriba" title="Volver arriba">↑</a>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo $perfil['anio_actual']; ?> <?php echo htmlspecialchars($perfil['nombre_completo']); ?> · Espacio Personal</p>
        </div>
    </footer>

    <script>
        const saludoEl = document.getElementById('saludoDinamico');
        if (saludoEl) {
            const hora = new Date().getHours();
            let saludo = 'Hola · Bienvenido';
            if (hora >= 5 && hora < 12) saludo = 'Buenos días · Perfil activo';
            else if (hora >= 12 && hora < 19) saludo = 'Buenas tardes · Perfil activo';
            else saludo = 'Buenas noches · Perfil activo';
            saludoEl.textContent = saludo;
        }

        const progressBar = document.getElementById('readingProgress');
        const scrollTopBtn = document.getElementById('scrollTopBtn');
        const tabs = document.querySelectorAll('.tab-btn');
        const sections = document.querySelectorAll('section[id]');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
            });
        });

        window.addEventListener('scroll', () => {
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrolled = docHeight > 0 ? (window.scrollY / docHeight) * 100 : 0;
            progressBar.style.width = Math.min(100, Math.max(0, scrolled)) + '%';

            if (scrollTopBtn) {
                scrollTopBtn.classList.toggle('visible', window.scrollY > 320);
            }

            let currentId = '';
            sections.forEach(sec => {
                const top = sec.getBoundingClientRect().top;
                if (top <= 145) {
                    currentId = sec.getAttribute('id');
                }
            });
            if (currentId) {
                tabs.forEach(t => {
                    t.classList.toggle('active', t.getAttribute('href') === '#' + currentId);
                });
            }
        }, { passive: true });

        const filterBtns = document.querySelectorAll('.filter-btn');
        const eduItems = document.querySelectorAll('.edu-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const filter = btn.dataset.filter;

                eduItems.forEach(item => {
                    const match = filter === 'all' || item.dataset.status === filter;
                    item.classList.toggle('hidden-item', !match);
                });
            });
        });

        function setupInteractivePills(listId, focusId, textId) {
            const container = document.getElementById(listId);
            const focusEl = document.getElementById(focusId);
            const textEl = document.getElementById(textId);
            if (!container || !focusEl || !textEl) return;

            const buttons = container.querySelectorAll('.pill-btn');
            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    buttons.forEach(b => b.classList.remove('selected'));
                    btn.classList.add('selected');
                    focusEl.textContent = btn.dataset.focus || 'Detalle';
                    textEl.textContent = btn.dataset.detail || '';
                });
            });
        }

        setupInteractivePills('habilidadesList', 'habilidadFocus', 'habilidadText');
        setupInteractivePills('interesesList', 'interesFocus', 'interesText');

        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const text = btn.dataset.copy;
                try {
                    await navigator.clipboard.writeText(text);
                    const original = btn.textContent;
                    btn.textContent = '¡Copiado!';
                    setTimeout(() => { btn.textContent = original; }, 1600);
                } catch (e) {
                    btn.textContent = 'Listo';
                }
            });
        });
    </script>

</body>
</html>