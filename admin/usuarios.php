<?php
declare(strict_types=1);
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../config/database.php';
require_admin();
$mensaje = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ficha = strtoupper(trim((string)($_POST['ficha'] ?? '')));
    $nombres = trim((string)($_POST['nombres'] ?? ''));
    $apellidos = trim((string)($_POST['apellidos'] ?? ''));
    $usuario = trim((string)($_POST['usuario'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $rol = ($_POST['rol'] ?? 'USUARIO') === 'ADMIN' ? 'ADMIN' : 'USUARIO';
    if ($ficha === '' || $nombres === '' || $apellidos === '' || $usuario === '' || strlen($password) < 10) {
        $error = 'Complete todos los campos. La contraseña debe tener al menos 10 caracteres.';
    } else {
        try {
            $stmt = db()->prepare("INSERT INTO usuarios (ficha,nombres,apellidos,usuario,password_hash,rol)
                                   VALUES (:ficha,:nombres,:apellidos,:usuario,:hash,:rol)");
            $stmt->execute(['ficha'=>$ficha,'nombres'=>$nombres,'apellidos'=>$apellidos,
                'usuario'=>$usuario,'hash'=>password_hash($password, PASSWORD_DEFAULT),'rol'=>$rol]);
            $mensaje = 'Usuario creado correctamente.';
        } catch (PDOException $e) {
            $error = $e->getCode() === '23000' ? 'La ficha o el nombre de usuario ya existe.' : 'No se pudo guardar el usuario. Verifique la conexión.';
        }
    }
}
$usuarios = db()->query("SELECT ficha,nombres,apellidos,usuario,rol,estado,fecha_creacion FROM usuarios ORDER BY fecha_creacion DESC")->fetchAll();
?>
<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>DATA HUB | Usuarios</title><link rel="stylesheet" href="../assets/style.css"></head>
<body class="app-body"><aside class="sidebar"><div class="side-brand"><b>DH</b><span>DATA HUB</span></div><div class="side-label">PLATAFORMA</div><a class="side-link" href="index.php">▦ &nbsp; Resumen</a><div class="side-label">CONFIGURACIÓN</div><a class="side-link active" href="usuarios.php">♙ &nbsp; Usuarios</a><div class="side-bottom"><a class="side-link" href="../logout.php">↪ &nbsp; Cerrar sesión</a></div></aside>
<main class="main-content"><header class="topbar"><div><span class="eyebrow">CONFIGURACIÓN</span><h1>Gestión de usuarios</h1></div><a class="btn-light" href="index.php">← Volver</a></header>
<?php if($mensaje): ?><div class="success"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?><?php if($error): ?><div class="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<section class="form-card"><div class="section-title"><h2>Registrar usuario</h2><span>FICHA COMO PRIMARY KEY</span></div>
<form method="post" class="user-form">
<div><label>Ficha (clave primaria)</label><input name="ficha" maxlength="20" required placeholder="Ej. 001245"></div>
<div><label>Nombres</label><input name="nombres" maxlength="100" required></div>
<div><label>Apellidos</label><input name="apellidos" maxlength="120" required></div>
<div><label>Nombre de usuario</label><input name="usuario" maxlength="50" required></div>
<div><label>Contraseña</label><input name="password" type="password" minlength="10" required placeholder="Mínimo 10 caracteres"></div>
<div><label>Rol</label><select name="rol"><option value="USUARIO">Usuario</option><option value="ADMIN">Administrador</option></select></div>
<div class="form-actions"><button class="btn-primary" type="submit">Crear usuario <span>＋</span></button></div>
</form></section>
<section class="table-card"><div class="section-title"><h2>Usuarios registrados</h2><span><?= count($usuarios) ?> CUENTAS</span></div><div class="table-wrap"><table><thead><tr><th>Ficha</th><th>Nombres</th><th>Apellidos</th><th>Usuario</th><th>Rol</th><th>Estado</th></tr></thead><tbody>
<?php foreach($usuarios as $r): ?><tr><td><?= htmlspecialchars($r['ficha']) ?></td><td><?= htmlspecialchars($r['nombres']) ?></td><td><?= htmlspecialchars($r['apellidos']) ?></td><td><?= htmlspecialchars($r['usuario']) ?></td><td><?= htmlspecialchars($r['rol']) ?></td><td><?= htmlspecialchars($r['estado']) ?></td></tr><?php endforeach; ?>
</tbody></table></div></section></main></body></html>
