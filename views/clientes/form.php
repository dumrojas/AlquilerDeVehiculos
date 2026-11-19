<?php
require __DIR__ . "/../../models/database/database_interface.php";
require __DIR__ . "/../../models/database/database.php";
require __DIR__ . "/../../models/entities/vehiculo.php";
require __DIR__ . "/../../models/entities/cliente.php";
require __DIR__ . "/../../models/entities/reserva.php";
require __DIR__ . "/../../models/mappers/vehiculo_mappers.php";
require __DIR__ . "/../../models/mappers/cliente_mappers.php";
require __DIR__ . "/../../models/mappers/reserva_mappers.php";
require __DIR__ . "/../../models/repositories/vehiculos_repository/vehiculos_repository_interface.php";
require __DIR__ . "/../../models/repositories/vehiculos_repository/vehiculos_repository.php";
require __DIR__ . "/../../models/repositories/clientes_repository/clientes_repository_interface.php";
require __DIR__ . "/../../models/repositories/clientes_repository/clientes_repository.php";
require __DIR__ . "/../../models/repositories/reservas_repository/reservas_repository_interface.php";
require __DIR__ . "/../../models/repositories/reservas_repository/reservas_repository.php";
require __DIR__ . "/../../models/config/models_config.php";
require __DIR__ . "/../../controllers/exceptions/exceptions.php";
require __DIR__ . "/../../controllers/mappers/vehiculos_mapper_controller.php";
require __DIR__ . "/../../controllers/mappers/clientes_mapper_controller.php";
require __DIR__ . "/../../controllers/mappers/reservas_mapper_controller.php";
require __DIR__ . "/../../controllers/vehiculos/vehiculos_controller_interface.php";
require __DIR__ . "/../../controllers/vehiculos/vehiculos_controller.php";
require __DIR__ . "/../../controllers/clientes/clientes_controller_interface.php";
require __DIR__ . "/../../controllers/clientes/clientes_controller.php";
require __DIR__ . "/../../controllers/reservas/reservas_controller_interface.php";
require __DIR__ . "/../../controllers/reservas/reservas_controller.php";
require __DIR__ . "/../../controllers/config/controllers_config.php";

use app\controllers\config\ControllersConfig;

$titulo = "Crear cliente";
$action = "operaciones/registrar.php";
$id = 0;
$nombre = "";
$telefono = "";
$correo = "";
$numero_licencia = "";

if (!empty($_GET["id"])) {
    $titulo = "Modificar cliente";
    $action = "operaciones/modificar.php";
    $id = (int) $_GET["id"];

    $cliente = ControllersConfig::get_clientes_controller()->get_cliente($id);
    $nombre = $cliente->get_nombre();
    $telefono = $cliente->get_telefono();
    $correo = $cliente->get_correo();
    $numero_licencia = $cliente->get_numero_licencia();
}

$mensaje = $_GET["mensaje"] ?? "";
$tipo_mensaje = ($_GET["tipo"] ?? "error") === "ok" ? "alerta-ok" : "alerta-error";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo); ?> | AutoRent</title>
    <link rel="stylesheet" href="../../public/css/style.css?v=3">
</head>

<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a class="marca" href="../inicio.php">
                <span class="marca-icono">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 17h14" />
                        <path d="M4 17v-4l1.8-4.2A2 2 0 0 1 7.6 7.5h8.8a2 2 0 0 1 1.8 1.3L20 13v4" />
                        <circle cx="7" cy="17" r="1.5" />
                        <circle cx="17" cy="17" r="1.5" />
                    </svg>
                </span>
                <span class="marca-texto">
                    <strong>AutoRent</strong>
                    <small>Alquiler de vehículos</small>
                </span>
            </a>

            <nav class="menu">
                <a href="../inicio.php">Inicio</a>
                <a href="../vehiculos/inicio.php">Vehículos</a>
                <a href="../clientes/inicio.php" class="activo">Clientes</a>
                <a href="../reservas/inicio.php">Reservas</a>
                <a href="../reportes/inicio.php">Reportes</a>
            </nav>
        </div>
    </header>

    <main class="contenido">
        <?php if ($mensaje !== "") : ?>
            <p class="alerta <?php echo $tipo_mensaje; ?>"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif;
?>

<div class="cabecera-pagina">
    <div>
        <h2><?php echo htmlspecialchars($titulo); ?></h2>
        <p>Datos de contacto y licencia del cliente.</p>
    </div>
</div>

<form class="tarjeta" action="<?php echo $action; ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <div class="campo">
        <label for="nombre">Nombre completo:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
    </div>

    <div class="campo">
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>">
    </div>

    <div class="campo">
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($correo); ?>">
    </div>

    <div class="campo">
        <label for="numero_licencia">Número de licencia:</label>
        <input type="text" id="numero_licencia" name="numero_licencia"
            value="<?php echo htmlspecialchars($numero_licencia); ?>">
    </div>

    <div class="acciones">
        <button type="submit">Guardar</button>
        <a class="boton boton-secundario" href="inicio.php">Cancelar</a>
    </div>
</form>

    </main>

    <footer class="pie">
        <p>Proyecto académico &middot; Gestor de Alquiler de Vehículos &middot; PHP + MySQL (XAMPP)</p>
    </footer>
</body>

</html>
