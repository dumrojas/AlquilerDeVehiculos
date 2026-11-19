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

$clientes = ControllersConfig::get_clientes_controller()->get_clientes();
$vehiculos = ControllersConfig::get_vehiculos_controller()->get_vehiculos();

$titulo = "Crear reserva";
$action = "operaciones/registrar.php";
$id = 0;
$cliente_id = 0;
$vehiculo_id = 0;
$fecha_inicio = date("Y-m-d");
$fecha_fin = date("Y-m-d", strtotime("+1 day"));

if (!empty($_GET["id"])) {
    $titulo = "Modificar reserva";
    $action = "operaciones/modificar.php";
    $id = (int) $_GET["id"];

    $reserva = ControllersConfig::get_reservas_controller()->get_reserva($id);
    $cliente_id = $reserva->get_cliente_id();
    $vehiculo_id = $reserva->get_vehiculo_id();
    $fecha_inicio = $reserva->get_fecha_inicio();
    $fecha_fin = $reserva->get_fecha_fin();
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
                <a href="../clientes/inicio.php">Clientes</a>
                <a href="../reservas/inicio.php" class="activo">Reservas</a>
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
        <p>El sistema valida que el vehículo no tenga otra reserva activa en esas fechas.</p>
    </div>
</div>

<?php if (count($clientes) === 0 || count($vehiculos) === 0) : ?>
    <p class="alerta alerta-error">
        Para crear una reserva primero debe registrar al menos un cliente y un vehículo.
    </p>
<?php endif; ?>

<form class="tarjeta" action="<?php echo $action; ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <div class="campo">
        <label for="cliente_id">Cliente:</label>
        <select id="cliente_id" name="cliente_id" required>
            <option value="">Seleccione un cliente</option>
            <?php foreach ($clientes as $cliente) : ?>
                <option value="<?php echo $cliente->get_id(); ?>"
                    <?php echo $cliente_id === $cliente->get_id() ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($cliente->get_nombre()); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="campo">
        <label for="vehiculo_id">Vehículo:</label>
        <select id="vehiculo_id" name="vehiculo_id" required>
            <option value="">Seleccione un vehículo</option>
            <?php foreach ($vehiculos as $vehiculo) : ?>
                <option value="<?php echo $vehiculo->get_id(); ?>"
                    <?php echo $vehiculo_id === $vehiculo->get_id() ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($vehiculo->get_descripcion() . " - " . $vehiculo->get_estado()); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="campo">
        <label for="fecha_inicio">Fecha de inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio"
            value="<?php echo htmlspecialchars($fecha_inicio); ?>" required>
    </div>

    <div class="campo">
        <label for="fecha_fin">Fecha de fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin"
            value="<?php echo htmlspecialchars($fecha_fin); ?>" required>
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
