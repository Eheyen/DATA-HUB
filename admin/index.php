<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
require_login();
$u = $_SESSION['usuario'];
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>DATA HUB | Panel</title><link rel="stylesheet" href="../assets/style.css"></head>
<body class="app-body">
<aside class="sidebar"><div class="side-brand"><b>DH</b><span>DATA HUB</span></div><div class="side-label">PLATAFORMA</div>
<a class="side-link active" href="index.php">▦ &nbsp; Resumen</a>
<a class="side-link" href="#">▤ &nbsp; Fuentes de datos</a><a class="side-link" href="#">⇧ &nbsp; Importaciones</a>
<a class="side-link" href="#">▣ &nbsp; Aplicaciones</a>
<?php if ($u['rol'] === 'ADMIN'): ?><div class="side-label">CONFIGURACIÓN</div><a class="side-link" href="usuarios.php">♙ &nbsp; Usuarios</a><?php endif; ?>
<div class="side-bottom"><div class="user-chip"><b><?= htmlspecialchars($u['nombres'].' '.$u['apellidos']) ?></b><small><?= htmlspecialchars($u['ficha'].' · '.$u['rol']) ?></small></div><a class="side-link" href="../logout.php">↪ &nbsp; Cerrar sesión</a></div></aside>
<main class="main-content"><header class="topbar"><div><span class="eyebrow">CENTRO DE CONTROL</span><h1>Panel principal</h1></div><div class="top-user"><?= htmlspecialchars($u['usuario']) ?></div></header>
<section class="welcome"><div><span class="eyebrow">DATA HUB</span><h2>Bienvenido, <?= htmlspecialchars($u['nombres']) ?></h2><p>Administre las fuentes de información y acceda a las aplicaciones habilitadas.</p></div><div class="welcome-symbol">◈</div></section>
<div class="section-title"><h2>Centro de gestión</h2><span>PRIMERA VERSIÓN</span></div>
<div class="cards">
<article class="dash-card"><div class="card-icon blue">▤</div><h3>Catastro comercial</h3><p>Almacenamiento histórico del catálogo comercial.</p><span class="status-tag">Base preparada</span></article>
<article class="dash-card"><div class="card-icon cyan">⇧</div><h3>Importaciones</h3><p>Control de archivos y periodos de carga.</p><span class="status-tag pending">En desarrollo</span></article>
<article class="dash-card"><div class="card-icon violet">▦</div><h3>Aplicaciones</h3><p>Acceso centralizado a dashboards y módulos.</p><span class="status-tag pending">En desarrollo</span></article>
<?php if ($u['rol'] === 'ADMIN'): ?><a class="dash-card card-anchor" href="usuarios.php"><div class="card-icon green">♙</div><h3>Usuarios</h3><p>Gestione fichas, nombres, apellidos, cuentas y roles.</p><span class="status-tag">Administración</span></a><?php endif; ?>
</div>
</main></body></html>
