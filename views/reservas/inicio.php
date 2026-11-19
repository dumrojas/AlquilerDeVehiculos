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

$controller = ControllersConfig::get_reservas_controller();

$reservas = $controller->get_reservas();
$filtro = $_GET["estado"] ?? "";

if (in_array($filtro, ["activa", "completada", "cancelada"], true)) {
    $reservas = array_values(array_filter(
        $reservas,
        fn($reserva): bool => $reserva->get_estado() === $filtro
    ));
}

$titulo = "Reservas";
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
        <h2>Reservas</h2>
        <p>Controla alquileres, devoluciones y cancelaciones.</p>
    </div>
    <a class="boton" href="form.php">Nueva reserva</a>
</div>

<div class="acciones">
    <a class="boton boton-mini <?php echo $filtro === "" ? "" : "boton-secundario"; ?>" href="inicio.php">Todas</a>
    <a class="boton boton-mini <?php echo $filtro === "activa" ? "" : "boton-secundario"; ?>"
        href="inicio.php?estado=activa">Activas</a>
    <a class="boton boton-mini <?php echo $filtro === "completada" ? "" : "boton-secundario"; ?>"
        href="inicio.php?estado=completada">Completadas</a>
    <a class="boton boton-mini <?php echo $filtro === "cancelada" ? "" : "boton-secundario"; ?>"
        href="inicio.php?estado=cancelada">Canceladas</a>
</div>

<?php if (count($reservas) === 0) : ?>
    <p class="vacio">No hay reservas para mostrar.</p>
<?php else : ?>
    <div class="tabla-envoltura">
        <table class="tabla-acciones">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Días</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $reserva) : ?>
                    <tr>
                        <td><?php echo $reserva->get_id(); ?></td>
                        <td><?php echo htmlspecialchars($reserva->get_cliente_nombre()); ?></td>
                        <td><?php echo htmlspecialchars($reserva->get_vehiculo_descripcion()); ?></td>
                        <td><?php echo htmlspecialchars($reserva->get_fecha_inicio()); ?></td>
                        <td><?php echo htmlspecialchars($reserva->get_fecha_fin()); ?></td>
                        <td><?php echo $reserva->get_dias(); ?></td>
                        <td>
                            <span class="etiqueta estado-<?php echo htmlspecialchars($reserva->get_estado()); ?>">
                                <?php echo htmlspecialchars($reserva->get_estado()); ?>
                            </span>
                        </td>
                        <td>
                            <div class="celda-acciones">
                                <?php if ($reserva->get_estado() === "activa") : ?>
                                    <a class="accion-link"
                                        href="operaciones/devolver.php?id=<?php echo $reserva->get_id(); ?>"
                                        onclick="return confirm('¿Registrar la devolución del vehículo?');">Devolver</a>
                                    <a class="accion-link"
                                        href="operaciones/cancelar.php?id=<?php echo $reserva->get_id(); ?>"
                                        onclick="return confirm('¿Cancelar esta reserva?');">Cancelar</a>
                                <?php endif; ?>
                                <a class="accion-link"
                                    href="form.php?id=<?php echo $reserva->get_id(); ?>">Editar</a>
                                <a class="accion-link peligro"
                                    href="operaciones/borrar.php?id=<?php echo $reserva->get_id(); ?>"
                                    onclick="return confirm('¿Eliminar el registro de esta reserva?');">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

    </main>

    <footer class="pie">
        <p>Proyecto académico &middot; Gestor de Alquiler de Vehículos &middot; PHP + MySQL (XAMPP)</p>
    </footer>
</body>

</html>
