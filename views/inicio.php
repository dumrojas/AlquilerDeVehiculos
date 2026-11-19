<?php
require __DIR__ . "/../models/database/database_interface.php";
require __DIR__ . "/../models/database/database.php";
require __DIR__ . "/../models/entities/vehiculo.php";
require __DIR__ . "/../models/entities/cliente.php";
require __DIR__ . "/../models/entities/reserva.php";
require __DIR__ . "/../models/mappers/vehiculo_mappers.php";
require __DIR__ . "/../models/mappers/cliente_mappers.php";
require __DIR__ . "/../models/mappers/reserva_mappers.php";
require __DIR__ . "/../models/repositories/vehiculos_repository/vehiculos_repository_interface.php";
require __DIR__ . "/../models/repositories/vehiculos_repository/vehiculos_repository.php";
require __DIR__ . "/../models/repositories/clientes_repository/clientes_repository_interface.php";
require __DIR__ . "/../models/repositories/clientes_repository/clientes_repository.php";
require __DIR__ . "/../models/repositories/reservas_repository/reservas_repository_interface.php";
require __DIR__ . "/../models/repositories/reservas_repository/reservas_repository.php";
require __DIR__ . "/../models/config/models_config.php";
require __DIR__ . "/../controllers/exceptions/exceptions.php";
require __DIR__ . "/../controllers/mappers/vehiculos_mapper_controller.php";
require __DIR__ . "/../controllers/mappers/clientes_mapper_controller.php";
require __DIR__ . "/../controllers/mappers/reservas_mapper_controller.php";
require __DIR__ . "/../controllers/vehiculos/vehiculos_controller_interface.php";
require __DIR__ . "/../controllers/vehiculos/vehiculos_controller.php";
require __DIR__ . "/../controllers/clientes/clientes_controller_interface.php";
require __DIR__ . "/../controllers/clientes/clientes_controller.php";
require __DIR__ . "/../controllers/reservas/reservas_controller_interface.php";
require __DIR__ . "/../controllers/reservas/reservas_controller.php";
require __DIR__ . "/../controllers/config/controllers_config.php";

use app\controllers\config\ControllersConfig;

$vehiculos = ControllersConfig::get_vehiculos_controller()->get_vehiculos();
$reservas = ControllersConfig::get_reservas_controller()->get_reservas();

$disponibles = 0;
$alquilados = 0;
$mantenimiento = 0;

foreach ($vehiculos as $vehiculo) {
    switch ($vehiculo->get_estado()) {
        case "disponible":
            $disponibles++;
            break;
        case "alquilado":
            $alquilados++;
            break;
        case "mantenimiento":
            $mantenimiento++;
            break;
    }
}

$activas = array_values(array_filter(
    $reservas,
    fn($reserva): bool => $reserva->get_estado() === "activa"
));

$ultimas = array_slice($reservas, 0, 6);

$titulo = "Inicio";
$mensaje = $_GET["mensaje"] ?? "";
$tipo_mensaje = ($_GET["tipo"] ?? "error") === "ok" ? "alerta-ok" : "alerta-error";
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo); ?> | AutoRent</title>
    <link rel="stylesheet" href="../public/css/style.css?v=3">
</head>

<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a class="marca" href="inicio.php">
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
                <a href="inicio.php" class="activo">Inicio</a>
                <a href="vehiculos/inicio.php">Vehículos</a>
                <a href="clientes/inicio.php">Clientes</a>
                <a href="reservas/inicio.php">Reservas</a>
                <a href="reportes/inicio.php">Reportes</a>
            </nav>
        </div>
    </header>

    <main class="contenido">
        <?php if ($mensaje !== "") : ?>
            <p class="alerta <?php echo $tipo_mensaje; ?>"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif;
?>

<section class="hero">
    <span class="hero-etiqueta">Panel de control</span>
    <h2>Gestiona tu flota y las reservas en un solo lugar</h2>
    <p>
        Registra vehículos y clientes, controla la disponibilidad en tiempo real y
        registra devoluciones sin perder el historial.
    </p>
    <div class="hero-acciones">
        <a class="boton boton-inverso" href="reservas/form.php">Nueva reserva</a>
        <a class="boton boton-contorno" href="vehiculos/form.php">Agregar vehículo</a>
        <a class="boton boton-contorno" href="reportes/inicio.php">Ver disponibilidad</a>
    </div>
</section>

<section class="tarjetas">
    <article class="tarjeta-metrica">
        <span>Vehículos</span>
        <strong><?php echo count($vehiculos); ?></strong>
    </article>
    <article class="tarjeta-metrica">
        <span>Disponibles</span>
        <strong><?php echo $disponibles; ?></strong>
    </article>
    <article class="tarjeta-metrica">
        <span>Alquilados</span>
        <strong><?php echo $alquilados; ?></strong>
    </article>
    <article class="tarjeta-metrica">
        <span>Mantenimiento</span>
        <strong><?php echo $mantenimiento; ?></strong>
    </article>
    <article class="tarjeta-metrica">
        <span>Reservas activas</span>
        <strong><?php echo count($activas); ?></strong>
    </article>
</section>

<div class="cabecera-pagina">
    <div>
        <h3>Últimas reservas</h3>
        <p>Los movimientos más recientes de la flota.</p>
    </div>
    <a class="boton boton-secundario" href="reservas/inicio.php">Ver todas</a>
</div>

<?php if (count($ultimas) === 0) : ?>
    <p class="vacio">Todavía no hay reservas registradas.</p>
<?php else : ?>
    <div class="tabla-envoltura">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Días</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ultimas as $reserva) : ?>
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
