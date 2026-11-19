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

$vehiculos_controller = ControllersConfig::get_vehiculos_controller();
$clientes_controller = ControllersConfig::get_clientes_controller();
$reservas_controller = ControllersConfig::get_reservas_controller();

$vehiculos = $vehiculos_controller->get_vehiculos();
$clientes = $clientes_controller->get_clientes();
$disponibles = $vehiculos_controller->get_vehiculos_disponibles();

$vehiculo_id = (int) ($_GET["vehiculo_id"] ?? 0);
$cliente_id = (int) ($_GET["cliente_id"] ?? 0);

$historial_vehiculo = $vehiculo_id > 0 ? $reservas_controller->historial_por_vehiculo($vehiculo_id) : [];
$historial_cliente = $cliente_id > 0 ? $reservas_controller->historial_por_cliente($cliente_id) : [];

$titulo = "Reportes";
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
                <a href="../reservas/inicio.php">Reservas</a>
                <a href="../reportes/inicio.php" class="activo">Reportes</a>
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
        <h2>Reportes</h2>
        <p>Disponibilidad actual e historial de alquileres por vehículo y por cliente.</p>
    </div>
</div>

<h3>Disponibles ahora</h3>

<?php if (count($disponibles) === 0) : ?>
    <p class="vacio">No hay vehículos disponibles en este momento.</p>
<?php else : ?>
    <div class="tabla-envoltura">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Categoría</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($disponibles as $vehiculo) : ?>
                    <tr>
                        <td><?php echo $vehiculo->get_id(); ?></td>
                        <td><?php echo htmlspecialchars($vehiculo->get_marca()); ?></td>
                        <td><?php echo htmlspecialchars($vehiculo->get_modelo()); ?></td>
                        <td><?php echo $vehiculo->get_anio(); ?></td>
                        <td><?php echo htmlspecialchars($vehiculo->get_categoria()); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<h3>Historial por vehículo</h3>

<form class="tarjeta" method="get" action="inicio.php">
    <div class="campo">
        <label for="vehiculo_id">Vehículo:</label>
        <select id="vehiculo_id" name="vehiculo_id">
            <option value="">Seleccione un vehículo</option>
            <?php foreach ($vehiculos as $vehiculo) : ?>
                <option value="<?php echo $vehiculo->get_id(); ?>"
                    <?php echo $vehiculo_id === $vehiculo->get_id() ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($vehiculo->get_descripcion()); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="acciones">
        <button type="submit">Consultar</button>
    </div>
</form>

<?php if ($vehiculo_id > 0) : ?>
    <?php if (count($historial_vehiculo) === 0) : ?>
        <p class="vacio">Ese vehículo no tiene reservas registradas.</p>
    <?php else : ?>
        <div class="tabla-envoltura" style="margin-top:20px;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Días</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial_vehiculo as $reserva) : ?>
                        <tr>
                            <td><?php echo $reserva->get_id(); ?></td>
                            <td><?php echo htmlspecialchars($reserva->get_cliente_nombre()); ?></td>
                            <td><?php echo htmlspecialchars($reserva->get_fecha_inicio()); ?></td>
                            <td><?php echo htmlspecialchars($reserva->get_fecha_fin()); ?></td>
                            <td><?php echo $reserva->get_dias(); ?></td>
                            <td>
                                <span class="etiqueta estado-<?php echo htmlspecialchars($reserva->get_estado()); ?>">
                                    <?php echo htmlspecialchars($reserva->get_estado()); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php endif; ?>

<h3>Historial por cliente</h3>

<form class="tarjeta" method="get" action="inicio.php">
    <div class="campo">
        <label for="cliente_id">Cliente:</label>
        <select id="cliente_id" name="cliente_id">
            <option value="">Seleccione un cliente</option>
            <?php foreach ($clientes as $cliente) : ?>
                <option value="<?php echo $cliente->get_id(); ?>"
                    <?php echo $cliente_id === $cliente->get_id() ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($cliente->get_nombre()); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="acciones">
        <button type="submit">Consultar</button>
    </div>
</form>

<?php if ($cliente_id > 0) : ?>
    <?php if (count($historial_cliente) === 0) : ?>
        <p class="vacio">Ese cliente no tiene reservas registradas.</p>
    <?php else : ?>
        <div class="tabla-envoltura" style="margin-top:20px;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vehículo</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Días</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial_cliente as $reserva) : ?>
                        <tr>
                            <td><?php echo $reserva->get_id(); ?></td>
                            <td><?php echo htmlspecialchars($reserva->get_vehiculo_descripcion()); ?></td>
                            <td><?php echo htmlspecialchars($reserva->get_fecha_inicio()); ?></td>
                            <td><?php echo htmlspecialchars($reserva->get_fecha_fin()); ?></td>
                            <td><?php echo $reserva->get_dias(); ?></td>
                            <td>
                                <span class="etiqueta estado-<?php echo htmlspecialchars($reserva->get_estado()); ?>">
                                    <?php echo htmlspecialchars($reserva->get_estado()); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php endif; ?>

    </main>

    <footer class="pie">
        <p>Proyecto académico &middot; Gestor de Alquiler de Vehículos &middot; PHP + MySQL (XAMPP)</p>
    </footer>
</body>

</html>
