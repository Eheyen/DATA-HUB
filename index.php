<?php
declare(strict_types=1);
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/config/database.php';

if (!empty($_SESSION['usuario'])) {
    header('Location: admin/index.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim((string)($_POST['usuario'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    try {
        $stmt = db()->prepare("SELECT ficha, nombres, apellidos, usuario, password_hash, rol, estado
                               FROM usuarios WHERE usuario = :usuario OR ficha = :ficha LIMIT 1");
        $stmt->execute(['usuario' => $usuario, 'ficha' => $usuario]);
        $row = $stmt->fetch();
        if ($row && $row['estado'] === 'ACTIVO' && password_verify($password, $row['password_hash'])) {
            session_regenerate_id(true);
            unset($row['password_hash']);
            $_SESSION['usuario'] = $row;
            db()->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE ficha = ?")->execute([$row['ficha']]);
            header('Location: admin/index.php');
            exit;
        }
        $error = 'Usuario/ficha o contraseña incorrectos, o cuenta inactiva.';
    } catch (Throwable $e) {
        $error = 'No se pudo conectar con la base de datos. Revise config/database.php y que haya importado el SQL.';
    }
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>DATA HUB | Iniciar sesión</title><link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-body">
<div class="water water-one"></div><div class="water water-two"></div>
<main class="login-wrap">
  <section class="brand-panel">
    <div class="brand-mark">DH</div>
    <p class="eyebrow">PLATAFORMA CENTRAL</p>
    <h1>DATA HUB</h1>
    <p>Un solo centro de datos. Múltiples aplicaciones y dashboards.</p>
    <div class="brand-line"></div><small>GESTIÓN · INTEGRACIÓN · ANÁLISIS</small>
  </section>
  <section class="login-panel">
    <div class="login-heading"><span class="login-icon">↗</span><p class="eyebrow">BIENVENIDO</p><h2>Iniciar sesión</h2><p>Ingrese sus credenciales para continuar.</p></div>
    <?php if ($error): ?><div class="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <form method="post" autocomplete="on">
      <label for="usuario">Usuario o ficha</label>
      <input id="usuario" name="usuario" required autofocus autocomplete="username" placeholder="Ingrese su usuario o ficha">
      <label for="password">Contraseña</label>
      <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="Ingrese su contraseña">
      <button class="btn-primary" type="submit">Ingresar <span>→</span></button>
    </form>
    <p class="login-foot">Acceso restringido · DATA HUB</p>
  </section>
</main>
</body></html>
