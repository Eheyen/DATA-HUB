<?php
declare(strict_types=1);
require __DIR__ . '/config/database.php';
$mensaje = '';
$error = '';
try {
    $count = (int)db()->query("SELECT COUNT(*) FROM usuarios WHERE rol='ADMIN'")->fetchColumn();
    if ($count > 0) {
        http_response_code(403);
        exit('Ya existe un administrador. Elimine este archivo del servidor.');
    }
} catch (Throwable $e) {
    exit('Primero importe 01_data_hub_esquema.sql y configure config/database.php.');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ficha = strtoupper(trim((string)($_POST['ficha'] ?? '')));
    $nombres = trim((string)($_POST['nombres'] ?? ''));
    $apellidos = trim((string)($_POST['apellidos'] ?? ''));
    $usuario = trim((string)($_POST['usuario'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if (!$ficha || !$nombres || !$apellidos || !$usuario || strlen($password) < 10) {
        $error = 'Complete todos los campos. La contraseña debe tener al menos 10 caracteres.';
    } else {
        try {
            $q = db()->prepare("INSERT INTO usuarios (ficha,nombres,apellidos,usuario,password_hash,rol)
                                VALUES (?,?,?,?,?,'ADMIN')");
            $q->execute([$ficha,$nombres,$apellidos,$usuario,password_hash($password,PASSWORD_DEFAULT)]);
            $mensaje = 'Administrador creado. Por seguridad, elimine ahora crear_admin_inicial.php del servidor.';
        } catch (Throwable $e) { $error = 'No se pudo crear el administrador. Revise que la ficha y el usuario no existan.'; }
    }
}
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Crear administrador DATA HUB</title><link rel="stylesheet" href="assets/style.css"></head><body class="login-body"><main class="setup-card"><p class="eyebrow">CONFIGURACIÓN INICIAL</p><h1>Crear administrador</h1><p>Este formulario solo funciona mientras no exista una cuenta ADMIN.</p><?php if($mensaje): ?><div class="success"><?= htmlspecialchars($mensaje) ?></div><a class="btn-primary" href="index.php">Ir al login</a><?php else: ?><?php if($error): ?><div class="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?><form method="post"><label>Ficha (PRIMARY KEY)</label><input name="ficha" required maxlength="20"><label>Nombres</label><input name="nombres" required><label>Apellidos</label><input name="apellidos" required><label>Usuario</label><input name="usuario" required><label>Contraseña (mínimo 10 caracteres)</label><input type="password" name="password" minlength="10" required><button class="btn-primary">Crear administrador</button></form><?php endif; ?></main></body></html>
