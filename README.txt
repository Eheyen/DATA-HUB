DATA HUB - PRIMERA VERSION

CONTENIDO
- 01_data_hub_esquema.sql: crea la base data_hub, usuarios, importaciones, catastro (63 campos) y vw_catastro_siam.
- index.php: login con usuario o ficha + contraseña.
- crear_admin_inicial.php: formulario para crear el primer administrador.
- admin/index.php: panel principal.
- admin/usuarios.php: alta y listado de usuarios.
- config/database.php: credenciales MySQL.
- assets/style.css: interfaz adaptable.

INSTALACION
1. Suba la carpeta DATA_HUB_INICIAL al servidor web con PHP habilitado.
2. Importe 01_data_hub_esquema.sql en phpMyAdmin.
3. Edite config/database.php con host, nombre de base, usuario y contraseña MySQL reales.
4. Abra crear_admin_inicial.php desde el navegador.
5. Registre ficha, nombres, apellidos, usuario y contraseña del administrador.
6. Elimine crear_admin_inicial.php del servidor inmediatamente después de crear el administrador.
7. Abra index.php e inicie sesión.

USUARIOS
- ficha VARCHAR(20) es PRIMARY KEY.
- nombres y apellidos se guardan por separado.
- usuario es UNIQUE y sirve para iniciar sesión; también se puede iniciar sesión con ficha.
- password_hash almacena hash seguro, nunca la contraseña en texto.
- rol: ADMIN o USUARIO.
- estado: ACTIVO o INACTIVO.

IMPORTANTE
- Cambie DB_HOST, DB_USER y DB_PASS según los datos de su servidor.
- localhost solo es correcto si MySQL está en el mismo servidor que PHP.
- Requiere PHP 8.0+ con PDO MySQL y MySQL 8.0+.
- No contiene credenciales de acceso reales.
- Las secciones de fuentes, importaciones y dashboards son la estructura inicial; sus operaciones se implementarán en etapas posteriores.
