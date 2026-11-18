<?php
require_once __DIR__ . "/../configure/bootstrap.php";

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
$raiz = "../";
require __DIR__ . "/partials/header.php";
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

<?php require __DIR__ . "/partials/footer.php"; ?>
