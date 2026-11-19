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

$vehiculos = ControllersConfig::get_vehiculos_controller()->get_vehiculos();

$titulo = "Vehículos";
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
                <a href="../vehiculos/inicio.php" class="activo">Vehículos</a>
                <a href="../clientes/inicio.php">Clientes</a>
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
        <h2>Vehículos</h2>
        <p>Flota completa con el estado de cada unidad en tiempo real.</p>
    </div>
    <a class="boton" href="form.php">Nuevo vehículo</a>
</div>

<?php if (count($vehiculos) === 0) : ?>
    <p class="vacio">No hay vehículos registrados todavía. Empieza agregando el primero.</p>
<?php else : ?>
    <section class="grid-vehiculos">
        <?php foreach ($vehiculos as $vehiculo) : ?>
            <article class="vehiculo-card">
                <div class="vehiculo-card-top">
                    <div>
                        <h3><?php echo htmlspecialchars($vehiculo->get_marca() . " " . $vehiculo->get_modelo()); ?></h3>
                        <p class="vehiculo-sub"><?php echo $vehiculo->get_anio(); ?></p>
                    </div>
                    <span class="etiqueta estado-<?php echo htmlspecialchars($vehiculo->get_estado()); ?>">
                        <?php echo htmlspecialchars($vehiculo->get_estado()); ?>
                    </span>
                </div>

                <div class="vehiculo-datos">
                    <span class="chip">
                        <?php echo $vehiculo->get_categoria() !== "" ? htmlspecialchars($vehiculo->get_categoria()) : "Sin categoría"; ?>
                    </span>
                    <span class="chip">Unidad #<?php echo $vehiculo->get_id(); ?></span>
                </div>

                <div class="vehiculo-card-acciones">
                    <a class="boton boton-mini"
                        href="form.php?id=<?php echo $vehiculo->get_id(); ?>">Editar</a>
                    <a class="boton boton-mini boton-peligro"
                        href="operaciones/borrar.php?id=<?php echo $vehiculo->get_id(); ?>"
                        onclick="return confirm('¿Eliminar este vehículo?');">Eliminar</a>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

    </main>

    <footer class="pie">
        <p>Proyecto académico &middot; Gestor de Alquiler de Vehículos &middot; PHP + MySQL (XAMPP)</p>
    </footer>
</body>

</html>
